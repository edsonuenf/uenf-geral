<?php
/**
 * Template para exibir resultados de busca
 * Versão moderna com UX/UI aprimorada
 */

get_header();

// Obter estatísticas de busca
$search_query = get_search_query();
$total_results = $wp_query->found_posts;
$current_page = max(1, get_query_var('paged'));
$results_per_page = get_option('posts_per_page');
$start_result = (($current_page - 1) * $results_per_page) + 1;
$end_result = min($current_page * $results_per_page, $total_results);
?>

<main id="primary" class="site-main search-page-modern">
    <!-- Hero Section Padrão -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center mb-3">
                <div class="col-lg-12">
                    <h1 class="display-5 fw-bold text-uenf-blue mb-3 hero-title">
                        <?php echo get_bloginfo('name'); ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="container">
        <?php cct_custom_breadcrumb(); ?>
    </div>
    <section class="line-breadcrumb"></section>

    <!-- Conteúdo Principal -->
    <div class="container py-5">
        <?php if (have_posts()) : ?>
            <!-- Informação da Busca -->
            <?php if ($search_query) : ?>
                <div class="search-query-info mb-4">
                    <p class="lead mb-0">
                        Você pesquisou por: <strong><?php echo esc_html($search_query); ?></strong>
                    </p>
                </div>
            <?php endif; ?>
            
            <!-- Estatísticas e Filtros -->
            <div class="search-stats-section mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="search-stats">
                            <div class="results-count">
                                <strong><?php echo number_format_i18n($total_results); ?></strong> 
                                <?php echo $total_results == 1 ? 'resultado encontrado' : 'resultados encontrados'; ?>
                            </div>
                            <?php if ($total_results > 0) : ?>
                                <div class="results-range text-muted">
                                    Mostrando <?php echo $start_result; ?>-<?php echo $end_result; ?> de <?php echo number_format_i18n($total_results); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="search-actions">
                            <!-- Nova Busca -->
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#newSearchForm">
                                <i class="fas fa-search me-2"></i>Nova Busca
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Formulário de Nova Busca (Colapsável) -->
                <div class="collapse mt-3" id="newSearchForm">
                    <div class="card card-body">
                        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="row g-3">
                            <div class="col-md-8">
                                <input type="search" class="form-control" name="s" value="<?php echo esc_attr($search_query); ?>" placeholder="Digite sua busca..." />
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Resultados da Busca -->
            <div class="search-results-grid">
                <div class="row">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-12 mb-4">
                            <article class="search-result-card">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <div class="row">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="col-md-3">
                                                    <div class="result-thumbnail">
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php the_post_thumbnail('medium', array('class' => 'img-fluid rounded')); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                            <?php else : ?>
                                                <div class="col-12">
                                            <?php endif; ?>
                                                <div class="result-content">
                                                    <div class="result-meta mb-2">
                                                        <span class="badge bg-primary me-2"><?php echo get_post_type_object(get_post_type())->labels->singular_name; ?></span>
                                                        <span class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            <?php echo get_the_date(); ?>
                                                        </span>
                                                        <?php if (get_the_author()) : ?>
                                                            <span class="text-muted ms-3">
                                                                <i class="fas fa-user me-1"></i>
                                                                <?php the_author(); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <h3 class="result-title">
                                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                                            <?php the_title(); ?>
                                                        </a>
                                                    </h3>
                                                    
                                                    <div class="result-excerpt text-muted mb-3">
                                                        <?php 
                                                        $excerpt = get_the_excerpt();
                                                        if ($search_query) {
                                                            $excerpt = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark>$1</mark>', $excerpt);
                                                        }
                                                        echo $excerpt;
                                                        ?>
                                                    </div>
                                                    
                                                    <div class="result-actions">
                                                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-arrow-right me-1"></i>
                                                            Ler mais
                                                        </a>
                                                        <?php if (get_permalink()) : ?>
                                                            <button class="btn btn-outline-secondary btn-sm ms-2" onclick="navigator.clipboard.writeText('<?php echo get_permalink(); ?>')" title="Copiar link">
                                                                <i class="fas fa-link"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Paginação Moderna -->
            <div class="search-pagination-wrapper mt-5">
                <?php
                the_posts_pagination(array(
                    'prev_text' => '<i class="fas fa-chevron-left me-2"></i>Anterior',
                    'next_text' => 'Próximo<i class="fas fa-chevron-right ms-2"></i>',
                    'screen_reader_text' => 'Navegação de resultados',
                    'class' => 'search-pagination'
                ));
                ?>
            </div>

        <?php else : ?>
            <!-- Estado Vazio -->
            <div class="no-results-section">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <div class="no-results-content">
                            <div class="no-results-icon mb-4">
                                <i class="fas fa-search-minus fa-5x text-muted"></i>
                            </div>
                            <h2 class="no-results-title mb-3">Nenhum resultado encontrado</h2>
                            <p class="no-results-text text-muted mb-4">
                                Não encontramos resultados para <strong>"<?php echo esc_html($search_query); ?>"</strong>.<br>
                                Tente refinar sua busca ou usar termos diferentes.
                            </p>
                            
                            <!-- Sugestões -->
                            <div class="search-suggestions mb-4">
                                <h5>Sugestões:</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Verifique a ortografia das palavras</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Use termos mais gerais</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Tente sinônimos</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Reduza o número de palavras</li>
                                </ul>
                            </div>
                            
                            <!-- Nova Busca -->
                            <div class="new-search-form">
                                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="row g-3 justify-content-center">
                                    <div class="col-md-6">
                                        <input type="search" class="form-control form-control-lg" name="s" value="" placeholder="Tente uma nova busca..." />
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-search me-2"></i>Buscar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();