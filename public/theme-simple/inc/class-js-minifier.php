<?php
/**
 * JavaScript Minifier Class
 * 
 * Simple JavaScript minifier for WordPress themes
 * Based on JSMin algorithm
 * 
 * @package UENF_Geral
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * JSMin - JavaScript Minifier
 * 
 * Simple JavaScript minification class
 */
class JSMin {
    
    /**
     * Minify JavaScript code
     * 
     * @param string $js JavaScript code to minify
     * @return string Minified JavaScript
     */
    public static function minify($js) {
        // Remove comments
        $js = preg_replace('/\/\*[\s\S]*?\*\//', '', $js);
        $js = preg_replace('/\/\/.*$/', '', $js);
        
        // Remove unnecessary whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Remove whitespace around operators
        $js = preg_replace('/\s*([{}();,=+\-*\/])\s*/', '$1', $js);
        
        // Remove leading and trailing whitespace
        $js = trim($js);
        
        return $js;
    }
    
    /**
     * Minify JavaScript file
     * 
     * @param string $file_path Path to JavaScript file
     * @return string|false Minified JavaScript or false on error
     */
    public static function minify_file($file_path) {
        if (!file_exists($file_path)) {
            return false;
        }
        
        $js = file_get_contents($file_path);
        if ($js === false) {
            return false;
        }
        
        return self::minify($js);
    }
    
    /**
     * Minify and save JavaScript file
     * 
     * @param string $input_file Input JavaScript file path
     * @param string $output_file Output minified file path
     * @return bool Success status
     */
    public static function minify_and_save($input_file, $output_file) {
        $minified = self::minify_file($input_file);
        
        if ($minified === false) {
            return false;
        }
        
        return file_put_contents($output_file, $minified) !== false;
    }
}

/**
 * Helper function for backward compatibility
 * 
 * @param string $js JavaScript code to minify
 * @return string Minified JavaScript
 */
if (!function_exists('jsmin')) {
    function jsmin($js) {
        return JSMin::minify($js);
    }
}