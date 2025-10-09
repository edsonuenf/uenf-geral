<?php
/**
 * The template for displaying 404 pages (not found)
 * Design moderno e criativo seguindo a identidade visual UENF
 */

get_header();
?>

<style>
/* Estilos inline para página 404 - Design moderno e minimalista */
.error-404-modern {
    min-height: 80vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 2rem 0;
}

.error-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    width: 100%;
}

.error-header {
    text-align: center;
    margin-bottom: 3rem;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.error-number {
    font-size: 8rem;
    font-weight: 900;
    color: var(--bs-uenf-blue, #1d3771);
    line-height: 1;
    margin-bottom: 1rem;
    text-shadow: 0 4px 8px rgba(29, 55, 113, 0.1);
    text-align: center;
    width: 100%;
}

.error-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--bs-uenf-blue, #1d3771);
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
    text-align: center;
    width: 100%;
}

.error-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    margin: 0 auto 2rem auto;
    max-width: 600px;
    line-height: 1.6;
    text-align: center;
    width: 100%;
    box-sizing: border-box;
}

.error-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.action-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    border-color: var(--bs-uenf-blue, #1d3771);
}

.action-card h3 {
    color: var(--bs-uenf-blue, #1d3771);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.action-icon {
    width: 24px;
    height: 24px;
    background: var(--bs-uenf-blue, #1d3771);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
    font-weight: bold;
}

/* Estilos para busca na página 404 - usar estilos do Sistema de Busca */
.action-card .search-container {
    margin-bottom: 1rem;
    max-width: 100%;
}

.action-card .search-container .custom-search-form {
    display: flex;
    align-items: stretch; /* Garante que os elementos tenham a mesma altura */
    width: 100%;
}

.action-card .search-container .search-field {
    flex: 1;
    height: auto;
    min-height: 38px; /* Altura mínima consistente */
    box-sizing: border-box;
}

.action-card .search-container .search-submit {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    height: auto;
    min-height: 38px; /* Mesma altura mínima do campo */
    box-sizing: border-box;
    padding: 6px 12px; /* Mesmo padding do campo */
}

.home-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, var(--bs-uenf-blue, #1d3771) 0%, #2c5aa0 100%);
    color: white !important;
    text-decoration: none !important;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(29, 55, 113, 0.3);
}

.home-button:hover {
    color: white !important;
    text-decoration: none !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(29, 55, 113, 0.4);
}

.home-button:link,
.home-button:visited,
.home-button:active {
    color: #ffffff !important;
    text-decoration: none !important;
}

.home-button a:link {
    color: #ffffff !important;
}

.suggestions-section {
    margin: 3rem auto;
    width: 100%;
    max-width: 1200px;
}

.suggestions-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--bs-uenf-blue, #1d3771);
    margin-bottom: 1.5rem;
    text-align: center;
    width: 100%;
}

.suggestions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.suggestion-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.suggestion-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.suggestion-content {
    padding: 1.5rem;
}

.suggestion-content h4 {
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.suggestion-content h4 a {
    color: var(--bs-uenf-blue, #1d3771);
    text-decoration: none;
    transition: color 0.2s ease;
}

.suggestion-content h4 a:hover {
    color: #2c5aa0;
}

.suggestion-content p {
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.5;
    font-size: 0.95rem;
}

.suggestion-meta {
    font-size: 0.85rem;
    color: #adb5bd;
}

.encouragement-text {
    text-align: center;
    margin: 3rem auto 4rem auto;
    padding: 2rem;
    background: rgba(29, 55, 113, 0.05);
    border-radius: 12px;
    border-left: 4px solid var(--bs-uenf-blue, #1d3771);
    max-width: 800px;
    width: 100%;
    box-sizing: border-box;
}

.encouragement-text p {
    color: #6c757d;
    margin: 0;
    font-size: 1.1rem;
    line-height: 1.6;
    text-align: center;
}

/* Responsividade */
@media (max-width: 768px) {
    .error-number {
        font-size: 5rem;
    }
    
    .error-title {
        font-size: 2rem;
    }
    
    .error-container {
        padding: 0 1rem;
    }
    
    .action-card {
        padding: 1.5rem;
    }
    
    .action-card .custom-search-form {
        flex-direction: column;
        border-radius: 20px;
        padding: 5px;
    }
    
    .action-card .custom-search-form .search-field {
        padding: 15px 20px;
        text-align: center;
    }
    
    .action-card .custom-search-form .search-submit {
        margin-top: 10px;
        width: 100%;
        border-radius: 15px;
        padding: 12px 20px;
    }
    
    .suggestions-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .error-number {
        font-size: 4rem;
    }
    
    .error-title {
        font-size: 1.8rem;
    }
    
    .action-card {
        padding: 1.25rem;
    }
}
</style>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center mb-3">
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

    <section class="error-404-modern">
        <div class="error-container">
            <!-- Cabeçalho do Erro -->
            <header class="error-header">
                <div class="error-number" aria-hidden="true">404</div>
                <h1 class="error-title">Oops! Página não encontrada</h1>
                <p class="error-subtitle">
                    A página que você está procurando pode ter sido removida, teve seu nome alterado ou está temporariamente indisponível.
                </p>
            </header>

            <!-- Ações Principais -->
            <div class="error-actions">
                <!-- Campo de Busca -->
                <div class="action-card">
                    <h2>
                        <span class="action-icon" aria-hidden="true">🔍</span>
                        Buscar Conteúdo
                    </h2>
                    <p>Encontre o que você está procurando usando nossa busca interna:</p>
                    <div class="search-container search-custom-uenf">
                        <!-- Busca Normal -->
                        <form role="search" method="get" class="custom-search-form search-custom-uenf" action="<?php echo home_url('/'); ?>">
                            <label for="search-field-404" class="visually-hidden">Termo de busca</label>
                            <input type="search" id="search-field-404" class="search-field search-custom-uenf" placeholder="Buscar..." value="" name="s" aria-describedby="search-help-404" />
                            <button type="submit" class="search-submit search-custom-uenf" aria-label="Executar busca">
                                <i class="fas fa-search" aria-hidden="true"></i>
                                <span class="search-text">Buscar</span>
                            </button>
                        </form>
                        <div id="search-help-404" class="visually-hidden">Digite os termos que deseja buscar no site</div>
                    </div>
                </div>

                <!-- Botão Home -->
                <div class="action-card">
                    <h2>
                        <span class="action-icon" aria-hidden="true">🏠</span>
                        Voltar ao Início
                    </h2>
                    <p>Retorne à página inicial e explore nosso conteúdo:</p>
                    <a href="<?php echo home_url(); ?>" class="home-button">
                        <span>←</span>
                        Página Inicial
                    </a>
                </div>
            </div>

            <!-- Sugestões de Conteúdo -->
            <section class="suggestions-section" aria-labelledby="suggestions-title">
                <h2 id="suggestions-title" class="suggestions-title">Conteúdo que pode interessar</h2>
                <div class="suggestions-grid">
                    <?php
                    // Buscar 5 posts recentes
                    $recent_posts = new WP_Query(array(
                        'posts_per_page' => 5,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if ($recent_posts->have_posts()) :
                        while ($recent_posts->have_posts()) : $recent_posts->the_post();
                    ?>
                        <article class="suggestion-card">
                            <div class="suggestion-content">
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                <div class="suggestion-meta">
                                    <span class="date"><?php echo get_the_date('d/m/Y'); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        // Fallback para páginas estáticas
                        $pages = get_pages(array('number' => 5, 'sort_column' => 'menu_order'));
                        foreach ($pages as $page) :
                    ?>
                        <article class="suggestion-card">
                            <div class="suggestion-content">
                                <h4><a href="<?php echo get_permalink($page->ID); ?>"><?php echo $page->post_title; ?></a></h4>
                                <p><?php echo wp_trim_words($page->post_excerpt ?: $page->post_content, 20); ?></p>
                            </div>
                        </article>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </section>

            <!-- Texto de Incentivo -->
            <div class="encouragement-text">
                <p>
                    <strong>Não desista!</strong> Nosso site tem muito conteúdo interessante para explorar. 
                    Use a busca acima, navegue pelo menu principal ou explore as sugestões de conteúdo. 
                    Estamos aqui para ajudar você a encontrar exatamente o que precisa.
                </p>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();