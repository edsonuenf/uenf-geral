<?php
/**
 * Template part for displaying posts
 */


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta">
                <?php
                cct_posted_on();
                cct_posted_by();
                ?>
            </div>
        <?php endif; ?>
    </header>

    <?php cct_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        if ( is_singular() ) :
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'cct-theme' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                )
            );

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cct-theme' ),
                    'after'  => '</div>',
                )
            );
        else :
            the_excerpt();
            ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="read-more">
                <?php esc_html_e( 'Read More', 'cct-theme' ); ?>
            </a>
        <?php endif; ?>
    </div>

    <footer class="entry-footer">
        <?php cct_entry_footer(); ?>
    </footer>
</article> 