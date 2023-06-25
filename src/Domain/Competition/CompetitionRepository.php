<?php

namespace App\Domain\Competition;

use App\Domain\Country\Country;
use App\Domain\Country\Iso2Code;
use App\Infrastructure\Overview\Overview;
use App\Infrastructure\Overview\Pagination;
use App\Infrastructure\ValueObject\Geography\Coordinates;
use App\Infrastructure\ValueObject\Time\DateRange;
use App\Infrastructure\ValueObject\Time\SerializableDateTime;
use Doctrine\DBAL\Connection;

readonly class CompetitionRepository
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function findAll(
        Pagination $pagination,
    ): Overview {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('SQL_CALC_FOUND_ROWS comp.*, c.iso2')
            ->from('Competitions', 'comp')
            ->innerJoin('comp', 'Countries', 'c', 'comp.countryId = c.id')
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getLimit())
            ->addOrderBy('year', 'DESC')
            ->addOrderBy('month', 'DESC')
            ->addOrderBy('day', 'DESC');

        $results = $queryBuilder->executeQuery()->fetchAllAssociative();
        $total = $this->connection->executeQuery('SELECT FOUND_ROWS() as total;')->fetchAssociative()['total'];

        $overview = Overview::empty($pagination, $total);
        foreach ($results as $result) {
            $overview->addItem($this->buildResult($result));
        }

        return $overview;
    }

    public function findByCountry(Country $country): Overview
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('SQL_CALC_FOUND_ROWS comp.*, c.iso2')
            ->from('Competitions', 'comp')
            ->innerJoin('comp', 'Countries', 'c', 'comp.countryId = c.id')
            ->andWhere('c.iso2 = :iso2')
            ->setParameter('iso2', $country->getIso2Code())
            ->addOrderBy('year', 'DESC')
            ->addOrderBy('month', 'DESC')
            ->addOrderBy('day', 'DESC');

        $results = $queryBuilder->executeQuery()->fetchAllAssociative();
        $total = $this->connection->executeQuery('SELECT FOUND_ROWS() as total;')->fetchAssociative()['total'];

        $overview = Overview::empty(
            $total > 1000 ? Pagination::fromPageNumberAndSize(1, $total) : Pagination::default(),
            $total
        );
        foreach ($results as $result) {
            $overview->addItem($this->buildResult($result));
        }

        return $overview;
    }

    private function buildResult(array $result): Competition
    {
        return Competition::fromState(
            $result['id'],
            $result['name'],
            $result['cityName'],
            Iso2Code::fromString($result['iso2']),
            DateRange::fromFromDateAndTillDate(
                SerializableDateTime::fromString($result['year'].'-'.$result['month'].'-'.$result['day']),
                SerializableDateTime::fromString($result['year'].'-'.$result['endMonth'].'-'.$result['endDay']),
            ),
            $result['cancelled'],
            explode(' ', $result['eventSpecs']),
            $result['wcaDelegate'],
            Venue::fromValues(
                $result['venue'],
                $result['venueAddress'],
                $result['venueDetails'],
                Coordinates::fromIntegers(
                    $result['latitude'],
                    $result['longitude'],
                )
            ),
            $result['organiser'],
            $result['information'],
            $result['external_website'],
        );
    }
}
