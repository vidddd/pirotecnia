<?php

namespace PM\LibroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PMLibroBundle:Default:index.html.twig');
    }
}
