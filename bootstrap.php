<?php
namespace WpEbscoWidget;
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

spl_autoload_register(__NAMESPACE__ . '\\autoload');
function autoload($className)
{
    $className = ltrim($className, '\\');
    if(strpos($className, __NAMESPACE__) !== 0) {
        return;
    }

    $className = str_replace(__NAMESPACE__. '\\', '', $className);
    // convert camelcase to dash
    $className = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $className));

    $path = EBSCO_WIDGET__PLUGIN_PATH .
        str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    require_once($path);
}
