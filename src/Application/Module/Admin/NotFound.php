<?php

namespace Application\Module\Admin;

use Application\Resource\MonologResource;

class NotFound extends BaseSiteController
{
    /**
     * @param $name
     * @param $arguments
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __call($name, $arguments)
    {
        $logger = $this->getMonolog();
        $request = $this->getRequest();

        $logger->info(__METHOD__ . ' ' . $name . ' ' . $request->getRequestUri());
        return $this->render('admin/404.twig', array(), 404);
    }
}
