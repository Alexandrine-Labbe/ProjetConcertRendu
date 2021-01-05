<?php

namespace App\Repository;

use App\Entity\Band;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Band|null find($id, $lockMode = null, $lockVersion = null)
 * @method Band|null findOneBy(array $criteria, array $orderBy = null)
 * @method Band[]    findAll()
 * @method Band[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Band::class);
    }

    // /**
    //  * @return Band[] Returns an array of Band objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /**
     * @param $id
     * @return Band|null
     * @throws NonUniqueResultException
     */
    public function findOneWithFutureShowsOnly($id): ?Band
    {
        return $this->createQueryBuilder('band')
            ->join('band.shows', 'concert')
            ->Where('band.id = :id')
            ->andWhere('concert.date > :now')
            ->setParameter('id', $id)
            ->setParameter('now', new DateTime())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
