<!DOCTYPE html>
<?php if ( function_exists( 'language_attributes' ) ) : ?>
<html <?php language_attributes(); ?>>
<?php endif; ?>
<head>
    <!-- Cabeçalho do documento HTML, inclui metadados e links para recursos externos -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
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
          <div class="row align-items-center">
            <div class="col-9 col-md-4">
              <div class="logo">
                <a href="https://uenf.br" target="_blank" rel="noopener noreferrer">
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo-uenf-transparente.png" alt="Logo da Universidade Estadual do Norte Fluminense Darcy Ribeiro">
                </a>
              </div>
            </div>
            <div class="col-3 d-md-none d-flex justify-content-end align-items-center gap-1">
              <?php
              $search_extension_active = get_theme_mod('uenf_extension_search_customizer_enabled', false);
              if ($search_extension_active) : ?>
              <button class="mobile-search-toggle" type="button" aria-label="Abrir busca" aria-expanded="false" aria-controls="mobile-search-bar">
                <i class="fas fa-search" aria-hidden="true"></i>
              </button>
              <?php endif; ?>
              <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral" aria-controls="menuLateral" aria-label="Toggle navigation" style="border: none; background: transparent; padding: 0;">
                <span class="navbar-toggler-icon"></span>
              </button>
            </div>

            <?php if ($search_extension_active ?? false) : ?>
            <div id="mobile-search-bar" class="mobile-search-bar" hidden>
              <div class="mobile-search-inner">
                <?php get_search_form(); ?>
              </div>
            </div>
            <?php endif; ?>
            <div class="col-md-8 header-media-grid">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-home-link" aria-label="Ir para a página inicial">
                    <i class="fas fa-home" aria-hidden="true"></i>
                </a>
                <button id="header-shortcut-btn" class="header-shortcut-btn" aria-label="Atalhos rápidos" title="Atalhos Rápidos">
                    <i class="fas fa-bolt" aria-hidden="true"></i>
                </button>
                <div class="idiomas-bandeiras">
                    <?php dynamic_sidebar('idiomas-uenf'); ?>
                </div>
                <?php if ( uenf_has_social_media() ) : ?>
                <div class="social-media">
                    <?php uenf_display_social_media(); ?>
                </div>
                <?php endif; ?>
                <!-- Botão de redes sociais — visível apenas em mobile (substitui os ícones individuais) -->
                <div class="social-share-wrapper">
                    <button id="header-social-btn" class="header-social-btn" aria-label="Redes sociais" aria-expanded="false" title="Redes Sociais">
                        <i class="fas fa-share-alt" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Navbar Superior com Logo -->
      <nav class="navbar navbar-dark navbar-uenf">
        <div class="container header-grid-container">
          <div class="header-grid-col1 d-none d-md-block">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateral" aria-controls="menuLateral" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </div>

          <div class="header-grid-col2">
            <div class="search-container search-custom-uenf">
              <?php 
              // Verificar se a extensão de busca personalizada está ativa
              $search_extension_active = get_theme_mod('uenf_extension_search_customizer_enabled', false);
              if ($search_extension_active) {
                  get_search_form();
              }
              ?>
            </div>

          </div>
        </div>
      </nav>
      <div class="offcanvas offcanvas-start" id="menuLateral" tabindex="-1" aria-labelledby="menuLateralLabel">
        <div class="offcanvas-header">
          <div class="offcanvas-logo" id="menuLateralLabel">
            <?php if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) : ?>
              <?php the_custom_logo(); ?>
            <?php else : ?>
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/logo-uenf-transparente.png"
                     alt="<?php bloginfo( 'name' ); ?>">
              </a>
            <?php endif; ?>
          </div>
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
    </header><!-- #masthead -->

    <div id="content" class="site-content">
    
    </div><!-- #content -->

<!-- JavaScript carregado via wp_enqueue_script no functions.php para melhor performance -->

