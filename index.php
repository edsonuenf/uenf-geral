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
        <div class="posts-list-filter mb-4">
            <label for="posts-cat-filter" class="posts-list-filter-label">Filtrar:</label>
            <select id="posts-cat-filter" class="posts-list-cat-select">
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

            <div id="posts-list-container">
            <?php while ( have_posts() ) : the_post();
                $post_cats     = wp_get_post_categories(get_the_ID(), ['fields' => 'ids']);
                $data_cats_str = implode(' ', array_map(fn($id) => 'cat-' . $id, $post_cats));
                $excerpt_raw   = wp_strip_all_tags(get_the_content());
                $excerpt_short = mb_strlen($excerpt_raw) > 500
                    ? mb_substr($excerpt_raw, 0, 500)
                    : $excerpt_raw;
            ?>
                <article class="posts-list-item" data-cats="<?php echo esc_attr($data_cats_str); ?>">
                    <span class="posts-list-date">
                        <i class="fas fa-calendar me-1" aria-hidden="true"></i><?php echo get_the_date('d \d\e F \d\e Y'); ?>
                    </span>
                    <h2 class="posts-list-title">
                        <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                    </h2>
                    <?php if ($excerpt_short) : ?>
                        <p class="posts-list-excerpt" data-excerpt="<?php echo esc_attr($excerpt_short); ?>"></p>
                    <?php endif; ?>
                    <div class="posts-list-actions">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-right me-1" aria-hidden="true"></i>Ler Mais
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
            </div>

            <p id="posts-list-empty" class="posts-list-empty" hidden>
                Nenhum post encontrado nesta categoria.
            </p>

            <?php the_posts_navigation(); ?>

        <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>

    </div>
</main>

<script>
(function () {
    // Excerpt responsivo: desktop 500 / tablet 400 / mobile 80
    function truncate(str, len) {
        return (str.length <= len ? str : str.substring(0, len).trimEnd()) + '...';
    }
    function getLimit() {
        var w = window.innerWidth;
        if (w <= 576)  return 80;
        if (w <= 992)  return 180;
        if (w <= 1200) return 310;
        return 500;
    }
    function applyExcerpts() {
        var limit = getLimit();
        document.querySelectorAll('.posts-list-excerpt[data-excerpt]').forEach(function (p) {
            p.textContent = truncate(p.dataset.excerpt, limit);
        });
    }
    applyExcerpts();
    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(applyExcerpts, 100);
    });

    var select = document.getElementById('posts-cat-filter');
    if (!select) return;
    var items     = Array.from(document.querySelectorAll('.posts-list-item'));
    var noResults = document.getElementById('posts-list-empty');
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
