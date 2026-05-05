<?php
/**
 * Custom template tags for this theme
 */

if (!function_exists('uenf_posted_on')):
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function uenf_posted_on()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'uenf-theme'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('uenf_posted_by')):
    /**
     * Prints HTML with meta information for the current author.
     */
    function uenf_posted_by()
    {
        // Author info removed by request
    }
endif;

if (!function_exists('uenf_entry_footer')):
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function uenf_entry_footer()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'uenf-theme'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'uenf-theme') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'uenf-theme'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'uenf-theme') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'uenf-theme'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'uenf-theme'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if (!function_exists('uenf_post_thumbnail')):
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function uenf_post_thumbnail()
    {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()):
            ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div>
        <?php else: ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                    )
                );
                ?>
            </a>
            <?php
        endif;
    }
endif;
if ( ! function_exists( 'uenf_get_random_image' ) ) :
    /**
     * Helper to get random placeholder images for patterns
     */
    function uenf_get_random_image( $width = 1200, $height = 800 ) {
        if ( ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
            return '';
        }
        $width  = absint( $width )  ?: 1200;
        $height = absint( $height ) ?: 800;
        $colors = ['1d3771', '2c5aa0', '28a745', 'dc3545', 'e0a800'];
        $bg     = $colors[ array_rand( $colors ) ];
        return esc_url( "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image" );
    }
endif;
