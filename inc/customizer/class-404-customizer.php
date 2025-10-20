<?php
/**
 * Seção do Customizer: Página 404
 *
 * Adiciona controles para personalizar a exibição da página 404
 *
 * @package CCT_Theme
 * @subpackage Customizer
 * @since 1.0.0
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Classe para controle de seleção múltipla no Customizer
if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Control_Multiple_Select extends WP_Customize_Control {
        /**
         * O tipo de controle, usado no JS.
         */
        public $type = 'multiple-select';

        /**
         * Renderiza o conteúdo do controle.
         */
        public function render_content() {
            if (empty($this->choices)) {
                return;
            }
            ?>
            <label>
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
                <select multiple="multiple" <?php $this->link(); ?> style="height: auto;">
                    <?php foreach ($this->choices as $value => $label) : ?>
                        <?php $selected = (in_array($value, (array)$this->value())) ? 'selected="selected"' : ''; ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php echo $selected; ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php
        }
    }

}

/**
 * Classe principal do Customizer para a página 404
 */
class CCT_404_Customizer {
        /**
         * Inicializar controles
         */
        public static function init() {
            add_action('customize_register', array(__CLASS__, 'register_controls'));
        }
        
        /**
         * Sanitiza os valores de seleção múltipla
         *
         * @param mixed $input Valor a ser sanitizado
         * @return array Array de valores sanitizados
         */
        public static function sanitize_multiselect($input) {
            // Verifica se o input é um array
            if (!is_array($input)) {
                $input = explode(',', $input);
            }
            
            // Remove valores vazios e sanitiza cada item
            $input = array_filter(array_map('sanitize_text_field', (array) $input));
            
            // Retorna um array vazio se não houver itens válidos
            return !empty($input) ? $input : array();
        }

