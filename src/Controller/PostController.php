<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tag;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(  EntityManagerInterface $entityManager, Request $request , TagRepository $tagRepository ): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $tags = $request->request->get('tags');
            if($tags != null){
                foreach ( $tags as $tag) {
                    $tagElement = $tagRepository->findOneBy(['name'=>strtolower($tag['name']) ] );
                   if(  $tagElement !=null ){
                       $tagElement->addPost($post);
                       $entityManager->persist($tagElement);
                   }else{
                       $tagElement = new Tag();
                       $tagElement->setName(strtolower($tag['name']));
                       $tagElement->addPost($post);
                       $entityManager->persist($tagElement);
                   }
                }
            }

            if($request->files->get('image')!=null){
                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file =  $form->get('image')->getData();
                $fileName =  md5(uniqid()).'.'.$file->guessExtension();
                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
                $post->setImage($fileName);
            }

            $post->setAuthor($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post , TagRepository $tagRepository, EntityManagerInterface $entityManager): Response
    {
        $oldImg = $post->getImage();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

//        dd($request->request->get('tags'));


        if ($form->isSubmitted() && $form->isValid()) {
            $tags = $request->request->get('tags');
            if($tags != null) {
                foreach ($tags as $tag) {
                    $tagElement = $tagRepository->findOneBy(['name' => strtolower($tag['name'])]);
                    if ($tagElement != null) {
                        $tagElement->addPost($post);
                        $entityManager->persist($tagElement);
                    } else {
                        $tagElement = new Tag();
                        $tagElement->setName(strtolower($tag['name']));
                        $tagElement->addPost($post);
                        $entityManager->persist($tagElement);
                    }
                }
            }

            $newImg = $request->files->get('image');
            if($newImg!=null){
                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file =  $form->get('image')->getData();
                $fileName =  md5(uniqid()).'.'.$file->guessExtension();
                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
                $post->setImage($fileName);
                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('image_directory').'/'.$oldImg);

            }else{
                $post->setImage($oldImg);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->getParameter('image_directory').'/'. $post->getImage());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index');
    }
}
