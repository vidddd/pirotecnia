<?php

namespace PM\PirotecniaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PMPirotecniaBundle:Default:index.html.twig');
    }
}
