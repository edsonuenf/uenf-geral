<?php
/**
 * The sidebar containing the main widget area
 */

if ( ! is_active_sidebar( 'sidebar-1' ) || get_theme_mod('disable_sidebar', true) ) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside> 