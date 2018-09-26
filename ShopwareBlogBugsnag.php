<?php declare(strict_types=1);

namespace ShopwareBlogBugsnag;

use Shopware\Components\Plugin;

class ShopwareBlogBugsnag extends Plugin
{
}

// check if it is not installed via composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}
