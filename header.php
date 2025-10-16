<!DOCTYPE html>
<?php if ( function_exists( 'language_attributes' ) ) : ?>
<html <?php language_attributes(); ?>>
<?php endif; ?>
<head>
    <!-- Cabeçalho do documento HTML, inclui metadados e links para recursos externos -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php if ( function_exists( 'wp_head' ) ) : ?>
    <?php wp_head(); ?> <!-- Hook do WordPress para incluir scripts e estilos no cabeçalho -->
    <?php endif; ?>
    <!-- Estilos carregados via wp_enqueue_style no functions.php para melhor performance -->
    
    <!-- CSS inline para correção de alinhamento de imagens -->
    <style type="text/css">
    /* Força alinhamento de imagens com máxima prioridade */
    body .entry-content img.alignleft,
    body .entry-content figure.alignleft,
    body .entry-content .wp-block-image.alignleft,
    body .entry-content .wp-block-image.alignleft img {
        float: left !important;
        margin: 0 20px 20px 0 !important;
        display: block !important;
        clear: none !important;
        max-width: 300px !important;
    }
    
    body .entry-content img.alignright,
    body .entry-content figure.alignright,
    body .entry-content .wp-block-image.alignright,
    body .entry-content .wp-block-image.alignright img {
        float: right !important;
        margin: 0 0 20px 20px !important;
        display: block !important;
        clear: none !important;
        max-width: 300px !important;
    }
    
    body .entry-content img.aligncenter,
    body .entry-content figure.aligncenter,
    body .entry-content .wp-block-image.aligncenter,
    body .entry-content .wp-block-image.aligncenter img {
        display: block !important;
        margin: 20px auto !important;
        float: none !important;
        clear: both !important;
    }
    
    /* Permitir texto ao lado das imagens */
    body .entry-content .alignleft + p,
    body .entry-content .alignright + p,
    body .entry-content .wp-caption.alignleft + p,
    body .entry-content .wp-caption.alignright + p,
    body .entry-content .wp-block-image.alignleft + p,
    body .entry-content .wp-block-image.alignright + p {
        clear: none !important;
        margin-top: 0 !important;
    }
    </style>


</head>
<body<?php
    $classes = get_body_class();
    // Remove a classe 'search-results' do array de classes
    $classes = array_diff($classes, array('search-results'));
    if (!empty($classes)) {
        echo ' class="' . esc_attr(implode(' ', $classes)) . '"';
    }
?>>
<?php 
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>
<div id="page" class="site">
    <!-- Header -->
    <header id="masthead" class="site-header">
      <div class="bg-header-logo">
        <div class="container">
          <div class="row">
            <div class="col-md-4">
              <div class="logo">
                <a href="https://uenf.br" target="_blank">
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo-uenf-transparente.png" alt="UENF">
                </a>
              </div>
            </div>
            <div class="col-md-8 header-media-grid">
                <div class="idiomas-bandeiras">
                    <?php dynamic_sidebar('idiomas-uenf'); ?>
                </div>
                <div class="social-media">
                    <?php cct_display_social_media(); ?>
                </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Navbar Superior com Logo -->
      <nav class="navbar navbar-dark navbar-uenf">
        <div class="container header-grid-container">
          <div class="header-grid-col1">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral" aria-controls="menuLateral" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </div>

          <div class="header-grid-col2">
            <div class="search-container search-custom-uenf">
              <?php 
              // Verificar se a extensão de busca personalizada está ativa
              $search_extension_active = get_theme_mod('cct_extension_search_customizer_enabled', false);
              if ($search_extension_active) {
                  get_search_form();
              }
              ?>
            </div>

            <div class="offcanvas offcanvas-start" id="menuLateral" tabindex="-1" aria-labelledby="menuLateralLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="menuLateralLabel">&nbsp;</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <?php
                  // Carrega o novo menu
                  if (function_exists('UENF_Menu_Component::display_menu')) {
                      UENF_Menu_Component::display_menu([
                          'theme_location' => 'primary',
                          'menu_id'        => 'primary-menu',
                          'menu_class'     => 'new-menu',
                          'container'      => false,
                          'fallback_cb'    => function() {
                              // Fallback para o menu padrão do WordPress
                              wp_nav_menu([
                                  'theme_location' => 'primary',
                                  'menu_id'        => 'primary-menu',
                                  'container'      => false,
                                  'menu_class'     => 'new-menu',
                                  'fallback_cb'    => 'wp_page_menu',
                              ]);
                          }
                      ]);
                  } else {
                      // Fallback direto para o menu padrão do WordPress
                      wp_nav_menu([
                          'theme_location' => 'primary',
                          'menu_id'        => 'primary-menu',
                          'container'      => false,
                          'menu_class'     => 'new-menu',
                          'fallback_cb'    => 'wp_page_menu',
                      ]);
                  }
                ?>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header><!-- #masthead -->

    <div id="content" class="site-content">
    
    </div><!-- #content -->

<!-- JavaScript carregado via wp_enqueue_script no functions.php para melhor performance -->

<script>
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        const searchButton = document.querySelector('.search-button');
        const searchInput = document.querySelector('.search-input');
        
        if (searchButton && searchInput) {
            // Função para alternar a visibilidade do campo de pesquisa
            searchButton.addEventListener('click', function() {
                searchInput.classList.toggle('active');
                
                if (searchInput.classList.contains('active')) {
                    // Se o campo estiver visível, dê foco a ele
                    searchInput.focus();
                }
            });
            
            // Fechar o campo de pesquisa se clicar fora dele
            document.addEventListener('click', function(event) {
                const searchContainer = document.querySelector('.search-container');
                if (searchContainer) {
                    const isClickInsideSearchContainer = searchContainer.contains(event.target);
                    
                    if (!isClickInsideSearchContainer && searchInput.classList.contains('active')) {
                        searchInput.classList.remove('active');
                    }
                }
            });
            
            // Impedir que cliques dentro do container de pesquisa propaguem para o documento
            const searchContainer = document.querySelector('.search-container');
            if (searchContainer) {
                searchContainer.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            }
        }
    });
})();
</script>
