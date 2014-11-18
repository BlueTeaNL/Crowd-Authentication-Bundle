<?php

namespace Bluetea\CrowdAuthenticationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BlueteaCrowdAuthenticationExtension extends Extension
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

        $configuration = new Configuration();
        $this->config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->setupAuthenticator();
    }

    /**
     * Setup the authenticator
     */
    protected function setupAuthenticator()
    {
        $this->container->setParameter('bluetea_crowd_authentication.base_url', $this->config['base_url']);
        $this->container->setParameter('bluetea_crowd_authentication.username', $this->config['application']);
        $this->container->setParameter('bluetea_crowd_authentication.password', $this->config['password']);

        $authenticatorDefinition = new Definition(
            'Bluetea\CrowdAuthenticationBundle\Api\Authenticator',
            array(new Reference('bluetea_crowd_authentication.guzzle'), new Reference('bluetea_crowd_authentication.crowd.user_mapper'))
        );

        $this->container->setDefinition('bluetea_crowd_authentication.authenticator', $authenticatorDefinition);
    }
}
