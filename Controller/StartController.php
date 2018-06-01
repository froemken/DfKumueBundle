<?php
namespace Sf\DfKumue\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StartController extends Controller
{
    /**
     * @Route("/kumue", name="kumue")
     * @return Response
     */
    public function show()
    {
        return $this->render('Start/Show.html.twig', []);
    }
}
