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

</head>

<body <?php if ( function_exists( 'body_class' ) ) : body_class(); endif; ?>>
<?php if ( function_exists( 'wp_body_open' ) ) : ?>
<?php wp_body_open(); ?> <!-- Hook do WordPress para incluir conteúdo no início do body -->
<?php endif; ?>

<div id="page" class="site"> <!-- Contêiner principal do site -->
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'Skip to content', 'cct-theme' ); ?> <!-- Link para pular direto para o conteúdo principal, útil para acessibilidade -->
    </a>

    <header id="masthead" class="site-header"> <!-- Cabeçalho do site, contém branding e navegação -->


        <div class="container"> <!-- Contêiner para centralizar o conteúdo do cabeçalho -->
            <div class="site-branding"> <!-- Área de branding do site, exibe o logotipo ou nome do site -->
                <?php
                if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) :
                    the_custom_logo(); // Exibe o logotipo personalizado, se existir
                else :
                ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); // Exibe o nome do site ?>
                        </a>
                    </h1>
                    <?php
                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ) :
                    ?>
                        <p class="site-description">
                            <?php echo $description; // Exibe a descrição do site ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <nav id="site-navigation" class="main-navigation"> <!-- Navegação principal do site -->
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <?php esc_html_e( 'Menu', 'cct-theme' ); ?> <!-- Botão para alternar a visibilidade do menu em dispositivos móveis -->
                </button>
                <?php
                if ( function_exists( 'wp_nav_menu' ) ) :
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                        )
                    ); // Exibe o menu de navegação principal
                endif;
                ?>
            </nav>

            <div class="header-social" style="text-align: <?php echo esc_attr( get_theme_mod( 'social_media_alignment', 'right' ) ); ?>;"> <!-- Seção de ícones de redes sociais no cabeçalho -->
                <?php
                $social_networks = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'telegram', 'whatsapp' );

                foreach ( $social_networks as $network ) {
                    $link = get_theme_mod( "{$network}_link" );
                    $icon = get_theme_mod( "{$network}_icon", "fab fa-{$network}" );
                    if ( $link ) {
                        echo '<a href="' . esc_url( $link ) . '" target="_blank" class="social-icon ' . esc_attr( $network ) . '">';
                        echo '<i class="' . esc_attr( $icon ) . '"></i>'; // Exibe o ícone da rede social
                        echo '</a>';
                    }
                }
                ?>
            </div>
        </div>
    </header>

    <div id="content" class="site-content"> <!-- Início do conteúdo principal do site --> 