<?php

namespace App\Repository;

use App\Entity\PaymentMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaymentMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentMethod[]    findAll()
 * @method PaymentMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentMethodRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaymentMethod::class);
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
      $stringQuery = 'DELETE FROM App\Entity\PaymentMethod p WHERE p.id IN (:ids)';
      $query = $entityManager->createQuery($stringQuery)->setParameter('ids', $ids);
      return $query->execute();
    }

//    /**
//     * @return PaymentMethod[] Returns an array of PaymentMethod objects
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
    public function findOneBySomeField($value): ?PaymentMethod
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
