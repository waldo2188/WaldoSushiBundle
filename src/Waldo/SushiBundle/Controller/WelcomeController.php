<?php

namespace Waldo\SushiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class WelcomeController extends Controller
{
    /**
     * Page d'accueil
     *
     * @Route("/", name="_welcome")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
