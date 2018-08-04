<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function FindFeatured()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.images', 'images')
            ->leftJoin('p.likedBy', 'l')
            ->addSelect('p, images, l')
            ->where('p.featured = :value')
            ->setParameter('value', true)
            ->getQuery()
           ->getArrayResult()
            ;
    }
    public function FindLast($n)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.images', 'images')
            ->leftJoin('p.likedBy', 'l')
            ->addSelect('p, images, partial l.{id}')
            ->setMaxResults($n)
            ->getQuery()
           ->getArrayResult()
            ;
    }

    public function findFavorites($id){
        return $this->createQueryBuilder('p')
            ->leftJoin('p.images', 'images')
            ->leftJoin('p.likedBy', 'l')
            ->addSelect('p, images, partial l.{id}')
            ->andWhere('l.id = :user')
            ->setParameter('user', $id)
            ->getQuery()
            ->getArrayResult();
    }
}
