<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecuredController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route(path: '/login', methods: ["GET"])]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }
}