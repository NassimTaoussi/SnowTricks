<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trick>
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function countAllTricks()
    {
        return $this->createQueryBuilder('t')
        ->select('COUNT(t.id)')
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function getFirstTricks($tricksForStarting)
    {
        $query = $this->createQueryBuilder('t')
            ->orderBy('t.id')
            ->setFirstResult(0)
            ->setMaxResults($tricksForStarting)
        ;

        return $query->getQuery()->getResult();
    }

    public function getMoreTricks($tricksAlreadyLoaded, $tricksPerLoading)
    {
        $query = $this->createQueryBuilder('t')
            ->orderBy('t.id')
            ->setFirstResult($tricksAlreadyLoaded)
            ->setMaxResults($tricksPerLoading)
        ;

        return $query->getQuery()->getResult();
    }

    public function updateTrick(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function deleteTrick($id)
    {
        $this->createQueryBuilder('t')
        ->delete()
        ->where('t.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getResult();
    }

    public function save(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
