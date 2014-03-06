<?php

namespace Application\Console;

use Application\Console\Command\CreateUser;
use Application\Console\Command\InitRoles;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use MiniFrame\Extra\Service\DoctrineService;
use Symfony\Component\Console\Helper\DialogHelper;

class Application extends \MiniFrame\ConsoleApplication\Application
{
    public function initCommands(\Symfony\Component\Console\Application $symfonyConsoleApp)
    {
        // Uygulamaya özel komutlar
        $symfonyConsoleApp->add(new CreateUser());
        $symfonyConsoleApp->add(new InitRoles());

        /**
         * Doctrine ile ilgili komutlar özel olarak ekleniyor.
         *
         * @var DoctrineService $doctrineService
         */
        $doctrineService = $this->getService('doctrine');
        $symfonyConsoleApp->setHelperSet(ConsoleRunner::createHelperSet($doctrineService->getEntityManager()));
        ConsoleRunner::addCommands($symfonyConsoleApp);

        // Doctrine Migrations commands
        $symfonyConsoleApp->getHelperSet()->set(new DialogHelper(), 'dialog');
        $symfonyConsoleApp->add(new DiffCommand());
        $symfonyConsoleApp->add(new MigrateCommand());
        $symfonyConsoleApp->add(new ExecuteCommand());
        $symfonyConsoleApp->add(new GenerateCommand());
        $symfonyConsoleApp->add(new LatestCommand());
        $symfonyConsoleApp->add(new StatusCommand());
        $symfonyConsoleApp->add(new VersionCommand());
    }
}
