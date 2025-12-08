<?php
/**
 * The template for displaying all single posts
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
                while (have_posts()):
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header mb-4">
                            <h1 class="entry-title"><?php the_title(); ?></h1>

                            <?php if ('post' === get_post_type()): ?>
                                <div class="entry-meta mb-3">
                                    <span class="posted-on">Publicado em <?php echo get_the_date(); ?></span>
                                </div>
                            <?php endif; ?>
                        </header>

                        <?php if (has_post_thumbnail()): ?>
                            <div class="post-thumbnail mb-4">
                                <?php the_post_thumbnail('uenf-large', ['class' => 'img-fluid']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php the_content(); ?>

                            <?php
                            wp_link_pages(
                                array(
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'cct-theme'),
                                    'after' => '</div>',
                                )
                            );
                            ?>
                        </div>

                        <footer class="entry-footer mt-4">
                            <?php
                            // Display categories and tags
                            //$categories_list = get_the_category_list(', ');
                            //if ($categories_list) {
                            //    echo '<div class="cat-links mb-2">Categorias: ' . $categories_list . '</div>';
                            //}
                        
                            //$tags_list = get_the_tag_list('', ', ');
                            //if ($tags_list) {
                            //    echo '<div class="tags-links">Tags: ' . $tags_list . '</div>';
                            //}
                            ?>
                        </footer>
                    </article>

                    <div class="post-navigation my-5">
                        <?php
                        the_post_navigation(
                            array(
                                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Anterior:', 'cct-theme') . '</span> <span class="nav-title">%title</span>',
                                'next_text' => '<span class="nav-subtitle">' . esc_html__('Próximo:', 'cct-theme') . '</span> <span class="nav-title">%title</span>',
                            )
                        );
                        ?>
                    </div>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    //if ( comments_open() || get_comments_number() ) :
                    //    comments_template();
                    //endif;
                
                endwhile; // End of the loop.
                ?>
            </div>
        </div>
    </div>
</main>

<?php
get_sidebar();
get_footer();