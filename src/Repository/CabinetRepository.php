<?php

namespace App\Repository;

use App\Entity\CabinetEnAttente;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CabinetEnAttente>
 *
 * @method CabinetEnAttente|null find($id, $lockMode = null, $lockVersion = null)
 * @method CabinetEnAttente|null findOneBy(array $criteria, array $orderBy = null)
 * @method CabinetEnAttente[]    findAll()
 * @method CabinetEnAttente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CabinetEnAttenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CabinetEnAttente::class);
    }

//    /**
//     * @return CabinetEnAttente[] Returns an array of CabinetEnAttente objects
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

//    public function findOneBySomeField($value): ?CabinetEnAttente
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
