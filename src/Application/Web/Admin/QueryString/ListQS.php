<?php

namespace Application\Web\Admin\QueryString;

use Symfony\Component\HttpFoundation\Request;

class ListQS extends BaseQS
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->params['page'] = intval($request->get('page'));
        $this->params['search'] = trim($request->get('search'));
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
