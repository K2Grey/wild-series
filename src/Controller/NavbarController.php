<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavbarController extends AbstractController
{
    /**
     * @return Response
     */
    public function displayNavbar(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy([], ['name' => 'ASC']);

        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findBy([], ['name' => 'ASC']);

        return $this->render('_navbar.html.twig', [
            'categories' => $categories,
            'actors' => $actors,
        ]);
    }
}
