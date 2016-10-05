<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Kernel
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Kernel constructor.
     * @param string $environment
     *
     * @throws \Exception
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
        $this->initContainer();
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return __DIR__;
    }


    /**
     * @return void
     *
     * @throws \Exception
     */
    protected function initContainer()
    {
        $rootDir = $this->getRootDir();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.dir', $rootDir);
        $container->setParameter('cache.dir', $rootDir . '/../var/cache');
        $container->setParameter('logs.dir', $rootDir . '/../var/logs');
        $this->loadContainerConfig($container);
        $this->container = $container;
    }

    /**
     * @param ContainerBuilder $container
     * @return void
     *
     * @throws \Exception
     */
    protected function loadContainerConfig(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator($this->getRootDir() . '/config'));
        $loader->load('config.yml');
    }
}