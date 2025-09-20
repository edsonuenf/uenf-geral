<?php
/**
 * Template for displaying the front page
 *
 * @package UENF_Geral
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Verifica se o usuário tem permissão para visualizar a página
/*if (!current_user_can('read')) {
    wp_die(esc_html__('Você não tem permissão para acessar esta página.', 'uenf-geral'));
}
*/
// Inicializa a saída em buffer
ob_start();

get_header();
?>

<main id="primary" class="site-main">
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center mb-3">
                <!-- Hero -->
                <div class="col-lg-12">
                    <h1 class="display-5 fw-bold text-uenf-blue mb-3 hero-title">
                        <?php 
                        // Segurança: Escapando a saída do nome do blog
                        $blog_name = get_bloginfo('name');
                        echo $blog_name ? esc_html($blog_name) : esc_html__('Site sem título', 'uenf-geral'); 
                        ?>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Conteúdo da Página Inicial -->
    <section class="page-content py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                    // Exibe o conteúdo da página inicial com segurança
                    if (have_posts()) :
                        while (have_posts()) :
                            the_post();
                            
                            // Use os filtros padrão do WordPress para o conteúdo
                            // Isso garante wpautop (parágrafos), shortcodes, embeds etc.
                            the_content();
                        endwhile;
                    else :
                        // Nenhum post encontrado
                        echo '<p class="text-center">' . esc_html__('Nenhum conteúdo encontrado.', 'uenf-geral') . '</p>';
                    endif;
                    
                    // Exemplo de formulário seguro
                    if (current_user_can('edit_posts') && apply_filters('uenf_show_example_form', false)) {
                        $nonce = wp_create_nonce('frontpage_form_nonce');
                        ?>
                        <form method="post" class="mt-4" id="uenf-example-form">
                            <?php wp_nonce_field('frontpage_form_action', 'frontpage_nonce'); ?>
                            <input type="hidden" name="frontpage_nonce" value="<?php echo esc_attr($nonce); ?>">
                            <input type="hidden" name="action" value="uenf_form_submit">
                            <!-- Campos do formulário aqui -->
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php
    // Verificação de segurança para seções adicionais
    $show_additional_sections = apply_filters('uenf_show_additional_sections', true);
    
    if ($show_additional_sections) {
        // Seções adicionais podem ser carregadas aqui
        do_action('uenf_after_main_content');
    }
    ?>
    
</main><!-- #main -->

<?php
// Limpa o buffer e exibe o conteúdo
$output = ob_get_clean();
echo $output; // Já devidamente escapado

get_footer();
?>
