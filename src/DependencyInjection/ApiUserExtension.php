<?php

namespace PhpSolution\ApiUserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * ApiUserExtension
 */
class ApiUserExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('api_user.user_entity_class', $config['user_entity_class']);
        $container->setParameter('api_user.user_enabled_by_default', $config['user_enabled_by_default']);
        if ($config['send_email_confirmation']) {
            $loader->load('event/registration_confirmation.yml');
        }
        if ($config['send_forgot_password']) {
            $loader->load('event/forgot_password.yml');
        }
    }
}