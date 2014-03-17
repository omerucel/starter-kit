<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;
use MiniFrame\Extra\Service\TwigService\TwigEnvironment;

class TwigService extends BaseService
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var \Twig_LoaderInterface
     */
    protected $twigLoader;

    /**
     * @var array
     */
    protected $registeredTwigFunctions = array();

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        if ($this->twig == null) {
            $twigConfigs = (array)$this->getConfigs()->getArray('twig');
            $this->twig = new TwigEnvironment($this->getTwigLoader(), $twigConfigs);
        }

        return $this->twig;
    }

    /**
     * @return \Twig_Loader_Filesystem|\Twig_LoaderInterface
     */
    public function getTwigLoader()
    {
        if ($this->twigLoader == null) {
            $this->twigLoader = new \Twig_Loader_Filesystem($this->getConfigs()->get('twig.template_path'));
        }

        return $this->twigLoader;
    }

    /**
     * @param $funcName
     * @param $funcHelperName
     * @return TwigService
     */
    public function useTwigFunctionHelper($funcName, $funcHelperName)
    {
        if (!isset($this->registeredTwigFunctions[$funcName])) {
            if (is_array($funcHelperName)) {
                $func = new \Twig_SimpleFunction($funcName, $funcHelperName);
            } else {
                $func = new \Twig_SimpleFunction($funcName, array($this, $funcHelperName));
            }
            $this->getTwig()->addFunction($funcName, $func);
            $this->registeredTwigFunctions[$funcName] = true;
        }

        return $this;
    }

    /**
     * @param $funcName
     * @param $funcHelperName
     * @return TwigService
     */
    public function useTwigFilterHelper($funcName, $funcHelperName)
    {
        if (!isset($this->registeredTwigFunctions[$funcName])) {
            if (is_array($funcHelperName)) {
                $func = new \Twig_SimpleFilter($funcName, $funcHelperName);
            } else {
                $func = new \Twig_SimpleFilter($funcName, array($this, $funcHelperName));
            }
            $this->getTwig()->addFilter($funcName, $func);
            $this->registeredTwigFunctions[$funcName] = true;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function assetUrlHelper()
    {
        return $this->checkPath('web_application.asset_url_prefix', func_get_args());
    }

    /**
     * @return string
     */
    public function siteUrlHelper()
    {
        return $this->checkPath('web_application.site_url_prefix', func_get_args());
    }

    /**
     * @return string
     */
    public function mediaUrlHelper()
    {
        return $this->checkPath('web_application.media_url_prefix', func_get_args());
    }

    /**
     * @param $configKey
     * @param $arguments
     * @return string
     */
    public function checkPath($configKey, $arguments)
    {
        $path = array_shift($arguments);
        $path = vsprintf($path, $arguments);
        if ($path[0] == '/') {
            $path = substr($path, 1);
        }

        return $this->getConfigs()->get($configKey) . $path;
    }
}
