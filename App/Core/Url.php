<?php

namespace App\Core;


class Url
{
    public static function base($schema = false)
    {


        $scriptFile = $_SERVER['SCRIPT_FILENAME'];

        $scriptName = basename($scriptFile);
        if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
            $scriptUrl = $_SERVER['SCRIPT_NAME'];
        } elseif (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $scriptName) {
            $scriptUrl = $_SERVER['PHP_SELF'];
        } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
            $scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
        } elseif (isset($_SERVER['PHP_SELF']) && ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
            $scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
        } elseif (!empty($_SERVER['DOCUMENT_ROOT']) && strpos($scriptFile, $_SERVER['DOCUMENT_ROOT']) === 0) {
            $scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $scriptFile));
        }

        $uri = rtrim(dirname($scriptUrl), '\\/');

        if ($schema) {
            $http = (isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)) ? 'https' : 'http';
            $host = $http . '://' . $_SERVER['SERVER_NAME'];

            return $host . $uri;
        }

        return $uri;
    }

    public static function to($url, $schema = false)
    {
        return self::base($schema) . '/' . trim($url, '/');
    }
}