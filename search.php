<style>
/* Removido flex do .search-results para alinhar resultados à esquerda */
/* Centralizar a paginação */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 24px;
}
/* Paginação customizada */
.pagination .page-numbers {
    display: inline-block;
    min-width: 32px;
    height: 32px;
    line-height: 32px;
    text-align: center;
    border-radius: 8px;
    background: transparent;
    color: rgb(38, 85, 125);
    margin: 0 4px;
    font-weight: bold;
    transition: background 0.2s, color 0.2s;
}
.pagination .page-numbers.current {
    background: rgb(38, 85, 125);
    color: #fff;
}
.pagination .page-numbers:not(.prev):not(.next):not(.dots):not(.current):hover {
    background: rgb(38, 85, 125) !important;
    color: #fff !important;
}
</style>
<?php
/**
 * The template for displaying search results pages
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center mb-3">
                <!-- Hero -->
                <div class="col-lg-12">
                    <div class="display-5 fw-bold text-uenf-blue mb-3 hero-title">
                        <?php echo get_bloginfo('name'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-1">
        <?php cct_custom_breadcrumb(); ?>
    </div>

    <section class="line-breadcrumb"></section>


    <div class="container py-5">
        <div class="search-page">
            <?php if ( have_posts() ) : ?>
                <header class="search-header">
                    <div class="page-title">
                        <?php
                        /* translators: %s: search query. */
                        printf( esc_html__( 'Resultados para: %s', 'cct-theme' ), '<span class="search-term">' . get_search_query() . '</span>' );
                        ?>
                    </div>
                </header>

                <div class="search-results py-4">
                    <?php
                    /* Start the Loop */
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article class="search-result-item py-2">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="post-meta">
                                <span style="color: #666666;"><?php echo get_the_date(); ?></span>
                                <?php if (has_post_thumbnail()) : ?>
                                    <span class="post-thumbnail">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <div style="height:1px; background-color:#cccccc; margin:16px 0;"></div>

                        </article>
                    <?php
                    endwhile;
                    ?>
                </div>

                <?php
                if (function_exists('the_posts_pagination')) :
                    the_posts_pagination(array(
                        'prev_text' => __('Anterior', 'cct-theme'),
                        'next_text' => __('Próximo', 'cct-theme'),
                        'screen_reader_text' => __('Navegação de posts', 'cct-theme'),
                    ));
                endif;
                ?>

            <?php else : ?>
                <div class="no-results">
                    <h2><?php esc_html_e('Nenhum resultado encontrado', 'cct-theme'); ?></h2>
                    <p><?php esc_html_e('Desculpe, mas não encontramos resultados para sua busca. Tente novamente com termos diferentes.', 'cct-theme'); ?></p>
                    <form class="search-again" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <label for="search-again" class="screen-reader-text"><?php esc_html_e('Buscar novamente:', 'cct-theme'); ?></label>
                        <input type="search" id="search-again" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_attr_e('Digite sua busca...', 'cct-theme'); ?>">
                        <button type="submit" class="search-submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_sidebar();
get_footer();