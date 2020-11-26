<?php

namespace App\Repository;

use App\Entity\Quack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quack[]    findAll()
 * @method Quack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quack::class);
    }

    /**
     * @return Quack[] Returns an array of Quack objects
     */
    public function findParentQuacks()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.parent IS NULL')
            ->orderBy('p.created_at', 'DESC');
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllDesc()
    {
        return $this->findBy(array(), array('created_at' => 'DESC'));
    }

    public function search($duckName) {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.Auteur', 'Auteur')
            ->leftJoin('q.tags', 'tag')
            ->andWhere('Auteur.duckname LIKE :searchedName OR tag.name LIKE :searchedName')
            ->setParameter('searchedName', '%'.$duckName.'%')
            ->orderBy('q.created_at', 'DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @return Quack[] Returns an array of Quack objects
     */

    public function findByTag($tag)
    {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.tags', 'tag')
            ->andWhere('tag.name = :searchedTag')
            ->setParameter('searchedTag', $tag)
            ->orderBy('q.created_at', 'DESC')
            ->getQuery()
            ->execute();

        // to get just one result:
        // $product = $query->setMaxResults(1)->getOneOrNullResult();
    }
    // /**
    //  * @return Quack[] Returns an array of Quack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quack
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
