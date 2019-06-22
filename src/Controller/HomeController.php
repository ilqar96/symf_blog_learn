<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $tagPosts = $tag->getPosts();
//        $tagPosts = $postRepository->findSearchedPostsForTags($q,$tag);
        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);


        return $this->render('home/index.html.twig',[
            'posts'=>$tagPosts,
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
        TagRepository $tagRepository
)
    {

        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);
        return $this->render('home/show.html.twig',[
            'post'=>$post,
            'categorys' => $categorys,
            'tags' => $tags,
        ]);
    }


}
