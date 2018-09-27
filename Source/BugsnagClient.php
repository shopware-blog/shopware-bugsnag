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

            if ($config['anonymizeIp']) {
                $this->bugsnagClient->registerCallback(
                    function ($report) {
                        // replaces the automatically collected IP
                        $report->setUser(['id' => null]);

                        // replaces the automatically collected IP
                        $report->addMetaData(['request' => ['clientIp' => null],]);
                    }
                );
            }
        }

        return $this->bugsnagClient;
    }

    public function registerHandler()
    {
        $config = $this->getConfig();
        $bugsnag = $this->getInstance();

        $bugsnagHandler = new \Bugsnag\Handler($bugsnag);

        if ($config['exceptionHandler']) {
            $bugsnagHandler->registerExceptionHandler(false);
        }

        if ($config['errorHandler']) {
            $bugsnagHandler->registerErrorHandler(false);
        }

        // restore default error handler
        restore_error_handler();
        restore_exception_handler();
    }

    private function getConfig(): array
    {
        return $this->configReader->getByPluginName('ShopwareBlogBugsnag');
    }
}
