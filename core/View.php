<?php

namespace Core;

class View
{
    /**
     * @param $view       Name of view file
     * @param array $args Array variables
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);
        $file = dirname(__DIR__) . "/app/Views/$view.php";
        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }
}
