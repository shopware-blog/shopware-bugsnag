<?php declare(strict_types=1);

namespace ShopwareBlogBugSnag;

use Shopware\Components\Plugin;

require_once __DIR__ . '/Bugsnag/Autoload.php';

class ShopwareBlogBugSnag extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Front_RouteShutdown' => ['handleError', 1000],
            'Enlight_Controller_Front_PostDispatch' => ['handleError', 1000],
        ];
    }

    /**
     * @param \Enlight_Controller_EventArgs $args
     */
    public function handleError(\Enlight_Controller_EventArgs $args)
    {
        $front = $args->getSubject();

        if ($front->getParam('noErrorHandler')) {
            return;
        }

        $config = $this->container->get('shopware.plugin.config_reader')->getByPluginName($this->getName());
        $apiKey = $config['apiKey'];
        if (empty($apiKey)) {
            return;
        }

        $bugSnag = new \Bugsnag_Client($apiKey);

        if ($config['exceptionHandler']) {
            set_exception_handler([$bugSnag, 'exceptionHandler']);
        }

        if ($config['errorHandler']) {
            set_error_handler([$bugSnag, 'errorHandler']);
        }
    }
}
