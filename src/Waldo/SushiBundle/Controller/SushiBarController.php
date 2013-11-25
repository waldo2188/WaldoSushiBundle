<?php

namespace Waldo\SushiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/sushi-bar/")
 */
class SushiBarController extends Controller
{
    /**
     * Page d'accueil
     *
     * @Route("/", name="_sushi_list")
     * @Template()
     */
    public function sushiListAction()
    {
        $sushiList = $this->getDoctrine()->getRepository("WaldoSushiBundle:Sushi")->findAll();

        return array("sushiList" => $sushiList);
    }
}
