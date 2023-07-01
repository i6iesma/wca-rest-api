<?php

namespace App\Domain\Result;

use App\Infrastructure\Overview\Overview;
use App\Infrastructure\Overview\Pagination;
use Doctrine\DBAL\Connection;

readonly class ResultRepository
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function findOneBy(
        Pagination $pagination,
        string $competitionId,
        string $eventId = null,
    ): Overview {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('r.*, rt.name as roundName, f.name as formatName')
            ->from('Results', 'r')
            ->innerJoin('r', 'RoundTypes', 'rt', 'r.roundTypeId = rt.id')
            ->innerJoin('r', 'Formats', 'f', 'r.formatId = f.id')
            ->innerJoin('r', 'Competitions', 'c', 'r.competitionId = c.id')
            ->andWhere('r.competitionId = :competitionId')
            ->setParameter('competitionId', $competitionId)
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getLimit());

        if ($eventId) {
            $queryBuilder
                ->andWhere('r.eventId = :event')
                ->setParameter('event', $eventId);
        }

        $results = $queryBuilder->executeQuery()->fetchAllAssociative();
        $total = $this->connection->executeQuery('SELECT FOUND_ROWS() as total;')->fetchOne();

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
            $overview->addItem(Result::fromState(
                $result['competitionId'],
                $result['personId'],
                $result['eventId'],
                $result['roundName'],
                $result['pos'],
                $result['best'],
                $result['average'],
                $result['formatName'],
                [
                    $result['value1'],
                    $result['value2'],
                    $result['value3'],
                    $result['value4'],
                    $result['value5'],
                ]
            ));
        }

        return $overview;
    }

    /**
     * @return \App\Domain\Result\Result[]
     */
    public function findByPerson(string $personId): array
    {
        $query = '
            SELECT r.*, rt.name as roundName, f.name as formatName
            FROM Results r
            INNER JOIN RoundTypes rt ON r.roundTypeId = rt.id
            INNER JOIN Formats f ON r.formatId = f.id
            INNER JOIN Competitions c ON r.competitionId = c.id
            INNER JOIN Events e ON r.eventId = e.id
            WHERE personId = :personId
            ORDER BY c.year DESC, c.month DESC, c.day DESC, e.rank ASC, rt.rank DESC
        ';

        $results = $this->connection->executeQuery($query, [
            'personId' => $personId,
        ])->fetchAllAssociative();

        return array_map(fn (array $result) => Result::fromState(
            $result['competitionId'],
            $result['personId'],
            $result['eventId'],
            $result['roundName'],
            $result['pos'],
            $result['best'],
            $result['average'],
            $result['formatName'],
            [
                $result['value1'],
                $result['value2'],
                $result['value3'],
                $result['value4'],
                $result['value5'],
            ]
        ), $results);
    }
}
