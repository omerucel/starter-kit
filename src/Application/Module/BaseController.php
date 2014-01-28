<?php

namespace Application\Module;

use Application\Resource\ConfigResource;
use Application\Resource\HttpResource;
use Application\Resource\MonologResource;
use Application\Resource\TwigResource;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController
{
    use ConfigResource;
    use TwigResource;
    use HttpResource;
    use MonologResource;

    /**
     * @return null|Response
     */
    public function preDispatch()
    {
        return null;
    }

    /**
     * @return null|Response
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
    public function render($templateFile, $templateVariables = array(), $status = 200)
    {
        $output = $this->getTwig()->render($templateFile, $templateVariables);
        return $this->toHtml($output, $status);
    }

    /**
     * @param array $result
     * @param int $status
     * @return Response
     */
    public function toJson(array $result, $status = 200)
    {
        $content = json_encode($result);
        return $this->createResponse($content, $status, 'application/json');
    }

    /**
     * @param $content
     * @param int $status
     * @return Response
     */
    public function toHtml($content, $status = 200)
    {
        return $this->createResponse($content, $status);
    }

    /**
     * @param $content
     * @param int $status
     * @return Response
     */
    public function toXml($content, $status = 200)
    {
        return $this->createResponse($content, $status, 'text/xml');
    }

    /**
     * @param $content
     * @param int $status
     * @return Response
     */
    public function toPlainText($content, $status = 200)
    {
        return $this->createResponse($content, $status, 'text/plain');
    }

    /**
     * @param $csvFileName
     * @param $templateFile
     * @param array $templateVariables
     * @param int $status
     * @return Response
     */
    public function renderForCsv($csvFileName, $templateFile, $templateVariables = array(), $status = 200)
    {
        $response = $this->render($templateFile, $templateVariables, $status);
        $response->headers->set('Content-Description', 'CSV File');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $csvFileName);
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    /**
     * @param $content
     * @param $status
     * @param string $contentType
     * @param string $charset
     * @return Response
     */
    public function createResponse($content, $status, $contentType = 'text/html', $charset = 'utf-8')
    {
        return $this->getResponse()
            ->create(
                $content,
                $status,
                array(
                    'content-type' => strtr(
                        ':contentType; charset=:charset',
                        array(
                            ':contentType' => $contentType,
                            ':charset' => $charset
                        )
                    )
                )
            );
    }

    /**
     * @param $url
     * @param int $code
     * @return RedirectResponse
     */
    public function redirect($url, $code = 302)
    {
        return new RedirectResponse($url, $code);
    }
}
