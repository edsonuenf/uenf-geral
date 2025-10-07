<?php
// Obter configurações do Customizer
$placeholder = get_theme_mod('cct_search_placeholder', 'Buscar...');
$button_text = get_theme_mod('cct_search_button_text', 'Buscar');
// Verificar se pelo menos um dispositivo tem texto ativado
$show_text_desktop = get_theme_mod('cct_search_show_button_text_desktop', true);
$show_text_tablet = get_theme_mod('cct_search_show_button_text_tablet', true);
$show_text_mobile = get_theme_mod('cct_search_show_button_text_mobile', false);
$show_button_text = $show_text_desktop || $show_text_tablet || $show_text_mobile;

// Configurações da busca retrátil
$is_retractable = get_theme_mod('cct_search_retractable', false);
$retractable_icon = get_theme_mod('cct_search_retractable_icon', 'fas fa-search');
$retractable_position = get_theme_mod('cct_search_retractable_position', 'top-right');
?>

<?php if ($is_retractable) : ?>
    <!-- Busca Retrátil Inline -->
    <div class="search-retractable-inline">
        <button type="button" class="search-retractable-toggle-inline search-custom-uenf" aria-label="Abrir busca">
            <i class="<?php echo esc_attr($retractable_icon); ?>"></i>
            <?php if ($show_button_text) : ?>
                <span class="search-text"><?php echo esc_html($button_text); ?></span>
            <?php endif; ?>
        </button>
        <div class="search-retractable-form-inline" style="display: none;">
            <form role="search" method="get" class="custom-search-form search-custom-uenf" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" class="search-field search-custom-uenf" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s" alt="<?php echo esc_attr($button_text); ?>" />
                <button type="submit" class="search-submit search-custom-uenf">
                    <i class="fas fa-search"></i>
                    <?php if ($show_button_text) : ?>
                        <span class="search-text"><?php echo esc_html($button_text); ?></span>
                    <?php endif; ?>
                </button>
                <button type="button" class="search-retractable-close-inline search-custom-uenf" aria-label="Fechar busca">
                    <i class="fas fa-times"></i>
                </button>
            </form>
        </div>
    </div>
<?php else : ?>
    <!-- Busca Normal -->
    <form role="search" method="get" class="custom-search-form search-custom-uenf" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="search" class="search-field search-custom-uenf" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo get_search_query(); ?>" name="s" alt="<?php echo esc_attr($button_text); ?>" />
        <button type="submit" class="search-submit search-custom-uenf">
            <i class="fas fa-search"></i>
            <?php if ($show_button_text) : ?>
                <span class="search-text"><?php echo esc_html($button_text); ?></span>
            <?php endif; ?>
        </button>
    </form>
<?php endif; ?>
