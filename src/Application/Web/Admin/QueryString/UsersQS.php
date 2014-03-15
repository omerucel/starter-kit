<?php

namespace Application\Web\Admin\QueryString;

use Symfony\Component\HttpFoundation\Request;

class UsersQS extends ListQS
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->params['role_id'] = intval($request->get('role_id'));
    }

    /**
     * @return bool
     */
    public function hasRoleId()
    {
        return $this->params['role_id'] > 0;
    }

    /**
     * @return int
     */
    public function getRoleId()
    {
        return $this->params['role_id'];
    }
}
