<?php

namespace MiniFrame\WebApplication;

use MiniFrame\BaseService;
use MiniFrame\Config;
use MiniFrame\Extra\Service\AuthService;
use MiniFrame\Extra\Service\DoctrineService;
use MiniFrame\Extra\Service\HttpFoundationService;
use MiniFrame\Extra\Service\MonologService;
use MiniFrame\Extra\Service\SessionHandlerService;
use MiniFrame\Extra\Service\TwigService;
use MiniFrame\ServiceLoader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /**
     * @var ServiceLoader
     */
    protected $serviceLoader;

    /**
     * @param ServiceLoader $serviceLoader
     */
    public function __construct(ServiceLoader $serviceLoader)
    {
        $this->serviceLoader = $serviceLoader;
    }

    /**
     * @return null
     */
    public function preDispatch()
    {
        $this->getTwigService()
            ->useTwigFunctionHelper('asset_url', 'assetUrlHelper')
            ->useTwigFunctionHelper('site_url', 'siteUrlHelper')
            ->useTwigFunctionHelper('media_url', 'mediaUrlHelper');
    }

    /**
     * @return null
     */
    public function postDispatch()
    {
        return null;
    }

    /**
     * @param $templateFile
     * @param array $templateVariables
     * @param int $status
     * @return Response
     */
    protected function render($templateFile, $templateVariables = array(), $status = 200)
    {
        $output = $this->getTwig()->render($templateFile, $templateVariables);
        return $this->toHtml($output, $status);
    }

    /**
     * @param array $result
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function toJson(array $result, $status = 200, $headers = array())
    {
        $content = json_encode($result);
        $headers['content-type'] = 'application/json; charset=utf-8';
        return $this->getResponse()->create($content, $status, $headers);
    }

    /**
     * @param $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function toHtml($content, $status = 200, $headers = array())
    {
        $headers['content-type'] = 'text/html; charset=utf-8';
        return $this->getResponse()->create($content, $status, $headers);
    }

    /**
     * @param $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function toXml($content, $status = 200, $headers = array())
    {
        $headers['content-type'] = 'text/xml; charset=utf-8';
        return $this->getResponse()->create($content, $status, $headers);
    }

    /**
     * @param $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function toPlainText($content, $status = 200, $headers = array())
    {
        $headers['content-type'] = 'text/plain; charset=utf-8';
        return $this->getResponse()->create($content, $status, $headers);
    }

    /**
     * @param $url
     * @param int $code
     * @param array $headers
     * @return RedirectResponse
     */
    protected function redirect($url, $code = 302, $headers = array())
    {
        return new RedirectResponse($url, $code, $headers);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->getHttpFoundationService()->getRequest();
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->getHttpFoundationService()->getResponse();
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this->getTwigService()->getTwig();
    }

    /**
     * @return \Monolog\Logger
     */
    public function getDefaultLogger()
    {
        return $this->getMonologService()->getDefaultLogger();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->getSessionHandlerService()->getSession();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getDoctrineService()->getEntityManager();
    }

    /**
     * @return HttpFoundationService
     */
    public function getHttpFoundationService()
    {
        return $this->getService('http_foundation');
    }

    /**
     * @return DoctrineService
     */
    public function getDoctrineService()
    {
        return $this->getService('doctrine');
    }

    /**
     * @return TwigService
     */
    public function getTwigService()
    {
        return $this->getService('twig');
    }

    /**
     * @return MonologService
     */
    public function getMonologService()
    {
        return $this->getService('monolog');
    }

    /**
     * @return SessionHandlerService
     */
    public function getSessionHandlerService()
    {
        return $this->getService('session_handler');
    }

    /**
     * @return AuthService
     */
    public function getAuthService()
    {
        return $this->getService('auth');
    }

    /**
     * @param $name
     * @return BaseService
     */
    public function getService($name)
    {
        return $this->getServiceLoader()->getService($name);
    }

    /**
     * @return Config
     */
    public function getConfigs()
    {
        return $this->getServiceLoader()->getConfigs();
    }

    /**
     * @return ServiceLoader
     */
    public function getServiceLoader()
    {
        return $this->serviceLoader;
    }
}
