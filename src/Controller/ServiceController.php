<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service/{name}", name="service_show")
     */
    public function showService($name): Response
    {
        return $this->render('service/showService.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/list', name: 'list_service')]
    public function listService()
    {
        return $this->render("service/list.html.twig");
    }
}
