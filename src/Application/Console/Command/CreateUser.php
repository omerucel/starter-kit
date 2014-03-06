<?php

namespace Application\Console\Command;

use Application\Entity\User;
use MiniFrame\ConsoleApplication\ApplicationHelper;
use MiniFrame\Extra\Service\DoctrineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    protected function configure()
    {
        $this
            ->setName('application:create-user')
            ->setDescription('Yeni bir kullanıcı oluşturur.')
            ->addOption(
                'role',
                null,
                InputOption::VALUE_REQUIRED,
                'Kullanıcı rolü.'
            )
            ->addOption(
                'username',
                null,
                InputOption::VALUE_REQUIRED,
                'Kullanıcı adı.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var DoctrineService $doctrineService
         * @var ApplicationHelper $applicationHelper
         */
        $applicationHelper = $this->getHelperSet()->get('application');
        $doctrineService = $applicationHelper->getService('doctrine');

        $role = $doctrineService->getEntityManager()->getRepository('Application\Entity\Role')
            ->findOneBy(array('name' => $input->getOption('role')));

        // Kullanıcıyı oluştur.
        $user = new User();
        $user->setUsername($input->getOption('username'));
        $user->setRole($role);

        $password = $user->generatePassword();
        $output->writeln("Password : " . $password);

        $doctrineService->getEntityManager()->persist($user);
        $doctrineService->getEntityManager()->flush();
    }
}
