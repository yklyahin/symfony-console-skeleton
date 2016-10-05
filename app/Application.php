<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class Application extends ConsoleApplication
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * Application constructor.
     * @param Kernel $kernel
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;

        $container = $kernel->getContainer();

        parent::__construct(
            $container->hasParameter('application.name') ? $container->getParameter('application.name') : 'UNKNOWN',
            $container->hasParameter('application.version') ? $container->getParameter('application.version') : 'UNKNOWN'
        );

        $this->addConsoleCommands();
    }

    /**
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function addConsoleCommands()
    {
        /** @var array $commands */
        $commands = $this->kernel->getContainer()->getParameter('application.commands');

        foreach ($commands as $command) {
            $this->add(new $command());
        }
    }

    /**
     * @param Command $command
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
    {
        if ($command instanceof ContainerAwareInterface) {
            $command->setContainer($this->kernel->getContainer());
        }

        return parent::doRunCommand($command, $input, $output);
    }
}