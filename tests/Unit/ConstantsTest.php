<?php
/**
 * Verifica as constantes do tema via análise estática de functions.php.
 * Não executa o arquivo — lê o código-fonte e valida definições e valores.
 */

namespace UENF\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ConstantsTest extends TestCase {

    private string $source;

    protected function setUp(): void {
        $this->source = file_get_contents( dirname( __DIR__, 2 ) . '/functions.php' );
    }

    // ── Constantes UENF_ devem estar definidas no código ─────────────────────

    /** @dataProvider uenfConstantsProvider */
    public function test_uenf_constant_is_defined_in_source( string $constant ): void {
        $this->assertMatchesRegularExpression(
            '/define\s*\(\s*[\'"]' . preg_quote( $constant, '/' ) . '[\'"]\s*,/',
            $this->source,
            "define('$constant', ...) não encontrado em functions.php"
        );
    }

    public static function uenfConstantsProvider(): array {
        return [
            [ 'UENF_PRIMARY_COLOR' ],
            [ 'UENF_PRIMARY_LIGHT' ],
            [ 'UENF_TEXT_COLOR' ],
            [ 'UENF_LINK_COLOR' ],
            [ 'UENF_LINK_HOVER_COLOR' ],
            [ 'UENF_WHITE' ],
            [ 'UENF_BLACK' ],
            [ 'UENF_PRIMARY_FONT' ],
            [ 'UENF_SECONDARY_FONT' ],
            [ 'UENF_FONT_SIZE_BASE' ],
            [ 'UENF_FONT_SIZE_LG' ],
            [ 'UENF_FONT_SIZE_XL' ],
            [ 'UENF_FONT_SIZE_XXL' ],
            [ 'UENF_DEFAULT_MENU_STYLE' ],
            [ 'UENF_DEFAULT_FOOTER_COLUMNS' ],
            [ 'UENF_DEFAULT_LAZY_LOADING' ],
        ];
    }

    // ── Constantes CCT_ não devem existir no código ───────────────────────────

    /** @dataProvider cctConstantsProvider */
    public function test_cct_constant_not_in_source( string $constant ): void {
        $this->assertDoesNotMatchRegularExpression(
            '/define\s*\(\s*[\'"]' . preg_quote( $constant, '/' ) . '[\'"]\s*,/',
            $this->source,
            "define('$constant', ...) ainda presente em functions.php"
        );
    }

    public static function cctConstantsProvider(): array {
        return [
            [ 'CCT_PRIMARY_COLOR' ],
            [ 'CCT_PRIMARY_LIGHT' ],
            [ 'CCT_TEXT_COLOR' ],
            [ 'CCT_LINK_COLOR' ],
            [ 'CCT_WHITE' ],
            [ 'CCT_BLACK' ],
            [ 'CCT_PRIMARY_FONT' ],
            [ 'CCT_DEFAULT_MENU_STYLE' ],
        ];
    }

    // ── Valores corretos das constantes principais ────────────────────────────

    public function test_primary_color_value_is_correct(): void {
        $this->assertMatchesRegularExpression(
            "/define\s*\(\s*'UENF_PRIMARY_COLOR'\s*,\s*'#1D3771'\s*\)/",
            $this->source
        );
    }

    public function test_white_value_is_correct(): void {
        $this->assertMatchesRegularExpression(
            "/define\s*\(\s*'UENF_WHITE'\s*,\s*'#FFFFFF'\s*\)/",
            $this->source
        );
    }

    public function test_footer_columns_value_is_correct(): void {
        $this->assertMatchesRegularExpression(
            "/define\s*\(\s*'UENF_DEFAULT_FOOTER_COLUMNS'\s*,\s*3\s*\)/",
            $this->source
        );
    }

    public function test_lazy_loading_value_is_true(): void {
        $this->assertMatchesRegularExpression(
            "/define\s*\(\s*'UENF_DEFAULT_LAZY_LOADING'\s*,\s*true\s*\)/",
            $this->source
        );
    }
}
