<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * 
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{page<[a-z0-9-]+>?article-sans-nom}", methods={"GET"}, name="blog")
     */
    public function show($page)
    {
        return $this->render('blog/index.html.twig', ['page' => str_replace("-", " ", ucwords($page))]);

    }
}
