<?php

namespace PhpSolution\ApiUserBundle\Command;

use PhpSolution\ApiUserBundle\Dto\RegistrationDto;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CreateUserCommand
 */
class CreateUserCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('api_user:user:create')
            ->setDescription('Create a user')
            ->addArgument('email', InputArgument::REQUIRED, 'The email')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
            ->addOption('role', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The roles');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $dto = (new RegistrationDto())
            ->setEmail($input->getArgument('email'))
            ->setPlainPassword($input->getArgument('password'))
            ->setRoles($input->getOption('role'));

        $user = $this->getContainer()->get('api_user.util.user_factory')->createUser(true);
        $result = $this->getContainer()->get('api_user.service.user')->updateUser($user, $dto);

        if ($result === $user) {
            $output->writeln(sprintf('Created user <comment>%s</comment>', $user->getEmail()));
        } else {
            $output->writeln(print_r($result, true));
        }
    }
}