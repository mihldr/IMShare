<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    #[Route('/', name: 'app_panel_redirect')]
    public function redirectToPanel(): Response
    {
        return $this->redirectToRoute("app_panel");
    }

    #[Route('/panel', name: 'app_panel_home')]
    public function home(): Response
    {
        return $this->render('panel/home/home.html.twig', []);
    }

    #[Route('/panel/gallery', name: 'app_panel_gallery')]
    public function gallery(): Response
    {
        return $this->render('panel/gallery/gallery.html.twig', []);
    }

    #[Route('/panel/keys', name: 'app_panel_keys')]
    public function keys(): Response
    {
        return $this->render('panel/keys/keys.html.twig', []);
    }

    #[Route('/panel/stats', name: 'app_panel_stats')]
    public function stats(): Response
    {
        return $this->render('panel/stats/stats.html.twig', []);
    }
}
