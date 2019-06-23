<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param string|null $term
     * @param array|null $creadential
     */

    public function findSearchedPostsQueryBuilder(?string $term ,?array $creadential=null):QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');
        if($creadential!=null) {
            foreach ($creadential as $key => $value) {
                switch ($key){
                    case 'category' :
                        $qb->andWhere('p.category = :cat ')
                            ->setParameter('cat',$value);
                        break;
                    case 'author' :
                        $qb->andWhere('p.author = :aut ')
                            ->setParameter('aut',$value);
                        break;
                    case 'tags' :
                        $qb ->andWhere(':tag  MEMBER OF p.tags')
                            ->setParameter('tag', $value);
                        break;
                }
            }
        }
        if($term){
            $qb->andWhere('p.title LIKE :term or p.content LIKE :term ')
                ->setParameter('term', '%'.$term.'%');
        }
        $qb ->leftJoin('p.tags', 't')
            ->addSelect('t')
            ->innerJoin('p.author','a')
            ->addSelect('a');
        return $qb->orderBy('p.createdAt', 'DESC');
    }


    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
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
    public function findOneBySomeField($value): ?Post
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
