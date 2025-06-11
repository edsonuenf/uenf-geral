<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
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

    <section class="error-404 not-found">
            <div class="error-visual">
                <h1 class="page-title">Página não encontrada</h1>
                <p class="lead">Desculpe, mas a página que você está procurando não existe ou foi movida.</p>
            </div>

            <div class="search-section">
                <h2 class="section-title">O que você está procurando?</h2>
                <div class="search-container">
                    <?php get_search_form(); ?>
                </div>
            </div>

    </section>
</main>

<?php
get_footer(); 