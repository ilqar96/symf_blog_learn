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
     * @return Post[]
     */

    public function findSearchedPosts(?string $term ,?array $creadential=null){
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
                        $qb->andWhere('p.tags = :tag')
                            ->setParameter('tag',$value);
                        dd($qb->getQuery());
                        break;
                }
            }
        }
        if($term){
            $qb->andWhere('p.title LIKE :term or p.content LIKE :term ')
                ->setParameter('term', '%'.$term.'%');
        }
        $qb ->innerJoin('p.author','a')
            ->addSelect('a');
        return $qb->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string|null $term
     * @param Tag $tag
     * @return Post[]
     */

    public function findSearchedPostsForTags(?string $term ,Tag $tag){

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
                FROM App\Entity\Post p
                INNER JOIN tag_post ON p.id = tag_post.post_id
                WHERE (p.title LIKE :term or p.content LIKE :term) and  tag_post.tag_id = :tag
                ORDER BY p.createdAt DESC'
        )->setParameter('tag', $tag)->setParameter('term',$term);

        // returns an array of Product objects
        return $query->execute();
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
