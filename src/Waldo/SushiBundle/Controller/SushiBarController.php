<?php

namespace Waldo\SushiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/sushi-bar")
 */
class SushiBarController extends Controller
{
    /**
     * Affiche la liste des sushi disponible
     *
     * @Route("/", name="_sushi_list")
     * @Template()
     */
    public function sushiListAction()
    {
        $sushiList = $this->getDoctrine()->getRepository("WaldoSushiBundle:Sushi")->findAll();

        return array("sushiList" => $sushiList);
    }

    
    /**
     * Affiche le formulaire de saisie d'un sushi
     *
     * @Route("/edit-sushi/{idSushi}", name="_sushi_edit", defaults={"idSushi"=null})
     * @Template()
     */
    public function editSushiAction(Request $request, $idSushi)
    {
        $em = $this->getDoctrine()->getManager();
        $sushi = null;

        if($idSushi != null) {
            $sushi = $em->getRepository("WaldoSushiBundle:Sushi")->findOneById($idSushi);
            if($sushi === null) {
                throw new NotFoundHttpException($this->get("translator")->trans('Ce sushi est inconnu'));
            }
        }

        // waldo_sushibundle_sushitype est le nom du type de formulaire Waldo\SushiBundle\Form\Type\SushiType
        // Du faite que le type de formulaire est définit comme un service, on peu l'appeler par son nom
        $form = $this->createForm("waldo_sushibundle_sushitype", $sushi);

        if($request->isMethod("POST")) {
            $form->bind($request);

            if($form->isValid()) {

                // Comme le waldo_sushibundle_sushitype est lié à la class \Waldo\SushiBundle\Entity\Sushi
                // la méthode getData retourne une instance de Sushi setter avec les données du formulaire
                /* @var $sushi \Waldo\SushiBundle\Entity\Sushi */
                $sushi = $form->getData();

                $em->persist($sushi);
                $em->flush();

                return $this->redirect($this->generateUrl("_sushi_edit", array("idSushi" => $sushi->getId())));
            }

        }

        return array("form" => $form->createView());
    }
}
