<?php

namespace App\Controller;

use App\Entity\Key;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanelController extends AbstractController
{
    /**
     *  Define front-ends sidebar links here,
     *  where key is route name and value is the display name
     */
    const PANEL_NAVBAR_MENU = [
        'app_panel_home' => 'Home',
        'app_panel_gallery' => 'Gallery',
        'app_panel_keys' => 'Keys',
        'app_panel_stats' => 'Statistics'
    ];

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
    public function keys(ManagerRegistry $doctrine, Security $security): Response
    {
        $keys = $doctrine->getRepository(Key::class)->findBy(["generated_by" => $security->getUser()], ["id" => "DESC"]);

        return $this->render('panel/keys/keys.html.twig', [
            "keys" => $keys
        ]);
    }

    #[Route('/panel/stats', name: 'app_panel_stats')]
    public function stats(): Response
    {
        return $this->render('panel/stats/stats.html.twig', []);
    }
}
