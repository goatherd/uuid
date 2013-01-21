<?php

// https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#example-implementation
function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    $fn = __DIR__ . '/../library/' . $fileName;
    if (file_exists($fn)) {
        require_once $fn;
    }
}

spl_autoload_register('autoload');