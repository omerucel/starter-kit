<?php

namespace Application\Resource;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait HttpResource
{
    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!ResourceMemory::hasKey('response')) {
            $response = new Response();
            ResourceMemory::set('response', $response);
        }

        return ResourceMemory::get('response');
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!ResourceMemory::hasKey('request')) {
            $request = Request::createFromGlobals();
            ResourceMemory::set('request', $request);
        }

        return ResourceMemory::get('request');
    }
}