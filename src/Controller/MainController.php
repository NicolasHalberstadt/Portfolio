<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/apropos", name="about")
     */
    public function about()
    {
      
        return $this->render('about.html.twig');
    }

    /**
     * @Route("/cv", name="cv")
     */
    public function cv() {
        $cv = 'asset/cv.pdf';
        $response = new BinaryFileResponse($cv);

        return $response;
    }

    /**
     * @Route("/projets", name="projets")
     */
    public function projects() {
        return $this->render('projets/all.html.twig');
    }

    /**
     * @Route("/projet/adopteuneserie", name="projet_adopte")
     */
    public function adopteuneserie() {
        return $this->render('projets/adopte.html.twig');
    }

    /**
     * @Route("/projet/moodswingvintage", name="projet_moodswing")
     */
    public function moodswingvintage() {
        return $this->render('projets/moodswing.html.twig');
    }

        /**
     * @Route("/projet/portfolio", name="projet_portfolio")
     */
    public function portfolio() {
        return $this->render('projets/portfolio.html.twig');
    }
}
