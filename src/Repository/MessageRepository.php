<?php

namespace App\Repository;

use App\Common\Response\PaginatedResponse;
use App\Entity\Message;
use App\Message\MessageStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly ManagerRegistry   $registry,
        private readonly PaginatedResponse $paginatedResponse)
    {
        parent::__construct($registry, Message::class);
    }

    public function findPaginated(?MessageStatus $status, int $page = 1,  int $limit = 10): PaginatedResponse
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('s');
        // Get the total count
        $totalCountQ = $this->createQueryBuilder('s')
            ->select('COUNT(s.id)');

        if ($status) {
            $qb->andWhere('s.status = :status')->setParameter('status', $status);
            $totalCountQ->andWhere('s.status = :status')->setParameter('status', $status);
        }

        $qb->setMaxResults($limit)->setFirstResult($offset);

        $this->paginatedResponse->setTotal($totalCountQ->getQuery()->getSingleScalarResult());
        $this->paginatedResponse->setData($qb->getQuery()->getResult());

        return $this->paginatedResponse;
    }
}
