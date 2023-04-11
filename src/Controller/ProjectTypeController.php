<?php

namespace App\Controller;

use App\Entity\ProjectType;
use App\Form\ProjectTypeType;
use App\Repository\ProjectTypeRepository;
use App\Security\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project-type')]
#[IsGranted(RoleVoter::ADMIN)]
class ProjectTypeController extends BaseController
{
    #[Route('/{_locale}', name: 'app_project_type_index', methods: ['GET'], requirements: ["_locale" => "fr|en|es"])]
    public function index(Request $request, ProjectTypeRepository $projectTypeRepository): Response
    {
        $toPDF = $request->query->get('pdf');
        $templatePath = 'project_type/' . ($toPDF ? 'pdf' : 'index') . '.html.twig';

        return $this->render($templatePath, [
            'project_types' => $projectTypeRepository->findAll(),
        ]);
    }

    #[Route('/{_locale}/new', name: 'app_project_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectTypeRepository $projectTypeRepository): Response
    {
        $projectType = new ProjectType();
        $form = $this->createForm(ProjectTypeType::class, $projectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectTypeRepository->add($projectType, true);
            $this->addFlash('success', 'the entity has been successfully created.');

            return $this->redirectToRoute('app_project_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project_type/new.html.twig', [
            'project_type' => $projectType,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_project_type_show', methods: ['GET'])]
    public function show(ProjectType $projectType): Response
    {
        return $this->render('project_type/show.html.twig', [
            'project_type' => $projectType,
        ]);
    }

    #[Route('/{_locale}/{id}/edit', name: 'app_project_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectType $projectType, ProjectTypeRepository $projectTypeRepository): Response
    {
        $form = $this->createForm(ProjectTypeType::class, $projectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectTypeRepository->add($projectType, true);
            $this->addFlash('success', 'the entity has been successfully updated.');

            return $this->redirectToRoute('app_project_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project_type/edit.html.twig', [
            'project_type' => $projectType,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_project_type_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectType $projectType, ProjectTypeRepository $projectTypeRepository): Response
    {
        if ($projectType->getProjects()->isEmpty()) {
            if ($this->isCsrfTokenValid('delete' . $projectType->getId(), $request->request->get('_token'))) {
                $projectTypeRepository->remove($projectType, true);
                $this->addFlash('success', 'the entity has been successfully removed.');
            }
        } else {
            $this->addFlash('error', 'Des projets de ce type sont encore conservés dans la bases de données. Le type ne peut donc pas encore être supprimé.');
        }

        return $this->redirectToRoute('app_project_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
