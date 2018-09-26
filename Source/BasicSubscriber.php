<?php declare(strict_types=1);

namespace ShopwareBlogBugsnag\Source;

use Enlight\Event\SubscriberInterface;

class BasicSubscriber implements SubscriberInterface
{
    /**
     * @var BugsnagClient
     */
    private $bugsnagClient;

    public function __construct(BugsnagClient $bugsnagClient)
    {
        $this->bugsnagClient = $bugsnagClient;
    }

    public static function getSubscribedEvents(): array
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

        $this->bugsnagClient->registerHandler();
    }
}
