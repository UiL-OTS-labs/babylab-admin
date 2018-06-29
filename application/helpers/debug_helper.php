<?php
/////////////////////////
// Debug functions
// Warning! When adding functions, make sure they do not have side effects or print statements in production!
/////////////////////////

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if(!function_exists('make_printable'))
{
    function make_printable($var)
    {
        if(is_numeric($var) || is_string($var))
            return $var;

        if(is_array($var))
        {
            foreach($var as &$value)
                $value = make_printable($value);

            return 'a::['.implode(", ", $var).']';
        }

        if(is_object($var))
            return 'o::'.get_class($var);

        return gettype($var);
    }
}

if(!function_exists('pretty_print_backtrace'))
{
    function pretty_print_backtrace($n = 5)
    {
        // Do not print anything in production
        if(!in_development())
            return;

        $trace = debug_backtrace(0, $n);

        $cleaner = function ($str) {
            return str_replace(FCPATH, '', $str);
        };

        $i = 0; # Ugly counter
        foreach ($trace as $item)
        {
            $item['file'] = isset($item['file']) ? $cleaner($item['file']) : 'Unknown File';
            $item['line'] = isset($item['line']) ? $item['line'] : '-1';

            echo $i.': '.$item['file'].':'.$item['line'].' - '.$item['function'].'(';
            if(isset($item['args']))
                echo implode(", ",array_map("make_printable", $item['args']));
            echo '); <br/>';
            $i++;
        }
    }
}