<?php

namespace Application\Resource;

use Application\Resource\Twig\TwigEnvironment;

trait TwigResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return TwigEnvironment
     */
    public function getTwig()
    {
        if (!ResourceMemory::hasKey('twig')) {
            $configs = $this->getConfigs();
            $twigConfigs = $configs['twig'];

            $loader = new \Twig_Loader_Filesystem($twigConfigs['template_path']);
            $twig = new TwigEnvironment($loader, $twigConfigs['options']);

            $checkPath = function ($arguments) {
                $path = array_shift($arguments);
                $path = vsprintf($path, $arguments);
                if ($path[0] == '/') {
                    $path = substr($path, 1);
                }

                return $path;
            };

            $asset = new \Twig_SimpleFunction(
                'asset',
                function () use ($configs, $checkPath) {
                    $arguments = func_get_args();
                    $path = $checkPath($arguments);

                    return $configs['asset_url'] . $path;
                }
            );

            $siteUrl = new \Twig_SimpleFunction(
                'site_url',
                function () use ($configs, $checkPath) {
                    $arguments = func_get_args();
                    $path = $checkPath($arguments);

                    return $configs['site_url'] . $path;
                }
            );

            $mediaUrl = new \Twig_SimpleFunction(
                'media_url',
                function () use ($configs, $checkPath) {
                    $arguments = func_get_args();
                    $path = $checkPath($arguments);

                    return $configs['media_url'] . $path;
                }
            );

            $twig->addFunction('asset', $asset);
            $twig->addFunction('site_url', $siteUrl);
            $twig->addFunction('media_url', $mediaUrl);

            ResourceMemory::set('twig', $twig);
        }

        return ResourceMemory::get('twig');
    }
}
