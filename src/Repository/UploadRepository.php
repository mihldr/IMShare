<?php

namespace App\Repository;

use App\Entity\Upload;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Upload>
 *
 * @method Upload|null find($id, $lockMode = null, $lockVersion = null)
 * @method Upload|null findOneBy(array $criteria, array $orderBy = null)
 * @method Upload[]    findAll()
 * @method Upload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UploadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Upload::class);
    }


    /**
     * @param UserInterface $user
     * @param int $page
     * @param int $limit
     * @return Upload[]
     */
    public function getUploadsByUserPaginated(UserInterface $user, int $page = 0, int $limit = 5) {
        $queryBuilder = $this->createQueryBuilder('up')
            ->innerJoin('up.uploaded_by', 'k')
            ->innerJoin('k.generated_by', 'us')
            ->where('us = :user')
            ->setParameter(':user', $user)
            ->orderBy('up.id', 'DESC')
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return $queryBuilder->getResult();
    }

//    /**
//     * @return Upload[] Returns an array of Upload objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Upload
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
