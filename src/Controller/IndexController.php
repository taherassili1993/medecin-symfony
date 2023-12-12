<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class IndexController extends AbstractController
{
    /**
     *
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        return $this->render('index.html.twig', ['name' => 'hello world !!!']);
    }

    /**
     * @Route("/api/change-language", name="app_language_api", methods={"POST"})
     */
    public function register_api(Request $request): Response
    {
        $language = $request->get('language', 'fr');
        $this->get('session')->set('language', $language);
        $response = new Response( json_encode( ['success' => true] ) );
        $response->headers->set( 'Content-Type', 'application/json' );

        return $response;
    }

}
