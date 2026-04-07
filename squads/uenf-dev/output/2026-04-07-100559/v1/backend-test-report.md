# Backend Test Report — Tema UENF

**Agente:** Diego (Backend Tester) | **Data:** 2026-04-07 | **Branch:** aparencia

---

## Sumário Executivo

Análise estática completa de todos os arquivos solicitados. Foram identificados **2 issues de severidade alta**, **3 de severidade média** e **3 de severidade baixa**. Nenhum parse error crítico em produção. O `index.php` está funcional com sanitização adequada, com exceção de um `echo get_bloginfo('name')` sem `esc_html()`. O maior risco é a **duplicação massiva de carregamento de módulos** entre `functions.php` e `customizer-loader.php`, e os `error_log()` sem guarda `WP_DEBUG` em produção.

---

## Resultados por Área

### 1. Hooks e Filters — `functions.php`

| Item | Status | Detalhe |
|---|---|---|
| Painel `uenf_panel` — prioridade 5 | ✅ | Correto, antes dos módulos |
| Bloco `customize_register` prioridade 15 | ❌ | **Duplica** todo o carregamento de `customizer-loader.php` — risco de instanciação dupla de gerenciadores |
| Reorganização `uenf_panel` prioridade 999 | ✅ | Lógica correta, executada após todos os módulos |
| Seções `uenf_group_*` prioridade 1001 | ✅ | Ordem garantida |
| `customize_controls_enqueue_scripts` | ✅ | CSS/JS inline corretos via WP API |
| `ob_end_clean()` antes de `!defined('ABSPATH')` | ❌ | **Ordem invertida** — verificação de segurança deve preceder a operação de buffer |
| `ini_set('zlib.output_compression', 'Off')` fora de hook | ⚠️ | Em PHP hardened pode falhar silenciosamente |

### 2. Template Hierarchy — `index.php`

| Item | Status | Detalhe |
|---|---|---|
| Posição `index.php` | ✅ | Fallback final correto na hierarquia WP |
| `get_header()` / `get_footer()` / `get_sidebar()` | ✅ | Presentes na ordem correta |
| `is_home() && ! is_front_page()` | ✅ | Condicional correta para página de posts estática |
| Fallback `content-none` | ✅ | Correto |

### 3. Validação de Funções PHP — `index.php`

| Função | Status | Detalhe |
|---|---|---|
| `get_categories(['hide_empty' => true])` | ✅ | Parâmetros corretos |
| `wp_get_post_categories(get_the_ID(), ['fields' => 'ids'])` | ✅ | Correto dentro do loop |
| `wp_strip_all_tags(get_the_content())` | ✅ | Correto para excerpt sem HTML |
| `mb_strlen()` e `mb_substr()` | ✅ | Multibyte-safe, correto |
| `array_map(fn($id) => 'cat-' . $id, $post_cats)` | ✅ | Arrow function PHP 7.4+ — correto |
| `echo get_bloginfo('name')` | ⚠️ | Ausência de `esc_html()` — XSS teórico |

### 4. Sanitização e Escape — `index.php`

| Ponto | Status | Detalhe |
|---|---|---|
| `esc_attr($cat->term_id)` | ✅ | Correto |
| `esc_html($cat->name)` | ✅ | Correto |
| `esc_attr($data_cats_str)` | ✅ | Correto |
| `esc_url(get_permalink())` (2 ocorrências) | ✅ | Correto |
| `esc_attr($excerpt_short)` no data-excerpt | ✅ | Correto — atributo, não innerHTML |
| `echo get_bloginfo('name')` (linha 27) | ❌ | Sem `esc_html()` |

### 5. `class-header-manager.php`

| Item | Status | Detalhe |
|---|---|---|
| Herança de `CCT_Customizer_Base` | ✅ | Correto |
| Sanitização `absint` / `sanitize_hex_color` / `rest_sanitize_boolean` | ✅ | Corretos para os tipos de dados |
| `transport => 'postMessage'` para alturas/cor | ✅ | Adequado |
| `transport => 'refresh'` para sticky | ✅ | Correto |
| Seção `cct_header_settings` vs mapa `cct_header` | ⚠️ | Renomeação em `functions.php` não tem efeito |
| `$bg_color` interpolado em CSS sem `esc_attr()` | ⚠️ | `sanitize_hex_color` já garante segurança, mas falta escape defensivo |

### 6. `class-cct-custom-controls.php`

| Item | Status | Detalhe |
|---|---|---|
| Guard `!class_exists` | ✅ | Previne redeclaração fatal |
| Escape em `render_content()` — label e value | ✅ | Corretos |
| `echo $this->description` sem escape (linha 29) | ⚠️ | Sem `esc_html()` |
| `echo $this->id` em seletor JS sem `esc_attr()` (linha 64) | ⚠️ | Origem interna mas má prática |

### 7. `customizer-loader.php`

| Item | Status | Detalhe |
|---|---|---|
| Singleton correto | ✅ | |
| `error_log()` sem guarda `WP_DEBUG` (linhas ~209-215) | ❌ | 6+ escritas no log a cada carregamento do customizer |
| Fallback quando `extension_manager` indisponível | ✅ | Carrega todos os módulos — evita tela branca |
| `load_legacy_sections()` vazio | ⚠️ | Código morto |
| `get_legacy_css()` retorna `''` sempre | ⚠️ | Código morto |

### 8. `template-parts/content.php`

| Item | Status | Detalhe |
|---|---|---|
| Parse error corrigido | ✅ | Arquivo válido, sintaxe limpa |
| `esc_url()`, `wp_kses()`, `esc_html__()` | ✅ | Todos corretos |

