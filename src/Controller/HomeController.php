<?php

namespace App\Controller;

use App\Security\Voter\RoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    #[Route('/', name: 'app_home')]
    #[IsGranted(RoleVoter::USER)]
    public function index(Request $request): Response
    {
        return $this->redirectToRoute('app_project_index', [
            '_locale' => $request->getLocale() ?? $request->getDefaultLocale()
        ]);
    }
}
