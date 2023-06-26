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

    public function findOneBy(
        Pagination $pagination,
        Country $country = null,
        int $year = null,
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

        if ($country) {
            $queryBuilder->andWhere('c.iso2 = :iso2')
                ->setParameter('iso2', $country->getIso2Code());
        }

        if ($year) {
            $queryBuilder->andWhere('comp.year = :year')
                ->setParameter('year', $year);
        }

        $results = $queryBuilder->executeQuery()->fetchAllAssociative();
        $total = $this->connection->executeQuery('SELECT FOUND_ROWS() as total;')->fetchAssociative()['total'];

        if (0 === count($results)) {
            return Overview::empty(Pagination::default());
        }

        $overview = Overview::empty(
            count($results) == $pagination->getPageSize() ? $pagination : $pagination::fromPageNumberAndSize(
                $pagination->getPageNumber(),
                count($results)
            ),
            $total
        );

        foreach ($results as $result) {
            $overview->addItem($this->buildResult($result));
        }

        return $overview;
    }

    public function findByPerson(string $personId): array
    {
        $query = '
            SELECT comp.*, c.iso2
            FROM Competitions comp
            INNER JOIN Countries c ON comp.countryId = c.id
            WHERE comp.id IN (SELECT DISTINCT competitionId FROM Results WHERE personId = :personId)
        ';

        $results = $this->connection->executeQuery($query, [
            'personId' => $personId,
        ])->fetchAllAssociative();

        return array_map(fn (array $result) => $this->buildResult($result), $results);
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
