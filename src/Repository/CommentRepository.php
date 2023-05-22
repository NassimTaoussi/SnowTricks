<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function countAllComments($trick)
    {
        return $this->createQueryBuilder('c')
        ->select('COUNT(c.id)')
        ->where('c.trick = :trickId ')
        ->setParameter('trickId', $trick->getId())
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function getFirstComments($commentsForStarting, $trick)
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->where('c.trick = :trickId ')
            ->setParameter('trickId', $trick->getId())
            ->setFirstResult(0)
            ->setMaxResults($commentsForStarting)
        ;

        return $query->getQuery()->getResult();
    }

    public function getMoreComments($commentsAlreadyLoaded, $commentsPerLoading, $trick)
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->where('c.trick = :trickId ')
            ->setParameter('trickId', $trick->getId())
            ->setFirstResult($commentsAlreadyLoaded)
            ->setMaxResults($commentsPerLoading)
        ;

        return $query->getQuery()->getResult();
    }

    public function save(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
