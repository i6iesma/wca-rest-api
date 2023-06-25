<?php

namespace App\Domain\Competition;

use App\Infrastructure\Overview\Overview;
use App\Infrastructure\Overview\Pagination;
use App\Infrastructure\Overview\Sorting\Sorting;
use App\Infrastructure\ValueObject\Country;
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
        Sorting $sorting,
    ): Overview {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('SQL_CALC_FOUND_ROWS comp.*, c.iso2')
            ->from('Competitions', 'comp')
            ->innerJoin('comp', 'Countries', 'c', 'comp.countryId = c.id')
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getLimit())
            ->orderBy(
                $sorting->getSortableFieldName(),
                $sorting->getSortingDirection()->toSql()
            );

        $results = $queryBuilder->executeQuery()->fetchAllAssociative();
        $total = $this->connection->executeQuery('SELECT FOUND_ROWS() as total;')->fetchAssociative()['total'];

        $overview = Overview::empty($pagination, $sorting, $total);
        foreach ($results as $result) {
            $overview->addItem(Competition::fromState(
                $result['id'],
                $result['name'],
                $result['cityName'],
                Country::fromIso2Code($result['iso2']),
                DateRange::fromFromDateAndTillDate(
                    SerializableDateTime::fromString(''),
                    SerializableDateTime::fromString('')
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
            ));
        }

        return $overview;
    }
}
