# Backend Test Report — uenf-dev | Run 2026-03-30-115040
**Agente:** Diego — Backend Tester
**Data:** 2026-03-30

---

## Resumo

| Área | Status | Severidade |
|------|--------|------------|
| Patterns PHP deletados — referências órfãs | ✅ Sem quebra | — |
| `register_block_pattern` — patterns restantes | ✅ OK | — |
| `cct_registra_padroes_blocos` — hook de registro | ✅ Correto | — |
| Categories de patterns ainda registradas | 🟡 Atenção | Baixo |
| `uenf_get_random_image()` — validação de parâmetros | 🔴 Risco | Médio |
| Migração de asset path — lógica PHP | ✅ Sem erro | — |
| Template hierarchy — sem alterações | ✅ Inalterado | — |
| Hooks de enqueue — ordens e prioridades | ✅ Correto | — |
| `uenf_load_addons` + `cct_init_addons` — carregamento duplo | 🟡 Atenção | Baixo |

---

## ✅ Patterns Deletados — Nenhuma Referência Órfã Crítica

Os 6 arquivos PHP deletados (`contact-card.php`, `faq-accordion.php`, `faq-tabs.php`, `highlights-grid.php`, `news-list.php`, `pricing-table.php`) **não estão referenciados por `require`, `include` ou `register_block_pattern` em `functions.php`**.

A função `cct_registra_padroes_blocos()` registra apenas 4 patterns inline (sem `require` dos arquivos PHP), todos via conteúdo HTML embutido diretamente na chamada:
- `cct-tema/secao-chamada` (Hero)
- `cct-tema/secao-servicos` (Grid de serviços)
- `cct-tema/secao-depoimentos` (Depoimentos)
- `cct-tema/secao-contato` (Contato)

**Os arquivos deletados eram patterns autônomos que provavelmente eram carregados via `require` em uma versão anterior, mas a referência já foi removida.** Deleção limpa — sem quebra de funcionalidade.

---

## 🟡 Pattern Categories — Categorias Órfãs Registradas

Em `functions.php` (linha 1637-1646), ainda são registradas categorias de patterns:

```php
register_block_pattern_category('uenf-patterns', [...]);
register_block_pattern_category('uenf-faq',      [...]);  // ← FAQ patterns deletados
register_block_pattern_category('uenf-pricing',  [...]);  // ← Pricing patterns deletados
```

As categorias `uenf-faq` e `uenf-pricing` ficaram vazias após a deleção dos patterns. O Gutenberg exibirá essas categorias sem nenhum pattern dentro — pode confundir editores de conteúdo que veem a categoria mas não encontram nada.

**Recomendação:** Remover as categorias cujos patterns foram migrados para o plugin, ou mover o registro das categorias para o plugin `uenf-templates`.

---

## 🔴 `uenf_get_random_image()` — Ausência de Validação de Parâmetros

```php
function uenf_get_random_image( $width = 1200, $height = 800 ) {
    $colors = ['1d3771', '2c5aa0', '28a745', 'dc3545', 'e0a800'];
    $bg = $colors[ array_rand( $colors ) ];
    return "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image";
}
```

**Problema:** Os parâmetros `$width` e `$height` são interpolados diretamente na URL **sem validação ou sanitização**. Se esta função for chamada com input não confiável:

```php
// Exemplo de chamada maliciosa se o input vier de uma requisição:
uenf_get_random_image('javascript:alert(1)', '800');
// Resultado: https://placehold.co/javascript:alert(1)x800/...
```

Embora a função retorne uma string (não a execute), se o retorno for usado em `echo` sem escapamento adequado (`esc_url()`), pode gerar XSS.

**Contexto mitigante:** A função retorna uma URL para `placehold.co` — se sempre chamada com valores literais pelo desenvolvedor (não via input de usuário), o risco é baixo. Mas a função não documenta essa restrição.

**Recomendação:**
```php
function uenf_get_random_image( $width = 1200, $height = 800 ) {
    $width  = absint( $width )  ?: 1200;
    $height = absint( $height ) ?: 800;
    $colors = ['1d3771', '2c5aa0', '28a745', 'dc3545', 'e0a800'];
    $bg     = $colors[ array_rand( $colors ) ];
    return esc_url( "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image" );
}
```

---

## ✅ Template Hierarchy — Inalterada

Os arquivos de template (`index.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `404.php`, `header.php`, `footer.php`, `sidebar.php`) não foram modificados. A hierarquia de templates WordPress está intacta.

---

## ✅ Migração de Asset Path — Lógica PHP Correta

```php
$style_path = get_template_directory() . '/assets/dist/css/style.min.css';
$style_version = file_exists($style_path) ? filemtime($style_path) : $theme_version;
```

- `get_template_directory()` retorna o path absoluto correto
- `file_exists()` antes de `filemtime()` — sem PHP warning se arquivo não existir
- Fallback para `$theme_version` se arquivo ausente — comportamento seguro

---

## 🟡 Dois Hooks de Inicialização — `uenf_load_addons` + `cct_init_addons`

```php
add_action('after_setup_theme', 'uenf_load_addons', 10);  // prioridade 10
add_action('after_setup_theme', 'cct_init_addons',  20);  // prioridade 20
```

Dois hooks de setup rodando em sequência. Não é um problema funcional, mas indica uma sobreposição de prefixos (`uenf_` e `cct_`) que sugere refatoração incompleta. À medida que o tema evolui, consolidar em um único ponto de inicialização reduz a superfície de problemas.

---

## ✅ `wp_get_nav_menu_items` Filter — Correto

```php
add_filter('wp_get_nav_menu_items', 'uenf_filter_hidden_pages_from_menu', 999, 2);
```

Prioridade 999 — executado por último, correto para um filtro de ocultação. Sem conflito com as alterações desta sessão.

---

## Checklist Backend

| Item | Status |
|------|--------|
| Patterns deletados sem referências órfãs | ✅ |
| Pattern categories limpas | ⚠️ `uenf-faq` e `uenf-pricing` vazias |
| `uenf_get_random_image()` com `absint()` | ❌ Faltando |
| Template hierarchy intacta | ✅ |
| Asset path com `file_exists()` guard | ✅ |
| Hooks registrados corretamente | ✅ |
| Duplo hook de setup — consolidar | ⚠️ Técnico debt |
