<?php
/**
 * The template for displaying all pages
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
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header mb-4">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php 
                            the_content();

                            wp_link_pages(
                                array(
                                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cct-theme' ),
                                    'after'  => '</div>',
                                )
                            );
                            ?>
                        </div>

                        <?php if ( get_edit_post_link() ) : ?>
                            <footer class="entry-footer mt-4">
                                <?php
                                edit_post_link(
                                    sprintf(
                                        wp_kses(
                                            /* translators: %s: Name of current post. Only visible to screen readers */
                                            __( 'Edit <span class="screen-reader-text">%s</span>', 'cct-theme' ),
                                            array(
                                                'span' => array(
                                                    'class' => array(),
                                                ),
                                            )
                                        ),
                                        wp_kses_post( get_the_title() )
                                    ),
                                    '<span class="edit-link">',
                                    '</span>'
                                );
                                ?>
                            </footer>
                        <?php endif; ?>
                    </article>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    // Comentários desativados por padrão no tema

                endwhile; // End of the loop.
                ?>
            </div>
        </div>
    </div>
</main>

<?php
get_sidebar();
get_footer();