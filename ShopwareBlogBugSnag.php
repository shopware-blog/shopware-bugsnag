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
        $bugsnag = new \Bugsnag_Client(0);
//        set_error_handler(array($bugsnag, 'errorHandler'));
        set_exception_handler(array($bugsnag, 'exceptionHandler'));
    }
}
