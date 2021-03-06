<?php

namespace App\Repository;

use App\Entity\Pupil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pupil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pupil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pupil[]    findAll()
 * @method Pupil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PupilRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pupil::class);
    }

    // /**
    //  * @return Pupil[] Returns an array of Pupil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pupil
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
