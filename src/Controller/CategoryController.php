<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route ("category", name="category_index")
     */
    public function index() {

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render("Category/index.html.twig",
            [
                "categories"=>$categories
            ]
        );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("category/add", name="category_add")
     */

    public function add(Request $request): Response {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ){
            $category = $form->getData();
            $newCategory = $this->getDoctrine()->getManager();
            $newCategory->persist($category);
            $newCategory->flush();
        }

        return $this->render("Category/add.html.twig", ["form"=>$form->createView()]);
    }
}