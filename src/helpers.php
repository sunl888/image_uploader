<?php
if (!function_exists('image_url')) {

    function image_url($image, $style = null, $default = null)
    {
        static $config = [];
        if (is_null($image)) {
            return value($default);
        }
        if (empty($config))
            $config = config('image_upload');
        if ($config['disk'] == 'local') {
            $parameters = ['image' => $image->hash];
            if (is_array($style)) {
                $parameters = array_merge($parameters, $style);
            } elseif (is_string($style)) {
                $parameters['p'] = $style;
            }
            return route(config('image_upload.route_name'), $parameters);
        } else {
            if (is_array($style)) {
                $style = array_merge($config['default_style'], $style);
            } elseif (isset($config['presets'][$style])) {
                $style = array_merge($config['default_style'], $config['presets'][$style]);
            } else {
                $style = null;
            }
            if (!empty($style)) {
                if (isset($style['q'])) {
                    $q = "q/{$style['q']}|imageslim";
                } else {
                    $q = '';
                }
                $parameters = '?imageView2/1/' . (isset($style['w']) ? "w/{$style['w']}/" : '') . (isset($style['h']) ? "h/{$style['h']}/" : '') . $q;
            } else {
                $parameters = '';
            }
            return $image->cloud_url . $parameters;
        }
    }
}