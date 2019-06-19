<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(PostRepository $prepo ,CategoryRepository $categoryRepository ,TagRepository $tagRepository)
    {
        $posts = $prepo->findBy([],['createdAt'=>'DESC']);
        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'categorys' => $categorys,
            'tags' => $tags,
        ]);
    }



    /**
     * @Route("/post-show/{slug}", name="post_show_home")
     */
    public function showPost(Post $post)
    {
        return $this->render('home/show.html.twig',[
            'post'=>$post,
        ]);
    }

    /**
     * @Route("/category/{slug}", name="post_categorys_home")
     */
    public function showCategorysPost(
        Category $category,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    )
    {
        $categoryPosts = $postRepository->findBy(['category'=>$category]);
        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);


        return $this->render('home/index.html.twig',[
            'posts'=>$categoryPosts,
            'categorys' => $categorys,
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/tag/{slug}", name="post_tags_home")
     */
    public function showTagsPost(
        Tag $tag,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    )
    {
        $tagPosts = $tag->getPosts();
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
        User $user,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
)
    {
        $authorsPosts = $postRepository->findBy(['author'=>$user]);
        $categorys = $categoryRepository->findBy(['isDeleted'=>false]);
        $tags = $tagRepository->findBy([],[],15);


        return $this->render('home/index.html.twig',[
            'posts'=>$authorsPosts,
            'categorys' => $categorys,
            'tags' => $tags,
        ]);
    }



}
