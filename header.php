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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()); ?>/css/styles.css">
    <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()); ?>/css/custom-fixes.css">
</head>
<body<?php
    $classes = get_body_class();
    // Remove a classe 'search-results' do array de classes
    $classes = array_diff($classes, array('search-results'));
    if (!empty($classes)) {
        echo ' class="' . esc_attr(implode(' ', $classes)) . '"';
    }
?>>
<?php if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} ?>
<div id="page" class="site">
    <!-- Header -->
    <header id="masthead" class="site-header">
      <div class="bg-header-logo">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <div class="logo">
                <a href="https://uenf.br" target="_blank">
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo-uenf-transparente.png" alt="UENF">
                </a>
              </div>
            </div>
            <div class="col-md-6 idiomas-bandeiras d-flex align-items-end justify-content-end">
                <?php dynamic_sidebar('idiomas-uenf'); ?>
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
            <div class="search-container">
              <?php dynamic_sidebar('ivory-search-uenf'); ?>
            </div>

            <div class="offcanvas offcanvas-start" id="menuLateral" tabindex="-1" aria-labelledby="menuLateralLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="menuLateralLabel">&nbsp;</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                  <?php
                    if (function_exists('custom_vertical_accordion_menu')) {
                        custom_vertical_accordion_menu();
                    } else {
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'container'      => false,
                                'menu_class'     => 'uenf-nav',
                                'fallback_cb'    => 'wp_page_menu',
                            )
                        );
                    }
                  ?>

                </ul>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header><!-- #masthead -->


    
    <div id="content" class="site-content">
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script>
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
    </script>




<script>
document.addEventListener("DOMContentLoaded", function() {
    // Seleciona todos os itens do menu que possuem submenus
    const menuItems = document.querySelectorAll('.uenf-nav li.menu-item-has-children > a, .uenf-nav li.page_item_has_children > a');
    
    // Adiciona evento de clique a cada item com submenu
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // Previne a navegação ao clicar no item pai
            
            const parent = this.parentElement;
            const submenu = parent.querySelector('ul.sub-menu, ul.children');
            
            if (submenu) {
                // Toggle da classe active no item pai
                parent.classList.toggle('active');
                
                // Abre ou fecha o submenu com animação
                if (submenu.classList.contains('open')) {
                    submenu.classList.remove('open');
                    setTimeout(() => {
                        submenu.style.display = 'none';
                    }, 300); // Tempo da animação
                } else {
                    submenu.style.display = 'block';
                    setTimeout(() => {
                        submenu.classList.add('open');
                    }, 10);
                }
                
                // Fecha outros submenus abertos (comportamento accordion)
                const siblings = Array.from(parent.parentElement.children)
                    .filter(child => child !== parent && (
                        child.classList.contains('menu-item-has-children') || 
                        child.classList.contains('page_item_has_children')
                    ));
                    
                siblings.forEach(sibling => {
                    sibling.classList.remove('active');
                    const siblingSubmenu = sibling.querySelector('ul.sub-menu, ul.children');
                    if (siblingSubmenu && siblingSubmenu.classList.contains('open')) {
                        siblingSubmenu.classList.remove('open');
                        setTimeout(() => {
                            siblingSubmenu.style.display = 'none';
                        }, 300);
                    }
                });
            }
        });
    });
    
    // Manter submenu aberto se estamos em uma subpágina
    const currentItems = document.querySelectorAll('.uenf-nav li.current-menu-item, .uenf-nav li.current_page_item');
    currentItems.forEach(currentItem => {
        const parentWithChildren = currentItem.closest('.menu-item-has-children, .page_item_has_children');
        if (parentWithChildren) {
            const submenu = parentWithChildren.querySelector('ul.sub-menu, ul.children');
            if (submenu) {
                parentWithChildren.classList.add('active');
                submenu.style.display = 'block';
                submenu.classList.add('open');
            }
        }
    });
    
    // Adicionar suporte a teclado para acessibilidade
    menuItems.forEach(item => {
        item.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click(); // Aciona o evento de clique
            }
        });
    });
});
</script>





