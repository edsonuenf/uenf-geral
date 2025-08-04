<?php
/**
 * PHPUnit bootstrap file
 */

error_reporting( E_ALL | E_STRICT );

// Define constantes do WordPress
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}

// Carrega o autoload do Composer se disponível
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

// Carrega as funções do tema
require_once ABSPATH . 'functions.php';

// Inclui funções de teste personalizadas
require_once __DIR__ . '/test-functions.php';

// Se precisar de funções específicas do WordPress para testes
if (file_exists(ABSPATH . 'wp-includes/functions.php')) {
    require_once ABSPATH . 'wp-includes/functions.php';
}
