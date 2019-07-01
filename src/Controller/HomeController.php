<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\PostView;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use App\Repository\PostViewRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(
        PaginatorInterface $paginator,
        PostRepository $postRepository ,
        CategoryRepository $categoryRepository ,
        TagRepository $tagRepository,
        Request $request
    )
    {
        $q = $request->query->get('q');
        $queryBuilder = $postRepository->findSearchedPostsQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            7/*limit per page*/
        );


        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy(['isDeleted'=>false],[],15);

        return $this->render('home/index.html.twig', [
            'posts' => $pagination,
            'categorys' => $categorys,
            'tags' => $tags,
        ]);
    }



    /**
     * @Route("/category/{slug}", name="post_categorys_home")
     */
    public function showCategorysPost(
        PaginatorInterface $paginator,
        Category $category,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        Request $request
    )
    {
        $q = $request->query->get('q');
        $queryBuilder = $postRepository->findSearchedPostsQueryBuilder($q,['category'=>$category]);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            7/*limit per page*/
        );
        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);

        return $this->render('home/index.html.twig',[
            'posts'=>$pagination,
            'categorys' => $categorys,
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/tag/{slug}", name="post_tags_home")
     */
    public function showTagsPost(
        PaginatorInterface $paginator,
        Tag $tag,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        Request $request
    )
    {
        $q = $request->query->get('q');
        $queryBuilder = $postRepository->findSearchedPostsQueryBuilder($q,['tags'=>$tag]);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            7/*limit per page*/
        );
        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);


        return $this->render('home/index.html.twig',[
            'posts'=>$pagination,
            'categorys' => $categorys,
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/authors-post/{id}", name="post_authors_home")
     */
    public function showAuthorsPost(
        PaginatorInterface $paginator,
        User $user,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        Request $request
)
    {
        $q = $request->query->get('q');
        $queryBuilder = $postRepository->findSearchedPostsQueryBuilder($q,['author'=>$user]);
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            7/*limit per page*/
        );
        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);


        return $this->render('home/index.html.twig',[
            'posts'=>$pagination,
            'categorys' => $categorys,
            'tags' => $tags,
        ]);
    }


    /**
     * @Route("/post-show/{slug}", name="post_show_home")
     */
    public function showPost(
        Post $post,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        PostLikeRepository $postLikeRepository,
        PostViewRepository $postViewRepository,
        Request $request,
        EntityManagerInterface $em
        )
    {
        $postViewRepository->addViewed($post,$this->getUser(),$request->getClientIp());

        $postLikeCount = $postLikeRepository->findBy(['post'=>$post,'liked'=>true,]);
        $postViewCount = $postViewRepository->findBy(['post'=>$post,]);
        $postLikedByMe = $postLikeRepository->findPostIsLikedMe($post,$this->getUser());

        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);

        return $this->render('home/show.html.twig',[
            'post'=>$post,
            'categorys' => $categorys,
            'tags' => $tags,
            'likes'=>count($postLikeCount),
            'views'=>count($postViewCount),
            'liked'=>$postLikedByMe,
        ]);
    }


    /**
     * @Route("/post-liked/{slug}", name="post_liked")
     */
    public function postLiked(
        Post $post,
        EntityManagerInterface $em,
        PostLikeRepository $repository
    )
    {

        if(!$this->getUser()){
            return new JsonResponse(['result'=>'User not registered']);
        }

        $postLike = $repository->findOneBy([
            'author'=>$this->getUser(),
            'post'=> $post,
        ]);

        if($postLike){
            $postLike->getLiked()? $postLike->setLiked(false):$postLike->setLiked(true);
        }else{
            $postLike = new PostLike();

            $postLike->setPost($post);
            $postLike->setAuthor($this->getUser());
            $postLike->setLiked(true);
        }

        try{
            $em->persist($postLike);
            $em->flush();
            $postLikeCount = $repository->findBy([
                'post'=>$post,
                'liked'=>true,
            ]);
            return new JsonResponse([
                'result'=>'success',
                'likes'=>count($postLikeCount)
            ]);
        }catch (\Exception $e) {
            return new JsonResponse(['result' => 'error']);
        }

    }


    /**
     * @Route("/add-comment/{slug}" , name="add_comment")
     */
    public  function addComment(
        Post $post,
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository
    ){

        $content = trim($request->request->get('content'));
        $subid = (int)$request->request->get('subid');
        $subid = $subid == 0 ?null:$subid;

        if($content != ''){
            $user = $this->getUser();
            try{
                $comment = new Comment();

                $comment->setPost($post)
                    ->setContent($content)
                    ->setParent($subid)
                    ->setAuthor($this->getUser());
                $em->persist($comment);
                $em->flush();
                return new JsonResponse(['result'=>'success','author'=>$user?$user->getEmail():'Guest','content'=>$content]);

            }catch (\Exception $e){
                return new JsonResponse(['result'=>'error']);
            }
        }
        return new JsonResponse(['result'=>'empty']);

    }


}
