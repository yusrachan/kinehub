<?php

namespace App\Repository;

use App\Entity\CabinetValide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CabinetValide>
 *
 * @method CabinetValide|null find($id, $lockMode = null, $lockVersion = null)
 * @method CabinetValide|null findOneBy(array $criteria, array $orderBy = null)
 * @method CabinetValide[]    findAll()
 * @method CabinetValide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CabinetValideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CabinetValide::class);
    }

//    /**
//     * @return CabinetValide[] Returns an array of CabinetValide objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CabinetValide
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
