<?php

require_once dirname(dirname(__DIR__)) . '/functions.php';

class FormattingTest extends PHPUnit\Framework\TestCase {

    /**
     * Testa a formatação de números de telefone brasileiros
     *
     * @dataProvider phoneNumberProvider
     */
    public function testFormatarTelefoneBrasil($input, $expected) {
        $this->assertEquals($expected, formatarTelefoneBrasil($input));
    }

    /**
     * Provedor de dados para teste de formatação de telefone
     */
    public function phoneNumberProvider() {
        return [
            // Formato 8 dígitos (fixo antigo)
            '8_digitos' => [
                '21234567',
                '(21) 2345-6789' // Assumindo que adiciona um 9 no final
            ],
            // Formato 9 dígitos (celular com nono dígito)
            '9_digitos' => [
                '21987654321',
                '(21) 98765-4321'
            ],
            // Já formatado
            'ja_formatado' => [
                '(21) 2345-6789',
                '(21) 2345-6789'
            ],
            // Com espaços e caracteres especiais
            'com_especiais' => [
                ' (21) 9 8765-4321 ',
                '(21) 98765-4321'
            ],
            // Número inválido (menos de 8 dígitos)
            'invalido_curto' => [
                '1234567',
                '1234567' // Deve retornar o mesmo valor
            ],
            // Número muito longo
            'muito_longo' => [
                '219876543210',
                '219876543210' // Deve retornar o mesmo valor
            ]
        ];
    }

    /**
     * Testa se o filtro está funcionando corretamente
     */
    public function testFilterFormataTelefone() {
        $content = 'Meu telefone é 21987654321';
        $expected = 'Meu telefone é (21) 98765-4321';
        
        $this->assertEquals($expected, formatarTelefoneBrasil($content));
    }
}
