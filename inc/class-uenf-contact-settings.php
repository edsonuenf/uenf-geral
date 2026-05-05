<?php
/**
 * Configuração de Contato — CPT singleton para email e telefone do rodapé.
 *
 * Somente Editores e Administradores podem acessar
 * (capability edit_others_posts).
 *
 * @package UENF_Geral
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class UENF_Contact_Settings {

    const POST_TYPE  = 'uenf_contato';
    const META_EMAIL = '_uenf_contact_email';
    const META_PHONE = '_uenf_contact_phone';
    const NONCE      = 'uenf_contact_settings_nonce';

    private static ?self $instance = null;

    public static function get_instance(): self {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init',           [ $this, 'register_post_type' ] );
        add_action( 'admin_init',     [ $this, 'maybe_create_singleton' ], 1 );
        add_action( 'admin_init',     [ $this, 'maybe_redirect_to_post' ], 2 );
        add_action( 'admin_menu',     [ $this, 'add_menu_page' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'save_post_' . self::POST_TYPE, [ $this, 'save_meta' ] );
    }

    // ── Registro do CPT ──────────────────────────────────────────────────────

    public function register_post_type(): void {
        register_post_type( self::POST_TYPE, [
            'labels'          => [
                'name'          => __( 'Configuração de Contato', 'uenf-theme' ),
                'singular_name' => __( 'Configuração de Contato', 'uenf-theme' ),
                'edit_item'     => __( 'Editar Configuração de Contato', 'uenf-theme' ),
            ],
            'public'          => false,
            'show_ui'         => true,
            'show_in_menu'    => false,   // menu próprio gerado em add_menu_page
            'show_in_rest'    => false,
            'supports'        => [ 'title' ],
            'capability_type' => 'post',
            // Qualquer operação requer edit_others_posts (Editor ou superior)
            'capabilities'    => [
                'edit_post'          => 'edit_others_posts',
                'read_post'          => 'edit_others_posts',
                'delete_post'        => 'edit_others_posts',
                'edit_posts'         => 'edit_others_posts',
                'edit_others_posts'  => 'edit_others_posts',
                'delete_posts'       => 'edit_others_posts',
                'publish_posts'      => 'edit_others_posts',
                'read_private_posts' => 'edit_others_posts',
            ],
            'map_meta_cap'    => false,
        ] );
    }

    // ── Singleton: cria o post se ainda não existir ───────────────────────────

    public function maybe_create_singleton(): void {
        if ( uenf_get_contact_post_id() === 0 ) {
            wp_insert_post( [
                'post_type'   => self::POST_TYPE,
                'post_title'  => __( 'Configuração de Contato', 'uenf-theme' ),
                'post_status' => 'publish',
            ] );
        }
    }

    // ── Menu admin ────────────────────────────────────────────────────────────

    public function add_menu_page(): void {
        add_menu_page(
            __( 'Configuração de Contato', 'uenf-theme' ),
            __( 'Contato Rodapé', 'uenf-theme' ),
            'edit_others_posts',
            'uenf-contact-settings',
            [ $this, 'admin_page' ],
            'dashicons-email-alt',
            61
        );
    }

    // Redirect via admin_init (headers ainda não enviados)
    public function maybe_redirect_to_post(): void {
        if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'uenf-contact-settings' ) {
            return;
        }
        $post_id = uenf_get_contact_post_id();
        if ( $post_id > 0 ) {
            wp_safe_redirect( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );
            exit;
        }
    }

    // Fallback: nunca deve ser exibido em condições normais
    public function admin_page(): void {
        echo '<div class="wrap"><p>' . esc_html__( 'Redirecionando...', 'uenf-theme' ) . '</p></div>';
    }

    // ── Meta box ──────────────────────────────────────────────────────────────

    public function add_meta_box(): void {
        add_meta_box(
            'uenf_contact_fields',
            __( 'Informações de Contato do Rodapé', 'uenf-theme' ),
            [ $this, 'render_meta_box' ],
            self::POST_TYPE,
            'normal',
            'high'
        );
    }

    public function render_meta_box( \WP_Post $post ): void {
        $emails = self::get_items( $post->ID, self::META_EMAIL );
        $phones = self::get_items( $post->ID, self::META_PHONE );
        wp_nonce_field( self::NONCE, self::NONCE );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="uenf_contact_email"><?php esc_html_e( 'Email', 'uenf-theme' ); ?></label>
                </th>
                <td>
                    <textarea id="uenf_contact_email"
                              name="uenf_contact_email"
                              rows="3"
                              class="large-text"
                              placeholder="contato@uenf.br"><?php echo esc_textarea( implode( "\n", $emails ) ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Um e-mail por linha. Quando houver mais de um, serão separados por " | " no rodapé.', 'uenf-theme' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="uenf_contact_phone"><?php esc_html_e( 'Telefone', 'uenf-theme' ); ?></label>
                </th>
                <td>
                    <textarea id="uenf_contact_phone"
                              name="uenf_contact_phone"
                              rows="3"
                              class="large-text"
                              placeholder="(22) 2739-7000"><?php echo esc_textarea( implode( "\n", $phones ) ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Um telefone por linha. Quando houver mais de um, serão separados por " | " no rodapé.', 'uenf-theme' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    // Lê o meta como array, compatível com valores antigos em string simples
    private static function get_items( int $post_id, string $meta_key ): array {
        $value = get_post_meta( $post_id, $meta_key, true );
        if ( is_array( $value ) ) {
            return array_filter( $value );
        }
        // legado: valor salvo como string única
        $str = trim( (string) $value );
        return $str !== '' ? [ $str ] : [];
    }

    // ── Salvar meta ───────────────────────────────────────────────────────────

    public function save_meta( int $post_id ): void {
        if ( ! isset( $_POST[ self::NONCE ] )
            || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE ] ) ), self::NONCE )
        ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_others_posts' ) ) {
            return;
        }

        if ( isset( $_POST['uenf_contact_email'] ) ) {
            $lines = preg_split( '/\r\n|\r|\n/', wp_unslash( $_POST['uenf_contact_email'] ) );
            $items = array_values( array_filter( array_map(
                fn( $v ) => sanitize_email( trim( $v ) ),
                $lines
            ) ) );
            update_post_meta( $post_id, self::META_EMAIL, $items );
        }

        if ( isset( $_POST['uenf_contact_phone'] ) ) {
            $lines = preg_split( '/\r\n|\r|\n/', wp_unslash( $_POST['uenf_contact_phone'] ) );
            $items = array_values( array_filter( array_map(
                fn( $v ) => sanitize_text_field( trim( $v ) ),
                $lines
            ) ) );
            update_post_meta( $post_id, self::META_PHONE, $items );
        }
    }
}

// ── Helper functions globais ─────────────────────────────────────────────────

function uenf_get_contact_post_id(): int {
    $posts = get_posts( [
        'post_type'      => UENF_Contact_Settings::POST_TYPE,
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ] );
    return $posts ? (int) $posts[0] : 0;
}

// Retorna os valores como array (uso interno e no footer)
function uenf_get_contact_emails(): array {
    $id = uenf_get_contact_post_id();
    if ( ! $id ) {
        return [];
    }
    $value = get_post_meta( $id, UENF_Contact_Settings::META_EMAIL, true );
    if ( is_array( $value ) ) {
        return array_values( array_filter( $value ) );
    }
    $str = trim( (string) $value );
    return $str !== '' ? [ $str ] : [];
}

function uenf_get_contact_phones(): array {
    $id = uenf_get_contact_post_id();
    if ( ! $id ) {
        return [];
    }
    $value = get_post_meta( $id, UENF_Contact_Settings::META_PHONE, true );
    if ( is_array( $value ) ) {
        return array_values( array_filter( $value ) );
    }
    $str = trim( (string) $value );
    return $str !== '' ? [ $str ] : [];
}

// Mantidos para compatibilidade — retornam string formatada com " | "
function uenf_get_contact_email(): string {
    return implode( ' | ', uenf_get_contact_emails() );
}

function uenf_get_contact_phone(): string {
    return implode( ' | ', uenf_get_contact_phones() );
}
