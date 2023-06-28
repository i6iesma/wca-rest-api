<?php

namespace App\Domain\Rank;

use Doctrine\DBAL\Connection;

readonly class RankRepository
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @return \App\Domain\Rank\Rank[]
     */
    public function findByPerson(string $personId): array
    {
        $query = '
            SELECT *, "average" as rankType
            FROM RanksAverage
            WHERE personId = :personId
            UNION
            SELECT *, "single" as rankType
            FROM RanksSingle
            WHERE personId = :personId
        ';

        $results = $this->connection->executeQuery($query, [
            'personId' => $personId,
        ])->fetchAllAssociative();

        return array_map(fn (array $result) => Rank::fromState(
            RankType::from($result['rankType']),
            $result['personId'],
            $result['eventId'],
            $result['best'],
            $result['worldRank'],
            $result['continentRank'],
            $result['countryRank'],
        ), $results);
    }
}
