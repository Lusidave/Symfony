<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Form\ProgramSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{

    /**
     * Show all rows from Program's entity
     *
     * @Route("/wild/", name="wild_index")
     * @param Request $request
     * @return Response A response instance
     */
    public function index(Request $request) :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        $form = $this->createForm(
            ProgramSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            return $this->showByCategory($data['searchField']);
        }

        if (!$programs) {
            throw $this->createNotFoundException('No program found in program\'s table.');
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
        ]);
    }
    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug}", defaults={"slug" = null}, name="wild_show")
     * @return Response
     */
    public function showByProgram(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(
                ['program' => $program]
            );

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
            'season' => $season
        ]);
    }
    /**
     * @param string categoryName
     * @Route("/category/{categoryName}", name="show_category")
     * @return Response
     */
    public function showByCategory(string $categoryName) :Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC']
            );
        if (!$programs) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        if (!$category) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        return $this->render('wild/category.html.twig', [
            'programs' => $programs ,
            'category' => $category,
        ]);
    }
    /**
     * @param integer $id
     * @Route("program/season/{id}", name="season")
     * @return Response
     */
    public function showBySeason(int $id) :Response
    {
        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->find($id);
        $program = $season->getProgram();
        $episode = $season->getEpisodes();


    return $this->render('wild/season.html.twig', [
        'season' => $season, 'program' => $program, 'episode' => $episode
        ]);
    }

    /**
     * @Route("/episode/{id}", name="episode")
     * @param Episode $episode
     * @return Response
     */
    public function showEpisode(Episode $episode) :Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'season' => $season, 'program' => $program, 'episode' => $episode
        ]);
    }
}
