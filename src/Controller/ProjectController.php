<?php

namespace App\Controller;

use App\Dto\ProjectFilter;
use App\Entity\Project;
use App\Form\ProjectEditType;
use App\Form\ProjectFilterType;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Security\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project')]
class ProjectController extends BaseController
{

    #[Route('/{_locale}', name: 'app_project_index', methods: ['GET', 'POST'], requirements: ["_locale" => "fr|en|es"])]
    #[IsGranted(RoleVoter::USER)]
    public function index(Request $request, ProjectRepository $projectRepository): Response
    {
        $toPDF = $request->query->get('pdf');
        $templatePath = 'project/' . ($toPDF ? 'pdf' : 'index') . '.html.twig';
        $projectFilter = new ProjectFilter;
        $form = $this->createForm(ProjectFilterType::class, $projectFilter);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $projects = $projectRepository->findBySearch($projectFilter);
        } else {
            $projects = $projectRepository->findAll();
        }

        return $this->render($templatePath, [
            'projects' => $projects,
            'form' => $form->createView(),
            'pdf' => $toPDF ? true : false,
        ]);
    }

    #[Route('/{_locale}/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    #[IsGranted(RoleVoter::ADMIN)]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $projectRepository->add($project, true);
            $this->addFlash('success', 'le projet a bien été créé.');

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_project_show', methods: ['GET'])]
    #[IsGranted(RoleVoter::ADMIN)]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{_locale}/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    #[IsGranted(RoleVoter::ADMIN)]
    public function edit(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        $form = $this->createForm(ProjectEditType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($project->getEndAt() !== null and $project->isIsActive() === false) {
                $project->setIsActive(true);
            }
            $projectRepository->add($project, true);
            $this->addFlash('success', 'le projet a bien été mis à jour.');

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_project_delete', methods: ['POST'])]
    #[IsGranted(RoleVoter::ADMIN)]
    public function delete(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $projectRepository->remove($project, true);
            $this->addFlash('success', 'le projet a bien été supprimé.');
        }

        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }
}
