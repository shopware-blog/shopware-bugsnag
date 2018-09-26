<?php declare(strict_types=1);

namespace ShopwareBlogBugsnag\Source;

use Bugsnag\Client;
use Shopware\Components\Plugin\CachedConfigReader;

class BugsnagClient
{
    /**
     * @var CachedConfigReader
     */
    private $configReader;

    /**
     * @var Client
     */
    private $bugsnagClient;

    public function __construct(CachedConfigReader $configReader)
    {
        $this->configReader = $configReader;
    }

    public function getInstance(): Client
    {
        if (!$this->bugsnagClient) {
            $config = $this->getConfig();
            if (empty($config['apiKey'])) {
                $this->bugsnagClient = Client::make();
            } else {
                $this->bugsnagClient = Client::make($config['apiKey']);
            }
        }

        return $this->bugsnagClient;
    }

    public function registerHandler()
    {
        $bugsnag = $this->getInstance();
        $config = $this->getConfig();

        $bugsnagHandler = new \Bugsnag\Handler($bugsnag);
        if ($config['exceptionHandler']) {
            $bugsnagHandler->registerExceptionHandler(false);
        }

        if ($config['errorHandler']) {
            $bugsnagHandler->registerErrorHandler(false);
        }
    }

    private function getConfig(): array
    {
        return $this->configReader->getByPluginName('ShopwareBlogBugsnag');
    }
}
