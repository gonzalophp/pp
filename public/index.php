<?php

use App\Kernel;



require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// var_export($_SERVER);


return function (array $context): Kernel {
    // var_export($_SERVER);
    $context['APP_ENV'] = 'dev';
    $context['APP_DEBUG'] = true;
    // exit;
    // var_export(['context' => $context]);
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
