<?php

namespace Application\Console\Command;

use Application\Entity\User;
use MiniFrame\ConsoleApplication\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends BaseCommand
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
            )
            ->addOption(
                'password',
                null,
                InputOption::VALUE_REQUIRED,
                'Şifre'
            )
            ->addOption(
                'email',
                null,
                InputOption::VALUE_REQUIRED,
                'E-Posta Adresi'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $role = $this->getEntityManager()->getRepository('Application\Entity\Role')
            ->findOneBy(array('name' => $input->getOption('role')));

        // Kullanıcıyı oluştur.
        $user = new User();
        $user->setUsername($input->getOption('username'));
        $user->setRole($role);
        $user->setPassword($input->getOption('password'));
        $user->setEmail($input->getOption('email'));

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
