<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostLike[]    findAll()
 * @method PostLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostLikeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostLike::class);
    }



    /**
     * @return boolean
     */
    public function findPostIsLikedMe(Post $post,$author)
    {
        if($author){
            $postLikedByMeResult = $this->createQueryBuilder('p')
                ->andWhere('p.author = :author')
                ->andWhere('p.post = :post')
                ->andWhere('p.liked = true')
                ->setParameter('author', $author)
                ->setParameter('post', $post)
                ->getQuery()
                ->getOneOrNullResult()
                ;

            return $postLikedByMeResult ? true:false;

        }else{
            return false;
        }

    }









    // /**
    //  * @return PostLike[] Returns an array of PostLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostLike
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
