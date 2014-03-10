<?php

namespace MiniFrame\ConsoleApplication;

use MiniFrame\BaseApplication;

abstract class Application extends BaseApplication
{
    public function serve()
    {
        $symfonyConsoleApp = new \Symfony\Component\Console\Application(
            $this->getConfigs()->get('console_application.name'),
            $this->getConfigs()->get('console_application.version')
        );
        $symfonyConsoleApp->getHelperSet()->set(new ServiceLoaderHelper($this->getServiceLoader()));
        $this->initCommands($symfonyConsoleApp);
        $symfonyConsoleApp->run();
    }

    abstract public function initCommands(\Symfony\Component\Console\Application $symfonyConsoleApp);
}
