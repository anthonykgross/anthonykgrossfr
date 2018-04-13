<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function getArticles($id = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.parent IS NOT NULL')
            ->orderBy('p.createdAt', 'DESC');

        if (!is_null($id)) {
            $qb->andWhere('p.id = :id')
                ->setParameter('id', $id);
        }

        return $qb->getQuery()
            ->getResult()
        ;
    }
}
