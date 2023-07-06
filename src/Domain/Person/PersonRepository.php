<?php

namespace App\Domain\Person;

use App\Domain\Competition\Championship\Championship;
use App\Domain\Competition\Championship\ChampionshipRepository;
use App\Domain\Competition\Competition;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Continent\Country\Iso2Code;
use App\Domain\Rank\RankRepository;
use App\Domain\Result\ResultRepository;
use App\Infrastructure\Overview\Overview;
use App\Infrastructure\Overview\Pagination;
use Doctrine\DBAL\Connection;

readonly class PersonRepository
{
    public function __construct(
        private Connection $connection,
        private RankRepository $rankRepository,
        private CompetitionRepository $competitionRepository,
        private ChampionshipRepository $championshipRepository,
        private ResultRepository $resultRepository,
    ) {
    }

    public function findOneBy(
        Pagination $pagination,
    ): Overview {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->select('SQL_CALC_FOUND_ROWS p.*, c.iso2')
            ->from('Persons', 'p')
            ->innerJoin('p', 'Countries', 'c', 'p.countryId = c.id')
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getLimit())
            ->addOrderBy('id', 'ASC');

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
            $overview->addItem(Person::fromState(
                $result['id'],
                $result['name'],
                Iso2Code::fromString($result['iso2']),
                array_map(fn (Competition $competition) => $competition->getId(), $this->competitionRepository->findByPerson($result['id'])),
                $this->rankRepository->findByPerson($result['id']),
                $this->resultRepository->findByPerson($result['id']),
                array_map(fn (Championship $championship) => $championship->getId(), $this->championshipRepository->findByPerson($result['id'])),
            ));
        }

        return $overview;
    }
}
