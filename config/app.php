<?php

if (!function_exists('app_normalize_path')) {
    function app_normalize_path($path)
    {
        $realPath = realpath($path);
        if ($realPath !== false) {
            $path = $realPath;
        }

        return rtrim(str_replace('\\', '/', $path), '/');
    }
}

if (!function_exists('app_root_path')) {
    function app_root_path($path = '')
    {
        $root = dirname(__DIR__);

        if ($path === '') {
            return $root;
        }

        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);

        return $root . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('base_path')) {
    function base_path()
    {
        static $basePath = null;

        if ($basePath !== null) {
            return $basePath;
        }

        $basePath = '';
        $appRoot = app_normalize_path(dirname(__DIR__));
        $scriptFilename = isset($_SERVER['SCRIPT_FILENAME']) ? app_normalize_path($_SERVER['SCRIPT_FILENAME']) : '';
        $scriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';

        if ($scriptFilename !== '' && $scriptName !== '') {
            $appRootCompare = strtolower($appRoot);
            $scriptCompare = strtolower($scriptFilename);

            if ($scriptCompare === $appRootCompare || strpos($scriptCompare, $appRootCompare . '/') === 0) {
                $relativeScript = substr($scriptFilename, strlen($appRoot));
                $relativeScript = '/' . ltrim($relativeScript, '/');
                $relativeLength = strlen($relativeScript);

                if ($relativeLength > 0 && substr($scriptName, -$relativeLength) === $relativeScript) {
                    $basePath = substr($scriptName, 0, -$relativeLength);
                }
            }
        }

        if ($basePath === '') {
            $requestPath = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
            $rootName = basename($appRoot);
            $needle = '/' . $rootName;

            if ($requestPath !== '' && $rootName !== '') {
                $position = strpos($requestPath, $needle);
                if ($position !== false) {
                    $basePath = substr($requestPath, 0, $position + strlen($needle));
                }
            }
        }

        $basePath = '/' . trim($basePath, '/');
        if ($basePath === '/') {
            $basePath = '';
        }

        return $basePath;
    }
}

if (!function_exists('base_url')) {
    function base_url($path = '')
    {
        $basePath = base_path();
        $path = (string) $path;

        if ($path === '') {
            return $basePath === '' ? '/' : $basePath . '/';
        }

        if (preg_match('#^(?:https?:)?//#i', $path)) {
            return $path;
        }

        if ($path[0] === '#') {
            return $path;
        }

        $path = ltrim($path, '/');

        return ($basePath === '' ? '' : $basePath) . '/' . $path;
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path)
    {
        return base_url($path);
    }
}

if (!function_exists('app_session_start')) {
    function app_session_start()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            if (!headers_sent()) {
                $cookieParams = session_get_cookie_params();
                $cookiePath = base_path();
                $cookiePath = $cookiePath === '' ? '/' : $cookiePath . '/';
                $secure = !empty($cookieParams['secure']);

                if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
                    $secure = true;
                }

                session_set_cookie_params(
                    isset($cookieParams['lifetime']) ? $cookieParams['lifetime'] : 0,
                    $cookiePath,
                    isset($cookieParams['domain']) ? $cookieParams['domain'] : '',
                    $secure,
                    isset($cookieParams['httponly']) ? $cookieParams['httponly'] : true
                );
            }

            session_start();
        }
    }
}

if (!function_exists('redirect')) {
    function redirect($path = 'index.php', $statusCode = 302)
    {
        $url = base_url($path);

        if (!headers_sent()) {
            header('Location: ' . $url, true, $statusCode);
        } else {
            echo '<script>window.location.href = ' . json_encode($url, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) . ';</script>';
        }

        exit;
    }
}
