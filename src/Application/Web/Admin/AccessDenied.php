<?php

namespace Application\Web\Admin;

class AccessDenied extends BaseController
{
    public function get()
    {
        $this->getDefaultLogger()->warning(
            'User permission error.',
            array(
                'username' => $this->getAuthService()->getCurrentUser()->getUsername(),
                'header' => $this->getRequest()->headers->all(),
                'server' => $this->getRequest()->server->all(),
                'params' => $this->getRequest()->request->all()
            )
        );
        return $this->render('admin/403.twig', array(), 403);
    }
}
