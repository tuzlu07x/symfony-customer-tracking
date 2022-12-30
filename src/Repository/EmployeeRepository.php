<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Traits\SearchTrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    use SearchTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchFullName($fullName)
    {
        $terms = $this->splitName($fullName);
        $queryBuilder = $this->createQueryBuilder('e');

        foreach ($terms as $term) {
            return $queryBuilder
                ->andWhere('e.first_name LIKE :first_name')
                ->orWhere('e.last_name LIKE :last_name')
                ->setParameter('first_name', '%' . $term[0] . '%')
                ->setParameter('last_name', '%' . $term[1] . '%')
                ->getQuery()
                ->getResult();
        }
    }

    public function searchBetweenDates($startDate, $endDate, $leave = null)
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->leftJoin('e.leaves', 'l')
            ->andWhere('l.start_date >= :start_date')
            ->andWhere('l.end_date <= :end_date')
            ->setParameter('start_date', $startDate)
            ->setParameter('end_date', $endDate);

        if ($leave) {
            $queryBuilder->andWhere('l.id IS NOT NULL');
        } elseif ($leave === false) {
            $queryBuilder->andWhere('l.id IS NULL');
        }

        return $queryBuilder->getQuery()->getResult();
    }


    //    /**
    //     * @return Employee[] Returns an array of Employee objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Employee
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
