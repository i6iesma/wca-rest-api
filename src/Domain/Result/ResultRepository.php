<?php

namespace App\Domain\Result;

use Doctrine\DBAL\Connection;

class ResultRepository
{
    public function __construct(
        private Connection $connection
    ) {
    }

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
            ORDER BY c.year DESC,  c.month DESC, c.day DESC, e.rank ASC, rt.rank DESC
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
