<?php

namespace App\Controller;

use App\Exception\ThrowableException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/error/{_locale}', name: 'app_error')]
    public function index(Request $request): Response
    {
        $exception = $request->attributes->get('exception');
        if ((!$exception instanceof \Exception) || (!property_exists($exception, 'statusCode'))) {
            $exception = new ThrowableException($exception);
        }

        return $this->render('error/index.html.twig', [
            'exception' => $exception,
        ]);
    }
}
