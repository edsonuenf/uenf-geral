<?php
/**
 * Template para exibição do menu UENF Simplificado
 * 
 * @package UENF_Geral
 */

// Verifica se a classe do menu walker existe
if (!class_exists('UENF_Menu_Walker')) {
    require_once get_template_directory() . '/components/menu/class-uenf-menu.php';
}

// Argumentos padrão para o menu
$defaults = array(
    'theme_location'  => 'primary', // Localização do tema
    'menu'            => '',
    'container'       => 'nav',
    'container_class' => 'menu-container',
    'container_id'    => 'site-navigation',
    'menu_class'      => 'new-menu',
    'menu_id'         => 'primary-menu',
    'echo'            => true,
    'fallback_cb'     => [UENF_Menu_Component::class, 'fallback_menu'],
    'before'          => '',
    'after'           => '',
    'link_before'     => '',
    'link_after'      => '',
    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'depth'           => 0,
    'walker'          => new UENF_Menu_Walker(),
);

// Filtro para permitir a modificação dos argumentos
$args = apply_filters('uenf_menu_args', $defaults);

// Exibe o menu
wp_nav_menu($args);
