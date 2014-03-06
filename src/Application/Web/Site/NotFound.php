<?php

namespace Application\Web\Site;

class NotFound extends BaseController
{
    /**
     * @param $name
     * @param $arguments
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __call($name, $arguments)
    {
        $this->getDefaultLogger()->info(__METHOD__ . ' ' . $name . ' ' . $this->getRequest()->getRequestUri());
        return $this->render('site/404.twig', array(), 404);
    }
}
