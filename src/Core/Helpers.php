<?php
class Helpers
{
    public static function view($path, $data = array())
    {
        extract($data);
        include __DIR__ . '/../../public_html/views/layout/head.php';
        include __DIR__ . '/../../public_html/views/layout/header.php';
        include __DIR__ . '/../../public_html/views/layout/sidebar.php';
        include __DIR__ . '/../../public_html/views/pages/' . $path . '.php';
        include __DIR__ . '/../../public_html/views/layout/footer.php';
    }

    public static function render($path, $data = array())
    {
        extract($data);
        include __DIR__ . '/../../public_html/views/layout/auth_head.php';
        include __DIR__ . '/../../public_html/views/pages/' . $path . '.php';
        include __DIR__ . '/../../public_html/views/layout/auth_footer.php';
    }

    public static function url($path)
    {
        $config = include __DIR__ . '/../../config/config.php';
        $base = $config['app']['base_url'];
        return rtrim($base, '/') . $path;
    }

    public static function flash($key, $value = null)
    {
        if ($value !== null) {
            $_SESSION['flash'][$key] = $value;
            return null;
        }
        if (isset($_SESSION['flash'][$key])) {
            $val = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $val;
        }
        return null;
    }
}