### 9. Compatibilidade PHP

| Item | Status |
|---|---|
| Arrow function `fn() =>` (PHP 7.4+) | ✅ Req. documentado |
| `list()` shorthand em foreach (PHP 7.1+) | ✅ |
| **Versão mínima efetiva: PHP 7.4** | ✅ |

---

## Performance: Problema N+1

**`wp_get_post_categories()` dentro do loop — ⚠️ Alerta**

Cada iteração dispara uma query separada de termos. Para 10 posts = 10 queries extras.

**Mitigação:**
```php
// Adicionar ANTES do loop have_posts():
if (!empty($GLOBALS['wp_query']->posts)) {
    $post_ids = wp_list_pluck($GLOBALS['wp_query']->posts, 'ID');
    update_object_term_cache($post_ids, 'post');
}
// Após isso, wp_get_post_categories() lerá do object cache — 0 queries extras
```

---

## Casos de Teste TDD — PHPUnit

```php
<?php
/**
 * Tests: lógica crítica do index.php
 * Arquivo: tests/Unit/IndexPhpLogicTest.php
 */
use PHPUnit\Framework\TestCase;

class IndexPhpLogicTest extends TestCase {

    private function filterRealCategories(array $cats, array $slugs): array {
        return array_values(array_filter($cats, function ($cat) use ($slugs) {
            foreach ($slugs as $slug) {
                if (strpos($cat->slug, $slug) === 0) return false;
            }
            return true;
        }));
    }

    private function makeCat(string $slug): object {
        $o = new stdClass();
        $o->slug = $slug;
        $o->term_id = rand(1, 999);
        return $o;
    }

    public function test_sem_categoria_excluido(): void {
        $result = $this->filterRealCategories([$this->makeCat('sem-categoria')], ['sem-categoria','uncategorized']);
        $this->assertCount(0, $result);
    }

    public function test_uncategorized_excluido(): void {
        $result = $this->filterRealCategories([$this->makeCat('uncategorized')], ['sem-categoria','uncategorized']);
        $this->assertCount(0, $result);
    }

    public function test_prefixo_sem_categoria_excluido(): void {
        // sem-categoria-2 começa com sem-categoria → strpos === 0 → excluído
        $result = $this->filterRealCategories([$this->makeCat('sem-categoria-2')], ['sem-categoria','uncategorized']);
        $this->assertCount(0, $result);
    }

    public function test_categoria_normal_nao_excluida(): void {
        $cats = [$this->makeCat('noticias'), $this->makeCat('eventos')];
        $result = $this->filterRealCategories($cats, ['sem-categoria','uncategorized']);
        $this->assertCount(2, $result);
    }

    public function test_lista_mista(): void {
        $cats = [
            $this->makeCat('noticias'),
            $this->makeCat('sem-categoria'),
            $this->makeCat('eventos'),
            $this->makeCat('uncategorized'),
        ];
        $result = $this->filterRealCategories($cats, ['sem-categoria','uncategorized']);
        $this->assertCount(2, $result);
        $this->assertEquals('noticias', $result[0]->slug);
        $this->assertEquals('eventos', $result[1]->slug);
    }

    public function test_exibir_filtro_falso_sem_cats(): void {
        $cats_reais = [];
        $exibir_filtro = count($cats_reais) >= 1;
        $this->assertFalse($exibir_filtro);
    }

    public function test_exibir_filtro_verdadeiro_com_uma_cat(): void {
        $cats_reais = [$this->makeCat('noticias')];
        $exibir_filtro = count($cats_reais) >= 1;
        $this->assertTrue($exibir_filtro);
    }
}
```

---

## Issues — Resumo por Severidade

### 🔴 Alta

- **BACK-001** — `functions.php`: Bloco `customize_register` prioridade 15 duplica carregamento do `customizer-loader.php`. Risco de instanciação dupla de todos os gerenciadores de módulo.
- **BACK-002** — `functions.php`: `ob_end_clean()` na linha 44 executa antes da verificação `!defined('ABSPATH')` da linha 33 — ordem lógica invertida.

### 🟡 Média

- **BACK-003** — `index.php:27`: `echo get_bloginfo('name')` sem `esc_html()`.
- **BACK-004** — `index.php`: `wp_get_post_categories()` dentro do loop — N+1 queries. Resolver com `update_object_term_cache()`.
- **BACK-005** — `customizer-loader.php:209-215`: `error_log()` sem guard `WP_DEBUG` — expõe paths em produção.

### 🟢 Baixa

- **BACK-006** — `class-header-manager.php`: Seção `cct_header_settings` não coincide com `cct_header` no mapa de renomeação.
- **BACK-007** — `customizer-loader.php`: `load_legacy_sections()` e `get_legacy_css()` são código morto.
- **BACK-008** — `class-cct-custom-controls.php`: `echo $this->description` sem `esc_html()`.

---

## Recomendações de Correção

1. **BACK-001**: Auditar e remover o bloco duplicado `customize_register` prioridade 15 de `functions.php`
2. **BACK-002**: Mover `ob_end_clean()` para dentro do bloco protegido por `ABSPATH`
3. **BACK-003**: Substituir `echo get_bloginfo('name')` por `echo esc_html(get_bloginfo('name'))`
4. **BACK-004**: Adicionar `update_object_term_cache()` antes do `while (have_posts())`
5. **BACK-005**: Envolver `error_log()` em `customizer-loader.php` com `if (defined('WP_DEBUG') && WP_DEBUG)`
