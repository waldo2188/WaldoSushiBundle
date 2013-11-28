<?php

/**
 * Class de test du service UtilisateurService
 *
 * @author valerian.girard
 */
class UtilisateurServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Ce test va permetre de savoir si un mot de page et un grain de sel sont bien
     * généré pour un nouvel utilisateur
     */
    public function testSouldGeneratePassword()
    {
        $utilisateur = new Waldo\SushiBundle\Entity\Utilisateur();
        $utilisateur->setUsername("arthur-dent")
                ->setEmail("a@a.fr")
                ->setPassword("aaaaa.1A");

        $em = $this->getMockBuilder("Doctrine\ORM\EntityManager")
                ->disableOriginalConstructor()
                ->getMock();
        $em->expects($this->once())
                ->method('persist')
                ->with($this->identicalTo($utilisateur));


        $encodeur = $this->getMock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        $encodeur->expects($this->once())
                ->method("encodePassword")
                ->withAnyParameters()
                ->will($this->returnValue("NewHasedPassword"));

        $encodeurFactory = $this->getMockBuilder("Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface")
                ->disableOriginalConstructor()
                ->getMock();
        $encodeurFactory->expects($this->once())
                ->method("getEncoder")
                ->will($this->returnValue($encodeur))
        ;

        $utilisateurService = new Waldo\SushiBundle\Service\UtilisateurService($em, $encodeurFactory);
        $utilisateurAfterTraitement = $utilisateurService->manageUtilisateur($utilisateur);

        $this->assertEquals("NewHasedPassword", $utilisateurAfterTraitement->getPassword());
        $this->assertNotNull($utilisateurAfterTraitement->getSalt());
    }

    /**
     * Ce test va permetre de savoir si le service ne gnérère pas de mot de passe
     * pour un utilisateur existant
     */
    public function testSouldNotGeneratePassword()
    {
        $utilisateur = new Waldo\SushiBundle\Entity\Utilisateur();
        $utilisateur->setId(42)
                ->setUsername("arthur-dent")
                ->setEmail("a@a.fr")
                ->setPassword("aaaaa.1A");

        $em = $this->getMockBuilder("Doctrine\ORM\EntityManager")
                ->disableOriginalConstructor()
                ->getMock();
        $em->expects($this->once())
                ->method('persist')
                ->with($this->identicalTo($utilisateur));


        $encodeur = $this->getMock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        $encodeur->expects($this->never())
                ->method("encodePassword");

        $encodeurFactory = $this->getMockBuilder("Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface")
                ->disableOriginalConstructor()
                ->getMock();
        $encodeurFactory->expects($this->never())
                ->method("getEncoder");

        $utilisateurService = new Waldo\SushiBundle\Service\UtilisateurService($em, $encodeurFactory);
        $utilisateurAfterTraitement = $utilisateurService->manageUtilisateur($utilisateur);

        $this->assertEquals("aaaaa.1A", $utilisateurAfterTraitement->getPassword());
        $this->assertNull($utilisateurAfterTraitement->getSalt());
    }

}
