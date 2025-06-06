<?php
/**
 * SEO optimization functions
 */

// Add meta description
function cct_meta_description() {
    if ( is_singular() ) {
        global $post;
        $meta_description = get_post_meta( $post->ID, '_meta_description', true );
        
        if ( empty( $meta_description ) ) {
            $meta_description = wp_trim_words( strip_tags( $post->post_content ), 25 );
        }
    } else {
        $meta_description = get_theme_mod( 'meta_description', get_bloginfo( 'description' ) );
    }

    if ( !empty( $meta_description ) ) {
        echo '<meta name="description" content="' . esc_attr( $meta_description ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'cct_meta_description' );

// Add Open Graph meta tags
function cct_og_meta_tags() {
    global $post;

    if ( is_singular() ) {
        $og_title = get_the_title();
        $og_description = wp_trim_words( strip_tags( $post->post_content ), 25 );
        $og_url = get_permalink();
        $og_type = is_single() ? 'article' : 'website';
        
        if ( has_post_thumbnail() ) {
            $og_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
            $og_image = $og_image[0];
        } else {
            $og_image = get_theme_mod( 'default_og_image' );
        }

        echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $og_description ) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $og_url ) . '" />' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
        
        if ( !empty( $og_image ) ) {
            echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'cct_og_meta_tags' );

// Add Twitter Card meta tags
function cct_twitter_card_tags() {
    global $post;

    if ( is_singular() ) {
        $twitter_title = get_the_title();
        $twitter_description = wp_trim_words( strip_tags( $post->post_content ), 25 );
        
        if ( has_post_thumbnail() ) {
            $twitter_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
            $twitter_image = $twitter_image[0];
        } else {
            $twitter_image = get_theme_mod( 'default_twitter_image' );
        }

        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $twitter_title ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $twitter_description ) . '" />' . "\n";
        
        if ( !empty( $twitter_image ) ) {
            echo '<meta name="twitter:image" content="' . esc_url( $twitter_image ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'cct_twitter_card_tags' );

// Add schema.org markup
function cct_schema_org_markup() {
    if ( is_singular( 'post' ) ) {
        global $post;
        
        $schema = array(
            '@context' => 'http://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'description' => wp_trim_words( strip_tags( $post->post_content ), 25 ),
            'datePublished' => get_the_date( 'c' ),
            'dateModified' => get_the_modified_date( 'c' ),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo( 'name' ),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_theme_mod( 'custom_logo' )
                )
            )
        );

        if ( has_post_thumbnail() ) {
            $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image[0],
                'width' => $image[1],
                'height' => $image[2]
            );
        }

        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'cct_schema_org_markup' );

// Add canonical URL
function cct_canonical_url() {
    if ( is_singular() ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'cct_canonical_url' );

// Add meta robots
function cct_meta_robots() {
    if ( is_singular() ) {
        $robots = get_post_meta( get_the_ID(), '_meta_robots', true );
        if ( !empty( $robots ) ) {
            echo '<meta name="robots" content="' . esc_attr( $robots ) . '" />' . "\n";
        }
    }
}
add_action( 'wp_head', 'cct_meta_robots' ); 