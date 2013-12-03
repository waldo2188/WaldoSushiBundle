<?php

namespace Waldo\SushiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/utilisateur")
 */
class UtilisateurController extends Controller
{

    /**
     * Affiche la liste des utilisateurs
     *
     * @Route("/show/", name="_utilisateur_show")
     * @Template()
     */
    public function showUtilisateursAction() {
        
        return array("utilisateurList" => $this->getDoctrine()->getRepository("WaldoSushiBundle:Utilisateur")->findAll());

    }

    
    /**
     * Affiche le formulaire de saisie d'un utilisateur
     *
     * @Route("/edit/{idUtilisateur}", name="_utilisateur_edit", defaults={"idUtilisateur"=null})
     * @Template()
     */
    public function editUtilisateurAction(Request $request, $idUtilisateur)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = null;

        if($idUtilisateur != null) {
            $utilisateur = $em->getRepository("WaldoSushiBundle:Utilisateur")->findOneById($idUtilisateur);
            if($utilisateur === null) {
                throw new NotFoundHttpException($this->get("translator")->trans('Cet utilisateur est inconnu'));
            }
        }

        // "utilisateur" est le nom du type de formulaire Waldo\SushiBundle\Form\Type\UtilisateurType
        // Du faite que le type de formulaire est définit comme un service, on peut l'appeler par son nom
        $form = $this->createForm("utilisateur", $utilisateur);

        if($request->isMethod("POST")) {
            $form->bind($request);

            if($form->isValid()) {

                $utilisateur = $this->get("waldo_utilisateur.service")->manageUtilisateur($form->getData());

                return $this->redirect($this->generateUrl("_utilisateur_edit", array("idUtilisateur" => $utilisateur->getId())));
            }

        }

        return array(
            "form" => $form->createView(),
            "utilisateur" => $utilisateur
                );
    }

    /**
     * Affiche le formulaire de changement d'un mot de passe utilisateur
     * Ce contröleur est presque identique à "editUtilisateurAction" et pourtant
     * il permet de ne modifier qu'une partie des informations de l'entité.
     *
     * @Route("/edit-password/{idUtilisateur}", name="_utilisateur_password_edit")
     * @Template()
     */
    public function editMotDePasseAction(Request $request, $idUtilisateur)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = null;

        if($idUtilisateur != null) {
            $utilisateur = $em->getRepository("WaldoSushiBundle:Utilisateur")->findOneById($idUtilisateur);
            if($utilisateur === null) {
                throw new NotFoundHttpException($this->get("translator")->trans('Cet utilisateur est inconnu'));
            }
        }

        // "utilisateur_password" est le nom du type de formulaire Waldo\SushiBundle\Form\Type\MotDePasseType
        // Du faite que le type de formulaire est définit comme un service, on peut l'appeler par son nom
        // On passe ici en troisième paramètre un tableau d'option.
        // Il nous permet ici de désactiver l'inhéritance de ce type de formulaire
        $form = $this->createForm("utilisateur_password", $utilisateur, array('inherit_data' => false));

        if($request->isMethod("POST")) {
            $form->bind($request);

            if($form->isValid()) {

                $utilisateur = $this->get("waldo_utilisateur.service")->manageUtilisateur($form->getData());

                return $this->redirect($this->generateUrl("_utilisateur_password_edit", array("idUtilisateur" => $utilisateur->getId())));
            }

        }

        return array(
            "form" => $form->createView(),
            "utilisateur" => $utilisateur
                );
    }
}
