<?php
/**
 * The template for displaying 404 pages (not found)
 * Design moderno e criativo seguindo a identidade visual UENF
 */

get_header();

// Inclui a classe do customizer 404
if (file_exists(get_template_directory() . '/inc/customizer/class-404-customizer.php')) {
    require_once get_template_directory() . '/inc/customizer/class-404-customizer.php';
}

// Carrega o CSS da página 404
$css_404_path = get_template_directory() . '/assets/css/404.css';
if (file_exists($css_404_path)) {
    $css_404_url = get_template_directory_uri() . '/assets/css/404.css';
    $css_404_version = filemtime($css_404_path);
    wp_enqueue_style('cct-404-style', $css_404_url, array('cct-style'), $css_404_version);
}
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
    grid-template-columns: 1fr;
    gap: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.suggestion-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    height: 100%;
    position: relative;
}

.suggestion-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    border: 2px solid var(--bs-uenf-blue, #1d3771);
    transition: all 0.3s ease;
}

.suggestion-thumbnail {
    flex: 0 0 200px;
    height: 150px;
    overflow: hidden;
    position: relative;
}

.suggestion-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.suggestion-card:hover .suggestion-thumbnail img {
    transform: scale(1.05);
}

.suggestion-content {
    flex: 1;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.suggestion-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    line-height: 1.4;
}

.suggestion-title a {
    color: #1d3771;
    text-decoration: none;
    transition: color 0.2s ease;
}

.suggestion-title a:hover {
    color: #2c5aa0;
    text-decoration: underline;
}

.suggestion-excerpt {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.suggestion-meta {
    font-size: 0.8rem;
    color: #868e96;
    margin-top: auto;
}

.suggestion-meta .date {
    display: inline-flex;
    align-items: center;
}

.suggestion-meta .date:before {
    content: "\f073";
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-right: 5px;
    font-size: 0.8em;
}

/* Responsividade */
@media (max-width: 768px) {
    .suggestion-card {
        flex-direction: column;
        height: auto;
    }
    
    .suggestion-thumbnail {
        flex: 0 0 100%;
        height: 180px;
    }
    
    .suggestion-content {
        padding: 1rem;
    }
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
                <h1 class="error-title"><?php echo esc_html(get_theme_mod('cct_404_title', 'Oops! Página não encontrada')); ?></h1>
                <p class="error-subtitle"><?php echo wp_kses_post(get_theme_mod('cct_404_subtitle', 'A página que você está procurando pode ter sido removida, ter mudado de nome ou está temporariamente indisponível.')); ?></p>
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

            <!-- Conteúdo que pode interessar -->
            <?php  
            // Verifica se a classe CCT_404_Customizer existe
            if (class_exists('CCT_404_Customizer')) {
                error_log('Classe CCT_404_Customizer encontrada');

                // Verifica se a função get_related_content existe na classe
                if (method_exists('CCT_404_Customizer', 'get_related_content')) {
                    error_log('Método get_related_content encontrado');
                    
                    // Obtém a consulta de posts relacionados
                    $related_query = CCT_404_Customizer::get_related_content();
                    
                    // Log da consulta
                    error_log('Tipo de retorno: ' . get_class($related_query));
                    error_log('Número de posts encontrados: ' . $related_query->found_posts);
                    error_log('Número de posts na consulta: ' . $related_query->post_count);
                    
                    // Verifica se a consulta retornou resultados
                    if ($related_query->have_posts()) {
                        error_log('A consulta retornou posts');
                        // Obtém o título da seção
                        $related_title = get_theme_mod('cct_404_related_title', 'Conteúdo que pode interessar');
                        ?>
                        <section class="related-content-section py-5">
                            <div class="container">
                                <h2 class="section-title mb-4"><?php echo esc_html($related_title); ?></h2>
                                <div class="suggestions-grid">
                                    <?php 
                                    while ($related_query->have_posts()) : 
                                        $related_query->the_post(); 
                                        ?>
                                        <article class="suggestion-card">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <a href="<?php the_permalink(); ?>" class="suggestion-thumbnail">
                                                    <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                                </a>
                                            <?php endif; ?>
                                            <div class="suggestion-content">
                                                <h3 class="suggestion-title">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h3>
                                                <div class="suggestion-excerpt">
                                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                                </div>
                                                <div class="suggestion-meta">
                                                    <span class="date">
                                                        <i class="far fa-calendar-alt"></i> 
                                                        <?php echo get_the_date('d/m/Y'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </article>
                                    <?php 
                                    endwhile; 
                                    wp_reset_postdata(); 
                                    ?>
                                </div>
                            </div>
                        </section>
                        <?php
                    } else {
                        error_log('Nenhum post relacionado encontrado.');
                    }
                } else {
                    error_log('O método get_related_content não existe na classe CCT_404_Customizer');
                }
            } else {
                error_log('A classe CCT_404_Customizer não foi encontrada');
            }
            ?>

            <!-- Texto de Incentivo -->
            <div class="encouragement-text text-center py-4">
                <p class="mb-0">
                    Não desista! Nosso site tem muito conteúdo interessante para explorar. Use a busca acima, navegue pelo menu principal ou explore as sugestões de conteúdo. Estamos aqui para ajudar você a encontrar exatamente o que precisa.
                </p>
            </div>

        </div>
    </section>
</main>

<?php
get_footer();