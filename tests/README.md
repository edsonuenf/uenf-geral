# Testes para o Tema UENF

Este diretório contém os testes automatizados para o tema UENF.

## Pré-requisitos

- PHP 7.4 ou superior
- [Composer](https://getcomposer.org/)
- [PHPUnit](https://phpunit.de/)
- WordPress (para testes de integração)

## Instalação

1. Instale as dependências do Composer:

```bash
composer install
```

2. Configure as variáveis de ambiente necessárias:

```bash
export WP_TESTS_DIR=/caminho/para/wordpress-tests-lib
export WP_ROOT_DIR=/caminho/para/wordpress
```

## Executando os Testes

Para executar todos os testes:

```bash
composer test
```

Para executar testes específicos:

```bash
./vendor/bin/phpunit tests/unit/FormattingTest.php
```

## Estrutura de Diretórios

- `unit/`: Testes unitários
- `integration/`: Testes de integração
- `test-functions.php`: Funções auxiliares para testes

## Escrevendo Novos Testes

1. Crie um novo arquivo na pasta `unit/` ou `integration/`
2. Estenda a classe `WP_UnitTestCase`
3. Use o prefixo `test` nos métodos de teste
4. Use anotações como `@covers` e `@group` para documentar seus testes

## Boas Práticas

- Um teste por funcionalidade
- Nomes descritivos para testes
- Use data providers para testar múltiplos cenários
- Mantenha os testes independentes
- Teste casos de sucesso e falha
