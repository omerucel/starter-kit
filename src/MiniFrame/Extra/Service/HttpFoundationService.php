<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpFoundationService extends BaseService
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @return Request
     */
    public function getRequest()
    {
        if ($this->request == null) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if ($this->response == null) {
            $this->response = new Response();
        }

        return $this->response;
    }
}
