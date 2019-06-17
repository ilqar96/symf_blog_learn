<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $categorys = $categoryRepository->findAll();

        $form = $this->createForm(CategoryFormType::class);



        return $this->render('category/index.html.twig', [
            'categorys' => $categorys,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/category/add", name="category_add")
     */
    public function new(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($category);
             $entityManager->flush();

            return $this->redirectToRoute('category');
        }


        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/category/edit/{slug}", name="category_edit")
     */
    public function edit(Request $request,$slug ,CategoryRepository $repo)
    {

        $category = $repo->findOneBy(['slug'=>$slug]);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);


        if ($form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new Response('success');
        }

        return new Response('error');
    }

    /**
     * @Route("/category/delete/{slug}" ,name="category_delete")
     */
    public function deleteAction($slug,CategoryRepository $categoryRepository)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $category =  $categoryRepository->findOneBy(['slug'=>$slug]);

        if (!$category) {
            throw $this->createNotFoundException('No category found for slug '.$slug);
        }

        $em->remove($category);
        $em->flush();


        return $this->redirectToRoute('category');
    }


    /**
     * @Route("/category/find/{slug}" ,name="category_find" )
     */
    public function findOneBySlug($slug,CategoryRepository $categoryRepository){

        $category =  $categoryRepository->findOneBy(['slug'=>$slug]);

        return new JsonResponse($category);
    }





}
