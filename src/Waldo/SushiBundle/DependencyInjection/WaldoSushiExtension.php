<?php

namespace Waldo\SushiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WaldoSushiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Ici on charge aussi un autre fichier de service 'form.xml'. Tout aurrais
        // pu être mis dans 'services.xml', cependant cela permet de bien séparer
        // ce qui est service métier et service lié au type de formulaire.
        $loader->load('form.xml');

        // Permet de charger une configuration particulière pour l'environnement de test depuis un fichier YAML
        // Cela évite si le bundle doit être utilisé ailleurs de devoir réécrir la configuration
        // dans le fichier app/config_test.yml
        if($container->getParameter("kernel.environment") === 'test') {
            $loaderYml = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loaderYml->load("config_test.yml");
        }

    }
}
