<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route(path: '/login', methods: ["GET"])]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }

    #[Route(path: '/login', methods: ["POST"])]
    public function doLogin(): Response
    {
        throw new \LogicException("Should never rich this line");
    }

    #[Route(path: '/app', methods: ["GET"])]
    public function app(): Response
    {
        return $this->render('app.html.twig');
    }
}