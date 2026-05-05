<?php
/**
 * Testa os métodos de sanitização do tema — pura lógica PHP, sem WP real.
 */

namespace UENF\Tests\Unit;

use PHPUnit\Framework\TestCase;

class SanitizersTest extends TestCase {

    private \UENF_Dark_Mode_Manager $manager;

    protected function setUp(): void {
        require_once dirname( __DIR__, 2 ) . '/inc/customizer/class-dark-mode-manager.php';
        // Instância direta contornando o singleton para testes
        $this->manager = new \UENF_Dark_Mode_Manager();
    }

    // ── sanitize_rgba_color ───────────────────────────────────────────────────

    /** @dataProvider validRgbaProvider */
    public function test_sanitize_rgba_accepts_valid_values( string $input ): void {
        $this->assertSame( $input, $this->manager->sanitize_rgba_color( $input ) );
    }

    public static function validRgbaProvider(): array {
        return [
            'rgb simples'             => [ 'rgb(0, 0, 0)' ],
            'rgb sem espaços'         => [ 'rgb(255,255,255)' ],
            'rgba com alpha'          => [ 'rgba(29, 55, 113, 0.8)' ],
            'rgba alpha inteiro'      => [ 'rgba(0,0,0,1)' ],
            'rgba alpha zero'         => [ 'rgba(255,255,255,0)' ],
            'rgba com espaços extras' => [ 'rgba( 100 , 100 , 100 , 0.5 )' ],
        ];
    }

    /** @dataProvider invalidRgbaProvider */
    public function test_sanitize_rgba_rejects_invalid_values( string $input ): void {
        $this->assertSame( '', $this->manager->sanitize_rgba_color( $input ) );
    }

    public static function invalidRgbaProvider(): array {
        return [
            'cor hex'           => [ '#1d3771' ],
            'nome de cor'       => [ 'blue' ],
            'js injection'      => [ 'rgba(0,0,0,0); alert(1)' ],
            'string vazia'      => [ '' ],
            'rgb fora do range' => [ 'rgb(256,0,0)' ],
            'sem parêntese'     => [ 'rgba 0,0,0,1' ],
        ];
    }

    public function test_sanitize_rgba_trims_whitespace(): void {
        $this->assertSame( 'rgb(0, 0, 0)', $this->manager->sanitize_rgba_color( '  rgb(0, 0, 0)  ' ) );
    }

    // ── sanitize_time ─────────────────────────────────────────────────────────

    /** @dataProvider validTimeProvider */
    public function test_sanitize_time_accepts_valid_times( string $input ): void {
        $this->assertSame( $input, $this->manager->sanitize_time( $input ) );
    }

    public static function validTimeProvider(): array {
        return [
            'meia-noite'     => [ '00:00' ],
            'horário padrão' => [ '18:00' ],
            'fim do dia'     => [ '23:59' ],
            'hora única'     => [ '9:00' ],
        ];
    }

    /** @dataProvider invalidTimeProvider */
    public function test_sanitize_time_returns_default_for_invalid( string $input ): void {
        $this->assertSame( '18:00', $this->manager->sanitize_time( $input ) );
    }

    public static function invalidTimeProvider(): array {
        return [
            'hora inválida'   => [ '25:00' ],
            'minuto inválido' => [ '12:60' ],
            'formato errado'  => [ '1200' ],
            'string vazia'    => [ '' ],
            'letras'          => [ 'meio-dia' ],
        ];
    }

    // ── sanitize_float ────────────────────────────────────────────────────────

    public function test_sanitize_float_clamps_to_minimum(): void {
        $this->assertSame( 0.1, $this->manager->sanitize_float( 0.0 ) );
        $this->assertSame( 0.1, $this->manager->sanitize_float( -5.0 ) );
    }

    public function test_sanitize_float_clamps_to_maximum(): void {
        $this->assertSame( 2.0, $this->manager->sanitize_float( 3.0 ) );
        $this->assertSame( 2.0, $this->manager->sanitize_float( 999 ) );
    }

    public function test_sanitize_float_accepts_valid_range(): void {
        $this->assertSame( 1.0, $this->manager->sanitize_float( 1.0 ) );
        $this->assertSame( 0.1, $this->manager->sanitize_float( 0.1 ) );
        $this->assertSame( 2.0, $this->manager->sanitize_float( 2.0 ) );
        $this->assertSame( 1.5, $this->manager->sanitize_float( 1.5 ) );
    }
}
