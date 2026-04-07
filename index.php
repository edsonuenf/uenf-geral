<?php
/**
 * The main template file
 */

get_header();

// Categorias reais com posts (exclui variantes de "Sem categoria" e "Uncategorized")
$slugs_sem_categoria = ['sem-categoria', 'uncategorized'];
$todas_cats = get_categories(['orderby' => 'name', 'order' => 'ASC', 'hide_empty' => true]);
$cats_reais = array_filter($todas_cats, function ($cat) use ($slugs_sem_categoria) {
    foreach ($slugs_sem_categoria as $slug) {
        if (strpos($cat->slug, $slug) === 0) return false;
    }
    return true;
});
$exibir_filtro = count($cats_reais) >= 1;
?>

<main id="primary" class="site-main">

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center mb-3">
                <div class="col-lg-12">
                    <div class="display-5 fw-bold text-uenf-blue mb-3 hero-title">
                        <?php echo esc_html(get_bloginfo('name')); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <?php if ( is_home() && ! is_front_page() ) : ?>
            <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
        <?php endif; ?>

        <?php if ( $exibir_filtro ) : ?>
        <div class="uenf-posts-filter mb-4">
            <label for="uenf-cat-filter" class="uenf-filter-label me-2">Filtrar:</label>
            <select id="uenf-cat-filter" class="uenf-cat-select">
                <option value="all">Todos</option>
                <?php foreach ( $cats_reais as $cat ) : ?>
                    <option value="cat-<?php echo esc_attr($cat->term_id); ?>">
                        <?php echo esc_html($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>

            <?php
            // Pre-warm object cache para evitar N+1 queries de categorias
            if ( ! empty( $GLOBALS['wp_query']->posts ) ) {
                $post_ids = wp_list_pluck( $GLOBALS['wp_query']->posts, 'ID' );
                update_object_term_cache( $post_ids, 'post' );
            }
            ?>

            <div id="uenf-posts-list">
            <?php while ( have_posts() ) : the_post();
                $post_cats     = wp_get_post_categories(get_the_ID(), ['fields' => 'ids']);
                $data_cats_str = implode(' ', array_map(fn($id) => 'cat-' . $id, $post_cats));
                $excerpt_raw   = wp_strip_all_tags(get_the_content());
                $excerpt_short = mb_strlen($excerpt_raw) > 500
                    ? mb_substr($excerpt_raw, 0, 500)
                    : $excerpt_raw;
            ?>
                <article class="uenf-post-item" data-cats="<?php echo esc_attr($data_cats_str); ?>">
                    <span class="uenf-post-date"><i class="fas fa-calendar me-1" aria-hidden="true"></i><?php echo get_the_date('d \d\e F \d\e Y'); ?></span>
                    <h2 class="uenf-post-title">
                        <a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
                    </h2>
                    <?php if ($excerpt_short) : ?>
                        <p class="uenf-post-excerpt" data-excerpt="<?php echo esc_attr($excerpt_short); ?>"></p>
                    <?php endif; ?>
                    <div class="result-actions">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-outline-primary btn-sm read-more-btn">
                            <i class="fas fa-arrow-right me-1" aria-hidden="true"></i>Ler Mais
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
            </div>

            <p id="uenf-no-results" class="uenf-no-results" hidden>
                Nenhum post encontrado nesta categoria.
            </p>

            <?php the_posts_navigation(); ?>

        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>

    </div>
</main>

<style>
.uenf-posts-filter { display:flex; align-items:center; }
.uenf-filter-label { font-weight:600; font-size:.9rem; color:var(--bs-uenf-blue,#1d3771); }
.uenf-cat-select {
    font-size:.9rem;
    border:2px solid var(--bs-uenf-blue,#1d3771);
    border-radius:4px;
    padding:.3rem 2rem .3rem .6rem;
    color:var(--bs-uenf-blue,#1d3771);
    background:#fff;
    cursor:pointer;
}
.uenf-cat-select:focus { outline:none; box-shadow:0 0 0 3px rgba(29,55,113,.2); }

.uenf-post-item {
    background: #fff;
    border: 1px solid #e0e6f0;
    border-radius: 6px;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
}
.uenf-post-date { font-size:.78rem; color:#6c757d; text-transform:uppercase; letter-spacing:.04em; display:block; margin-bottom:.35rem; }
.uenf-post-title { font-size:32px; font-weight:700; line-height:51px; margin:0 0 .5rem; }
.uenf-post-title a { color:#26557d; text-decoration:none; }
.uenf-post-title a:hover { text-decoration:underline; }
.uenf-post-excerpt { font-size:.9rem; color:#495057; margin:0 0 1rem; line-height:1.6; }
.uenf-no-results { color:#6c757d; font-style:italic; }
</style>

<script>
(function () {
    // Excerpt responsivo: desktop 500 / tablet 400 / mobile 80
    function truncate(str, len) {
        return (str.length <= len ? str : str.substring(0, len).trimEnd()) + '...';
    }
    function getLimit() {
        var w = window.innerWidth;
        if (w <= 576)  return 80;   // celular
        if (w <= 992)  return 180;  // tablet
        if (w <= 1200) return 310;  // notebook
        return 500;                  // desktop
    }
    function applyExcerpts() {
        var limit = getLimit();
        document.querySelectorAll('.uenf-post-excerpt[data-excerpt]').forEach(function (p) {
            p.textContent = truncate(p.dataset.excerpt, limit);
        });
    }
    applyExcerpts();
    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(applyExcerpts, 100);
    });

    var select = document.getElementById('uenf-cat-filter');
    if (!select) return;
    var items     = Array.from(document.querySelectorAll('.uenf-post-item'));
    var noResults = document.getElementById('uenf-no-results');
    select.addEventListener('change', function () {
        var val = this.value;
        var visiveis = 0;
        items.forEach(function (item) {
            var cats = (item.dataset.cats || '').split(' ');
            var mostrar = val === 'all' || cats.includes(val);
            item.style.display = mostrar ? '' : 'none';
            if (mostrar) visiveis++;
        });
        if (noResults) {
            if (visiveis === 0) { noResults.removeAttribute('hidden'); }
            else { noResults.setAttribute('hidden', ''); }
        }
    });
})();
</script>

<?php
get_sidebar();
get_footer();
