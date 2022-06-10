<?php

namespace App\Controller;

use App\Entity\Idea;
use App\Form\IdeaType;
use App\Repository\IdeaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Censurator;

/**
 * @Route("/idea")
 */
class IdeaController extends AbstractController
{

    private $censurator;

    public function __construct(Censurator $censurator) {
        $this->censurator = $censurator;
    }

    /**
     * @Route("/", name="app_idea_index", methods={"GET"})
     */
    public function index(IdeaRepository $ideaRepository): Response
    {
        return $this->render('idea/index.html.twig', [
            'ideas' => $ideaRepository->findAll(),
        ]);
    }

    /**
     * @isGranted("ROLE_USER")
     * @Route("/new", name="app_idea_new", methods={"GET", "POST"})
     */
    public function new(Request $request, IdeaRepository $ideaRepository): Response
    {
        $idea = new Idea();
        $form = $this->createForm(IdeaType::class, $idea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $idea->setContent($this->censurator->purify($idea->getContent()));
            $ideaRepository->add($idea, true);

            return $this->redirectToRoute('app_idea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('idea/new.html.twig', [
            'idea' => $idea,
            'form' => $form,
            'title' => 'Nouvelle idée'
        ]);
    }

    /**
     * @Route("/{id}", name="app_idea_show", methods={"GET"})
     */
    public function show(Idea $idea): Response
    {
        return $this->render('idea/show.html.twig', [
            'idea' => $idea,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_idea_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Idea $idea, IdeaRepository $ideaRepository): Response
    {
        $form = $this->createForm(IdeaType::class, $idea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ideaRepository->add($idea, true);

            return $this->redirectToRoute('app_idea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('idea/edit.html.twig', [
            'idea' => $idea,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_idea_delete", methods={"POST"})
     */
    public function delete(Request $request, Idea $idea, IdeaRepository $ideaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$idea->getId(), $request->request->get('_token'))) {
            $ideaRepository->remove($idea, true);
        }

        return $this->redirectToRoute('app_idea_index', [], Response::HTTP_SEE_OTHER);
    }
}
