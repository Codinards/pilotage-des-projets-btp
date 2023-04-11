<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Security\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
#[IsGranted(RoleVoter::ADMIN)]
class CompanyController extends BaseController
{
    #[Route('/{_locale}', name: 'app_company_index', methods: ['GET'], requirements: ["_locale" => "fr|en|es"])]
    public function index(Request $request, CompanyRepository $companyRepository): Response
    {
        $toPDF = $request->query->get('pdf');
        $templatePath = 'company/' . ($toPDF ? 'pdf' : 'index') . '.html.twig';

        return $this->render($templatePath, [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    #[Route('/{_locale}/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompanyRepository $companyRepository): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->add($company, true);
            $this->addFlash('success', 'the entity has been successfully created.');

            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/{_locale}/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->add($company, true);
            $this->addFlash('success', 'the entity has been successfully updated.');

            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        if ($company->getProjects()->isEmpty()) {
            if ($this->isCsrfTokenValid('delete' . $company->getId(), $request->request->get('_token'))) {
                $companyRepository->remove($company, true);
                $this->addFlash('success', 'the entity has been successfully removed.');
            }
        } else {
            $this->addFlash('error', 'Des projets de cette compagnie sont encore conservés dans la bases de données. La compagnie ne peut donc pas encore être supprimée.');
        }
        return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
    }
}
