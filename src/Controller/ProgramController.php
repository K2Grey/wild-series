<?php


namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * The controller for the program add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/{id<^[0-9]+$>}", methods={"GET"}, name="show")
     * @param Program $program
     * @return Response
     */
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program->getId() . ' found in program\'s table.'
            );
        }

        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route("/{programId<^[0-9]+$>}/seasons/{seasonId<^[0-9]+$>}", methods={"GET"}, name="season_show")
     * @ParamConverter("program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", options={"mapping": {"seasonId": "id"}})
     * @param Program $program
     * @param Season $season
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program->getId() . ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $season->getId() . ' found in season\'s table.'
            );
        }

        $episodes = $season->getEpisodes();

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    /**
     * @Route("/{programId<^[0-9]+$>}/seasons/{seasonId<^[0-9]+$>}/episodes/{episodeId<^[0-9]+$>}",
     *     methods={"GET"}, name="episode_show")
     * @ParamConverter("program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", options={"mapping": {"episodeId": "id"}})
     * @param Program $program
     * @param Season $season
     * @param Episode $episode
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program->getId() . ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $season->getId() . ' found in season\'s table.'
            );
        }

        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode with id : ' . $episode->getId() . ' found in episode\'s table.'
            );
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
