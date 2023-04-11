<?php

namespace App\Repository;

use App\Dto\ProjectFilter;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function add(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearch(ProjectFilter $filter): array
    {

        $sql = $filter->buildQuery('p');

        if (!empty($sql)) {
            $builder = $this->createQueryBuilder('p');
            $builder
                ->where($sql)
                ->setParameters($filter->params);
            return $builder->getQuery()->getResult();
        } else {
            return $this->findAll();
        }
        return [];
    }

    public function findByConditions(array $criteria, array $orderBy = []): array
    {
        $builder = $this->createQueryBuilder('p');
        if ($criteria['name'] ?? null) {
            $builder->andWhere('p.name LIKE :val')
                ->setParameter('val', "%" . $criteria['name'] . "%");
        }
        if ($criteria['type'] ?? null) {
            $builder->andWhere('p.type = :type')
                ->setParameter('type', $criteria['type']);
        }
        if ($criteria['cost'] ?? null) {
            $builder->andWhere('p.cost ' . $criteria['cost'][1] . " :cost")
                ->setParameter('cost', $criteria['cost'][0]);
        }
        if ($criteria['sector'] ?? null) {
            $builder->andWhere('p.sector = :sector')
                ->setParameter('sector', $criteria['sector']);
        }
        if ($criteria['company'] ?? null) {
            $builder->andWhere('p.company = :company')
                ->setParameter('company', $criteria['company']);
        }
        if ($criteria['startAt'] ?? null) {
            $builder->andWhere('p.startAt ' . $criteria['startAt'][1] . " :startAt")
                ->setParameter('startAt', $criteria['startAt'][0]);
        }
        if ($criteria['endAt'] ?? null) {
            $builder->andWhere('p.endAt ' . $criteria['endAt'][1] . " :endAt")
                ->setParameter('endAt', $criteria['endAt'][0]);
        }
        if ($criteria['presentedAt'] ?? null) {
            $builder->andWhere('p.presentedAt ' . $criteria['presentedAt'][1] . " :presentedAt")
                ->setParameter('presentedAt', $criteria['presentedAt'][0]);
        }
        if (!empty($orderBy)) {
            $builder->orderBy('p.' . $orderBy[0], $orderBy[1]);
        }
        return $builder
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Project[] Returns an array of Project objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Project
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