        /**
         * Registrar controles no Customizer
         */
        public static function register_controls($wp_customize) {
            // Painel de Configurações da Página 404
            $wp_customize->add_section('cct_404_page', array(
                'title'       => 'Página 404',
                'description' => 'Configure o conteúdo exibido na página de erro 404.',
                'priority'    => 35,
            ));

            // Título da Página 404
            $wp_customize->add_setting('cct_404_title', array(
                'default'           => 'Oops! Página não encontrada',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control('cct_404_title', array(
                'label'       => 'Título da Página 404',
                'section'     => 'cct_404_page',
                'type'        => 'text',
            ));

            // Subtítulo da Página 404
            $wp_customize->add_setting('cct_404_subtitle', array(
                'default'           => 'A página que você está procurando pode ter sido removida, ter mudado de nome ou está temporariamente indisponível.',
                'sanitize_callback' => 'wp_kses_post',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control('cct_404_subtitle', array(
                'label'       => 'Subtítulo',
                'section'     => 'cct_404_page',
                'type'        => 'textarea',
            ));

            // Seção: Conteúdo que pode interessar
            $wp_customize->add_setting('cct_404_related_heading', array(
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'cct_404_related_heading', array(
                'label'       => 'Conteúdo que pode interessar',
                'description' => 'Configure os conteúdos relacionados que aparecem na página 404.',
                'section'     => 'cct_404_page',
                'type'        => 'hidden',
                'settings'    => 'cct_404_related_heading',
            )));

            // Ativar conteúdo relacionado
            $wp_customize->add_setting('cct_404_related_enabled', array(
                'default'           => true,
                'sanitize_callback' => 'wp_validate_boolean',
                'transport'         => 'refresh',
            ));
            $wp_customize->add_control('cct_404_related_enabled', array(
                'label'       => 'Exibir "Conteúdo que pode interessar"',
                'description' => 'Ativa a exibição de conteúdos relacionados na página 404.',
                'section'     => 'cct_404_page',
                'type'        => 'checkbox',
            ));

            // Título da seção de conteúdo relacionado
            $wp_customize->add_setting('cct_404_related_title', array(
                'default'           => 'Conteúdo que pode interessar',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            ));
            $wp_customize->add_control('cct_404_related_title', array(
                'label'       => 'Título da seção de conteúdo relacionado',
                'description' => 'Título exibido acima dos conteúdos relacionados.',
                'section'     => 'cct_404_page',
                'type'        => 'text',
            ));

            // Tipos de conteúdo para exibir
            $post_types = get_post_types(array('public' => true), 'objects');
            $post_type_choices = array();
            
            foreach ($post_types as $post_type) {
                if ($post_type->name !== 'attachment') {
                    $post_type_choices[$post_type->name] = $post_type->labels->name;
                }
            }

            $wp_customize->add_setting('cct_404_related_post_types', array(
                'default'           => array('post', 'page'),
                'sanitize_callback' => array(__CLASS__, 'sanitize_multiselect'),
                'transport'         => 'refresh',
            ));
            $wp_customize->add_control(new WP_Customize_Control_Multiple_Select(
                $wp_customize,
                'cct_404_related_post_types',
                array(
                    'label'       => 'Tipos de conteúdo',
                    'description' => 'Selecione os tipos de conteúdo que serão exibidos como sugestões.',
                    'section'     => 'cct_404_page',
                    'type'        => 'multiple-select',
                    'choices'     => $post_type_choices,
                )
            ));

            // Número de itens para exibir
            $wp_customize->add_setting('cct_404_related_count', array(
                'default'           => 4,
                'sanitize_callback' => 'absint',
                'transport'         => 'refresh',
            ));
            $wp_customize->add_control('cct_404_related_count', array(
                'label'       => 'Número de itens',
                'description' => 'Quantos itens de conteúdo mostrar na seção.',
                'section'     => 'cct_404_page',
                'type'        => 'number',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 12,
                    'step' => 1,
                ),
            ));

            // Ordem dos itens
            $wp_customize->add_setting('cct_404_related_orderby', array(
                'default'           => 'date',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ));
            $wp_customize->add_control('cct_404_related_orderby', array(
                'label'       => 'Ordenar por',
                'section'     => 'cct_404_page',
                'type'        => 'select',
                'choices'     => array(
                    'date'          => 'Data (mais recentes primeiro)',
                    'title'         => 'Título (A-Z)',
                    'comment_count' => 'Mais comentados',
                    'rand'          => 'Aleatório',
                ),
            ));
        }
        
        /**
         * Obtém os conteúdos relacionados com base nas configurações
         *
         * @return WP_Query Objeto de consulta com os resultados
         */
        public static function get_related_content() {
            // Obtém as configurações
            $enabled = get_theme_mod('cct_404_related_enabled', true);
            $post_types = get_theme_mod('cct_404_related_post_types', array('post', 'page'));
            $count = get_theme_mod('cct_404_related_count', 4);
            $orderby = get_theme_mod('cct_404_related_orderby', 'date');
            
            // Log para depuração
            error_log('Configurações de conteúdo relacionado:');
            error_log('- Habilitado: ' . ($enabled ? 'Sim' : 'Não'));
            error_log('- Tipos de post: ' . print_r($post_types, true));
            error_log('- Quantidade: ' . $count);
            error_log('- Ordenar por: ' . $orderby);
            
            if (!$enabled) {
                error_log('Conteúdo relacionado desabilitado nas configurações');
                return new WP_Query(array('post__in' => array(0))); // Retorna uma consulta vazia
            }
            
            if (empty($post_types)) {
                error_log('Nenhum tipo de post selecionado');
                return new WP_Query(array('post__in' => array(0))); // Retorna uma consulta vazia
            }
            
            // Garante que $post_types seja um array
            if (!is_array($post_types)) {
                $post_types = array($post_types);
            }
            
            // Argumentos da consulta
            $args = array(
                'post_type'      => $post_types,
                'posts_per_page' => $count,
                'orderby'        => $orderby,
                'order'          => 'DESC',
                'post_status'    => 'publish',
                'no_found_rows'  => true, // Otimização de desempenho
                'cache_results'  => false, // Desativa o cache para depuração
            );
            
            // Log dos argumentos da consulta
            error_log('Argumentos da consulta: ' . print_r($args, true));
            
            $query = new WP_Query($args);
            
            // Log dos resultados da consulta
            error_log('Total de posts encontrados: ' . $query->found_posts);
            
            return $query;
        }
    }

// Inicializar a classe CCT_404_Customizer
add_action('after_setup_theme', function() {
    CCT_404_Customizer::init();
});
