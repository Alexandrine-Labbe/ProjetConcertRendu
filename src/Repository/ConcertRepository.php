<?php

namespace App\Repository;

use App\Entity\Concert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Concert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concert[]    findAll()
 * @method Concert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Concert::class);
    }

    /**
     * @return Concert[] Returns an array of Concert objects
     */
    public function findByAfterDate($date)
    {


        return $this->createQueryBuilder('concert')
            ->andWhere('concert.date >= :date')
            ->setParameter('date', $date)
            ->orderBy('concert.date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Concert[] Returns an array of Concert objects
     */
    public function findByBandAfterDate($band_id, $date)
    {

        return $this->createQueryBuilder('concert')
            ->join('concert.band', 'band')
            ->where('band.id = :id')
            ->andWhere('concert.date >= :date')
            ->setParameter('date', $date)
            ->setParameter('id', $band_id)
            ->orderBy('concert.date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $year
     * @return Concert[] Returns an array of Concert objects
     */
    public function findByYear($year)
    {
        $begin_year = "01/01/" . $year;
        $end_year = "31/12/" . $year;

        return $this->createQueryBuilder('concert')
            ->andWhere('concert.date >= :begin')
            ->andWhere('concert.date <= :end')
            ->setParameter('begin', $begin_year)
            ->setParameter('end', $end_year)
            ->orderBy('concert.date', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Concert
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
