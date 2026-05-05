<?php
/**
 * Verifica que as classes principais do tema carregam corretamente
 * com o novo prefixo UENF_ e que as antigas CCT_ não existem.
 */

namespace UENF\Tests\Integration;

use PHPUnit\Framework\TestCase;

class ClassLoadingTest extends TestCase {

    // ── Arquivos a carregar ───────────────────────────────────────────────────

    private static array $classFiles = [
        'inc/customizer/class-dark-mode-manager.php'     => 'UENF_Dark_Mode_Manager',
        'inc/class-form-validator.php'                   => 'UENF_Form_Validator',
        'inc/class-advanced-search.php'                  => 'UENF_Advanced_Search',
        'inc/class-theme-reset-manager.php'              => 'UENF_Theme_Reset_Manager',
        'inc/extensions/class-extension-manager.php'     => 'UENF_Extension_Manager',
        'inc/customizer/class-color-manager.php'         => 'UENF_Color_Manager',
        'inc/customizer/class-gradient-manager.php'      => 'UENF_Gradient_Manager',
        'inc/customizer/class-icon-manager.php'          => 'UENF_Icon_Manager',
    ];

    /** @dataProvider classFileProvider */
    public function test_class_loads_without_error( string $file, string $className ): void {
        $path = dirname( __DIR__, 2 ) . '/' . $file;
        $this->assertFileExists( $path, "Arquivo $file não encontrado" );

        ob_start();
        require_once $path;
        ob_end_clean();

        $this->assertTrue( class_exists( $className ), "Classe $className não existe após include de $file" );
    }

    public static function classFileProvider(): array {
        $out = [];
        foreach ( self::$classFiles as $file => $class ) {
            $out[ $class ] = [ $file, $class ];
        }
        return $out;
    }

    // ── Classes antigas CCT_ não devem existir ────────────────────────────────

    /** @dataProvider legacyClassProvider */
    public function test_legacy_cct_class_does_not_exist( string $className ): void {
        $this->assertFalse( class_exists( $className, false ), "Classe legada $className ainda existe" );
    }

    public static function legacyClassProvider(): array {
        return [
            [ 'CCT_Dark_Mode_Manager' ],
            [ 'CCT_Form_Validator' ],
            [ 'CCT_Advanced_Search' ],
            [ 'CCT_Theme_Reset_Manager' ],
            [ 'CCT_Extension_Manager' ],
            [ 'CCT_Color_Manager' ],
            [ 'CCT_Gradient_Manager' ],
            [ 'CCT_Icon_Manager' ],
        ];
    }

    // ── Métodos de sanitização disponíveis ────────────────────────────────────

    public function test_dark_mode_manager_has_sanitize_rgba_color(): void {
        require_once dirname( __DIR__, 2 ) . '/inc/customizer/class-dark-mode-manager.php';
        $this->assertTrue(
            method_exists( 'UENF_Dark_Mode_Manager', 'sanitize_rgba_color' ),
            'Método sanitize_rgba_color ausente em UENF_Dark_Mode_Manager'
        );
    }

    public function test_dark_mode_manager_has_sanitize_time(): void {
        $this->assertTrue(
            method_exists( 'UENF_Dark_Mode_Manager', 'sanitize_time' ),
            'Método sanitize_time ausente em UENF_Dark_Mode_Manager'
        );
    }

    public function test_dark_mode_manager_has_sanitize_float(): void {
        $this->assertTrue(
            method_exists( 'UENF_Dark_Mode_Manager', 'sanitize_float' ),
            'Método sanitize_float ausente em UENF_Dark_Mode_Manager'
        );
    }

    // ── Enqueue usa handles com prefixo uenf- ─────────────────────────────────

    public function test_enqueue_style_uses_uenf_handle(): void {
        require_once dirname( __DIR__, 2 ) . '/inc/customizer/class-dark-mode-manager.php';
        $source = file_get_contents(
            dirname( __DIR__, 2 ) . '/inc/customizer/class-dark-mode-manager.php'
        );
        $this->assertStringContainsString( "'uenf-dark-mode'", $source );
        $this->assertStringNotContainsString( "'cct-dark-mode'", $source );
    }
}
