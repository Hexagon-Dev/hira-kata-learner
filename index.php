<?php

ignore_user_abort(true);

require __DIR__ . '/vendor/autoload.php';

$myApp = new \HexagonDev\HiraKataLearner\App;

$handler = static function () use ($myApp) {
    echo $myApp->handle($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
};

$maxRequests = (int)($_SERVER['MAX_REQUESTS'] ?? 0);
for ($nbRequests = 0; !$maxRequests || $nbRequests < $maxRequests; ++$nbRequests) {
    $keepRunning = \frankenphp_handle_request($handler);

    $myApp->terminate();

    gc_collect_cycles();

    if (!$keepRunning) {
        break;
    }
}

$myApp->shutdown();
