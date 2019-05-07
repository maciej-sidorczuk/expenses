<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Place::class);
    }

    public function searchByString($query_string, $value = null): array {
      $entityManager = $this->getEntityManager();
      $query = $entityManager->createQuery($query_string);
      if(isset($value)) {
        $query->setParameter('name', $value);
      }
      return $query->execute();
    }

    public function deleteAll($ids) {
      $entityManager = $this->getEntityManager();
      $stringQuery = 'DELETE FROM App\Entity\Place p WHERE p.id IN (:ids)';
      $query = $entityManager->createQuery($stringQuery)->setParameter('ids', $ids);
      return $query->execute();
    }

//    /**
//     * @return Place[] Returns an array of Place objects
//     */
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
    public function findOneBySomeField($value): ?Place
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
