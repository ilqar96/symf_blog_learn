<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\RegistrationFormType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $categorys =  $categoryRepository->findAll();
        $form = $this->createForm(CategoryFormType::class);

        return $this->render('category/index.html.twig', [
            'form'=>$form->createView(),
            'categorys' => $categorys,
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('category_add');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/category/edit/{slug}", name="category_edit")
     */
    public function edit($slug,CategoryRepository $categoryRepository ,Request $request)
    {

        $category = $categoryRepository->findOneBy(['slug'=>$slug]);
        $form = $this->createForm(CategoryFormType::class, $category);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
                dd('test');

            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);
                $entityManager->flush();

                return new JsonResponse(['reslut' => 'success']);
            }

        }

        return new JsonResponse(['reslut'=>'error']);

    }


    /**
     * @Route("/category/delete/{slug}", name="category_delete")
     */
    public function delete( $slug,CategoryRepository $categoryRepository)
    {
        $category =  $categoryRepository->findOneBy(['slug'=>$slug]);

        $em = $this->getDoctrine()->getEntityManager();

        if (!$category) {
            throw $this->createNotFoundException('No category found for slug - '.$slug);
        }

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('category');
    }


    /**
     * @Route("/category/find/{slug}", name="category_delete")
     */
    public function findBySlug( $slug,CategoryRepository $categoryRepository)
    {
        $category =  $categoryRepository->findOneBy(['slug'=>$slug]);

//        dd($category);

        if (!$category) {
            throw $this->createNotFoundException('No category found for slug - '.$slug);
        }

        return $this->json($category);

    }







}
