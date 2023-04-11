<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    public function render(string $view, array $parameters = [], Response|null $response = null): Response
    {
        $parameters['error'] = $parameters['error'] ?? null;

        return parent::render($view, $parameters, $response);
    }
}
