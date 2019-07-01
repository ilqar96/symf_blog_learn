<?php

namespace App\Repository;


use App\Entity\Post;
use App\Entity\PostView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method PostView|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostView|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostView[]    findAll()
 * @method PostView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostViewRepository extends ServiceEntityRepository
{

    /** @var EntityManagerInterface */
    protected $em;


    public function __construct(
        RegistryInterface $registry,
        EntityManagerInterface $em
    )
    {
        parent::__construct($registry, PostView::class);
        $this->em  = $em;
    }


    public function addViewed(Post $post,$user,$clientIp){

        if($user != null){
            $postViewedResult = $this->createQueryBuilder('v')
                ->andWhere('v.post = :post')
                ->andWhere('v.user = :user')
                ->setParameter('post', $post)
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
        }else{
            $postViewedResult = $this->createQueryBuilder('v')
                ->andWhere('v.post = :post')
                ->andWhere('v.userIp = :userIp ')
                ->setParameter('post', $post)
                ->setParameter('userIp', $clientIp)
                ->getQuery()
                ->getResult();
        }

            if($postViewedResult == null){
                $postView = new PostView();

                $postView->setPost($post);
                $postView->setUser($user);
                $postView->setUserIp($clientIp);
                $this->em->persist($postView);
                $this->em->flush();
            }
            return false;
    }

//
//    /**
//     * @return boolean
//     */

//    public function addViewed(Post $post,$user,$clientIp){
//        $postViewedResult = $this->createQueryBuilder('v')
//            ->andWhere('v.post = :post')
//            ->setParameter('post', $post);
//
//        if($user != null){
//            $postViewedResult->andWhere('v.user = :user')
//                ->setParameter('user', $user);
//        }else{
//            $postViewedResult->andWhere('v.userIp = :userIp')
//                ->setParameter('userIp', $clientIp);
//        }
//
//        $postViewedResult->getQuery()
//            ->getResult();
//
//        dd($postViewedResult);
//
//        if($postViewedResult == null){
//            $postView = new PostView();
//
//            $postView->setPost($post);
//            $postView->setUser($user);
//            $postView->setUserIp($clientIp);
//            $this->em->persist($postView);
//            $this->em->flush();
//
//            return true;
//        }
//        return false;
//
//    }





    // /**
    //  * @return PostView[] Returns an array of PostView objects
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
    public function findOneBySomeField($value): ?PostView
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
