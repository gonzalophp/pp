<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class PensionController extends AbstractController
{

    #[Route('/pension', name: 'pension_index')]
    public function index(): Response
    {
        return new Response('aaaaaaaaaaaaa');
    }
}
