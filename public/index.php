<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';


return function (array $context): Kernel {
    // $context['APP_ENV'] = 'dev';
    // $context['APP_DEBUG'] = true;
    // exit;
    // var_export(['context' => $context]);
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
