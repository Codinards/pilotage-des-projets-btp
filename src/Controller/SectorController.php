<?php

namespace App\Controller;

use App\Entity\Sector;
use App\Form\SectorType;
use App\Repository\SectorRepository;
use App\Security\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sector')]
#[IsGranted(RoleVoter::ADMIN)]
class SectorController extends BaseController
{
    #[Route('/{_locale}', name: 'app_sector_index', methods: ['GET'], requirements: ["_locale" => "fr|en|es"])]
    public function index(Request $request, SectorRepository $sectorRepository): Response
    {
        $toPDF = $request->query->get('pdf');
        $templatePath = 'sector/' . ($toPDF ? 'pdf' : 'index') . '.html.twig';

        return $this->render($templatePath, [
            'sectors' => $sectorRepository->findAll(),
        ]);
    }

    #[Route('/{_locale}/new', name: 'app_sector_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SectorRepository $sectorRepository): Response
    {
        $sector = new Sector();
        $form = $this->createForm(SectorType::class, $sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectorRepository->add($sector, true);
            $this->addFlash('success', 'the entity has been successfully created.');

            return $this->redirectToRoute('app_sector_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sector/new.html.twig', [
            'sector' => $sector,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_sector_show', methods: ['GET'])]
    public function show(Sector $sector): Response
    {
        return $this->render('sector/show.html.twig', [
            'sector' => $sector,
        ]);
    }

    #[Route('/{_locale}/{id}/edit', name: 'app_sector_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sector $sector, SectorRepository $sectorRepository): Response
    {
        $form = $this->createForm(SectorType::class, $sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectorRepository->add($sector, true);
            $this->addFlash('success', 'the entity has been successfully updated.');

            return $this->redirectToRoute('app_sector_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sector/edit.html.twig', [
            'sector' => $sector,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_sector_delete', methods: ['POST'])]
    public function delete(Request $request, Sector $sector, SectorRepository $sectorRepository): Response
    {
        if ($sector->getProjects()->isEmpty()) {
            if ($this->isCsrfTokenValid('delete' . $sector->getId(), $request->request->get('_token'))) {
                $sectorRepository->remove($sector, true);
                $this->addFlash('success', 'the entity has been successfully removed.');
            }
        } else {
            $this->addFlash('error', 'Des projets dans ce secteur sont encore conservés dans la bases de données. Le secteur ne peut donc pas encore être supprimé.');
        }

        return $this->redirectToRoute('app_sector_index', [], Response::HTTP_SEE_OTHER);
    }
}
