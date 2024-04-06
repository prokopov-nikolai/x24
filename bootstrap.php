<?php
declare(strict_types=1);

session_start();

include 'config/config.php';

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/x24/');

function autoload($className) {
    $paths = explode('_', $className);
    $path = ROOT_PATH . 'modules/' . strtolower($className) . '/' . $className . '.php';
    $pathFr = ROOT_PATH . 'framework/' . $className . '.php';

    if (file_exists($path)) {
        require($path);
    } else if (file_exists($pathFr)) {
        require($pathFr);
    }
}

spl_autoload_register('autoload');

if (!function_exists('pr')) {
    /**
     * Функции для дебажинга
     * @param $array
     * @param bool $return
     * @return bool|string
     */
    function pr($array, $return = false)
    {
        if ($return === false) {
            ob_start();
            echo '<pre style="display: block; overflow: hidden; line-height: 30px;text-align: left;">';
            print_r($array);
            echo '</pre>';
            return false;
        } else {
            ob_start();
            echo '<pre>';
            print_r($array);
            echo '</pre>';
            return ob_get_clean();
        }
    }

    function prex($array)
    {
        pr($array);
        exit;
    }
}
