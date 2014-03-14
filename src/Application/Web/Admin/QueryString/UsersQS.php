<?php

namespace Application\Web\Admin\QueryString;

use Symfony\Component\HttpFoundation\Request;

class UsersQS extends BaseQS
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->params['page'] = intval($request->get('page'));
        $this->params['search'] = trim($request->get('search'));
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

    /**
     * @return bool
     */
    public function hasSearch()
    {
        return strlen($this->params['search']) > 0;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->params['search'];
    }

    /**
     * @return bool
     */
    public function hasPage()
    {
        return $this->params['page'] > 0;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->params['page'];
    }
}