<script>
(function() {
    'use strict';

    // Dropdown de idiomas — somente mobile
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth > 767.98) return;

        var wrapper = document.querySelector('.header-media-grid .idiomas-bandeiras');
        if (!wrapper) return;

        // Coleta todos os links de idioma da lista original
        var links = Array.from(wrapper.querySelectorAll('a'));
        if (links.length === 0) return;

        // Cria o botão trigger com a primeira flag
        var firstImg = links[0].querySelector('img');
        var trigger = document.createElement('button');
        trigger.className = 'lang-dropdown-trigger';
        trigger.setAttribute('aria-expanded', 'false');
        trigger.setAttribute('aria-label', 'Selecionar idioma');
        if (firstImg) {
            var triggerImg = firstImg.cloneNode(true);
            trigger.appendChild(triggerImg);
        }

        // Cria o painel com todos os idiomas
        var panel = document.createElement('div');
        panel.className = 'lang-dropdown-panel';
        panel.setAttribute('role', 'menu');
        panel.setAttribute('aria-hidden', 'true');
        links.forEach(function(a) {
            var item = a.cloneNode(true);
            item.setAttribute('role', 'menuitem');
            // Garante que o texto do idioma aparece ao lado da flag
            if (!item.textContent.trim() && item.querySelector('img')) {
                item.insertAdjacentText('beforeend', ' ' + (item.querySelector('img').alt || ''));
            }
            panel.appendChild(item);
        });

        wrapper.appendChild(trigger);
        wrapper.appendChild(panel);

        function openLangPanel() {
            wrapper.classList.add('open');
            trigger.setAttribute('aria-expanded', 'true');
            panel.setAttribute('aria-hidden', 'false');
            var first = panel.querySelector('a, button');
            if (first) { first.focus(); }
        }

        function closeLangPanel() {
            wrapper.classList.remove('open');
            trigger.setAttribute('aria-expanded', 'false');
            panel.setAttribute('aria-hidden', 'true');
        }

        // Toggle
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.classList.contains('open') ? closeLangPanel() : openLangPanel();
        });

        // Fechar com Escape
        panel.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeLangPanel(); trigger.focus(); }
        });

        // Fecha ao clicar fora
        document.addEventListener('click', function() { closeLangPanel(); });
    });

    // Botão atalhos na barra inferior mobile
    // Controla o painel diretamente (sem delegar ao .shortcut-icon que tem 2 handlers conflitantes)
    document.addEventListener('DOMContentLoaded', function() {
        var container = document.getElementById('uenf-shortcut-panel-container');
        if (!container) return;

        function closeShortcutPanel() {
            container.classList.remove('expanded');
            var p = container.querySelector('.shortcut-panel');
            var i = container.querySelector('.shortcut-icon');
            if (p) p.classList.remove('active');
            if (i) i.classList.remove('active');
            document.body.style.overflow = '';
        }

        function openShortcutPanel() {
            container.classList.add('expanded');
            var p = container.querySelector('.shortcut-panel');
            var i = container.querySelector('.shortcut-icon');
            if (p) p.classList.add('active');
            if (i) i.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Botão gear na barra inferior
        var btn = document.getElementById('header-shortcut-btn');
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                container.classList.contains('expanded') ? closeShortcutPanel() : openShortcutPanel();
            });
        }

        // Botão X (fechar) dentro do painel
        var closeBtn = container.querySelector('.close-panel');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeShortcutPanel();
            });
        }

        // Clicar em qualquer item do painel: fechar e navegar
        container.querySelectorAll('.shortcut-item').forEach(function(item) {
            item.addEventListener('click', function(e) {
                var href = item.getAttribute('href');
                closeShortcutPanel();
                // Scroll suave para âncoras internas
                if (href && href.charAt(0) === '#' && href.length > 1) {
                    e.preventDefault();
                    var target = document.querySelector(href) || document.getElementById(href.slice(1));
                    if (target) {
                        setTimeout(function() {
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 300); // espera o painel fechar
                    }
                }
            });
        });

        // Fechar ao clicar fora do painel (em mobile)
        document.addEventListener('click', function(e) {
            if (!container.classList.contains('expanded')) return;
            if (e.target.closest('.shortcut-panel') || e.target.closest('#header-shortcut-btn')) return;
            closeShortcutPanel();
        });

        // Fechar com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && container.classList.contains('expanded')) {
                closeShortcutPanel();
            }
        });
    });

    // Dropdown de redes sociais — barra inferior mobile
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth > 767.98) return;

        var btn     = document.getElementById('header-social-btn');
        var wrapper = btn ? btn.closest('.social-share-wrapper') : null;
        if (!btn || !wrapper) return;

        // Coleta links do .social-media (ocultado via CSS no mobile, mas permanece no DOM)
        var socialLinks = Array.from(document.querySelectorAll(
            '.header-media-grid .social-media .social-link'
        ));
        if (socialLinks.length === 0) {
            btn.style.display = 'none';
            return;
        }

        // Constrói o painel dropdown
        var panel = document.createElement('div');
        panel.className = 'social-dropdown-panel';
        panel.setAttribute('role', 'menu');

        socialLinks.forEach(function(link) {
            var item = document.createElement('a');
            item.href = link.href;
            item.target = '_blank';
            item.rel = 'noopener noreferrer';
            item.setAttribute('role', 'menuitem');

            var icon = link.querySelector('i');
            if (icon) { item.appendChild(icon.cloneNode(true)); }

            var name = link.title ||
                (link.querySelector('.screen-reader-text')
                    ? link.querySelector('.screen-reader-text').textContent.trim()
                    : '');
            if (name) {
                var span = document.createElement('span');
                span.textContent = name;
                item.appendChild(span);
            }
            panel.appendChild(item);
        });

        panel.setAttribute('aria-hidden', 'true');
        wrapper.appendChild(panel);

        function openSocialPanel() {
            wrapper.classList.add('open');
            btn.setAttribute('aria-expanded', 'true');
            panel.setAttribute('aria-hidden', 'false');
            var first = panel.querySelector('a, button');
            if (first) { first.focus(); }
        }

        function closeSocialPanel() {
            wrapper.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
            panel.setAttribute('aria-hidden', 'true');
        }

        // Toggle ao clicar
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.classList.contains('open') ? closeSocialPanel() : openSocialPanel();
        });

        // Fechar com Escape
        panel.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeSocialPanel(); btn.focus(); }
        });

        // Fecha ao clicar fora
        document.addEventListener('click', function() { closeSocialPanel(); });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var toggle = document.querySelector('.mobile-search-toggle');
        var bar    = document.getElementById('mobile-search-bar');
        if (!toggle || !bar) return;

        toggle.addEventListener('click', function() {
            var isOpen = !bar.hidden;
            bar.hidden = isOpen;
            toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            if (!isOpen) {
                var input = bar.querySelector('input[type="search"]');
                if (input) { input.focus(); }
            }
        });

        // Fechar ao clicar fora
        document.addEventListener('click', function(e) {
            if (!bar.hidden && !bar.contains(e.target) && !toggle.contains(e.target)) {
                bar.hidden = true;
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    });
})();
</script>
