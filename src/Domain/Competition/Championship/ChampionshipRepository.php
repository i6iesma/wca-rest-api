<?php

namespace App\Domain\Competition\Championship;

use App\Domain\Competition\CompetitionRepository;
use App\Infrastructure\Overview\Overview;
use App\Infrastructure\Overview\Pagination;
use Doctrine\DBAL\Connection;

readonly class ChampionshipRepository
{
    public function __construct(
        private Connection $connection,
        private CompetitionRepository $competitionRepository,
    ) {
    }

    public function findAll(): Overview
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('SQL_CALC_FOUND_ROWS champ.*')
            ->from('championships', 'champ')
            ->innerJoin('champ', 'Competitions', 'comp', 'champ.competition_id = comp.id')
            ->addOrderBy('comp.year', 'DESC')
            ->addOrderBy('comp.month', 'DESC')
            ->addOrderBy('comp.day', 'DESC');

        $results = $queryBuilder->executeQuery()->fetchAllAssociative();
        $total = $this->connection->executeQuery('SELECT FOUND_ROWS() as total;')->fetchOne();

        if (0 === count($results)) {
            return Overview::empty(Pagination::default());
        }

        $overview = Overview::empty(Pagination::default(), $total);
        foreach ($results as $result) {
            $overview->addItem($this->buildResult($result));
        }

        return $overview;
    }

    /**
     * @param array<mixed> $result
     */
    private function buildResult(array $result): Championship
    {
        return Championship::fromCompetitionAndRegion(
            $this->competitionRepository->find($result['competition_id']),
            // @TODO: slugify this.
            $result['championship_type'],
        );
    }
}
