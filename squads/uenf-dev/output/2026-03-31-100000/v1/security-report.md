# Security Report — uenf-dev run 2026-03-31

**Autor:** André (Security Reviewer)
**Branch auditada:** `aparencia`
**Escopo:** Customizer UENF — reorganização de painel, injeção PHP→JS, CSS inline, controles anônimos, template-tags

---

## Veredicto Geral

**APROVADO COM RESSALVAS**

O núcleo das mudanças da branch `aparencia` está seguro. Não há input de usuário não sanitizado nos fluxos principais auditados. As ressalvas são: (1) `error_log()` incondicional em produção em `customizer-loader.php`; (2) SVG XSS via `handle_svg_optimization()` em `class-icon-manager.php` — exposto pela varredura ampliada de `$_POST`; (3) `$_POST['gradients']` sem sanitização em `class-gradient-manager.php`.

---

## A. Injeção PHP → JS via `wp_json_encode`

**`functions.php` linhas 631–632**

- `$child_sections` e `$group_children_map` são arrays 100% hardcoded. Nenhum valor vem de banco, `$_GET`, `$_POST` ou fonte externa. **Não há caminho de injeção.**
- `wp_json_encode()` preferível a `json_encode()` por wrapper WordPress com tratamento de erros.

**Status: APROVADO**

---

## B. CSS Inline Gerado por PHP

**`functions.php` linhas 592–627**

- Bloco CSS é string literal PHP sem nenhuma interpolação de variável. Nenhum dado externo na string.
- `wp_add_inline_style()` insere CSS raw — irrelevante aqui pois não há interpolação. Se no futuro for adicionado dado dinâmico, exige sanitização prévia.

**Status: APROVADO**

---

## C. Controles no Customizer — Settings e Sanitização

**`functions.php` linhas 550–563**

- `type => 'option'` com `sanitize_callback => '__return_empty_string'`: valor salvo sempre `""`. Funcionalmente seguro. Problema arquitetural: cria 16 entradas em `wp_options` a cada save — considerar `type => 'theme_mod'`.
- Anonymous class com `render_content(){}` vazio: não renderiza HTML, não expõe dados.

**Status: APROVADO COM OBSERVAÇÃO** (poluição de `wp_options`)

---

## D. Verificação de Capabilities

- Painel `uenf_panel`: `capability => 'edit_theme_options'` — correto.
- Seções dos managers sem `capability` explícita — herdam do painel pai por comportamento documentado do Core WordPress.
- Endpoints AJAX usam `current_user_can('customize')` — equivalente. Consistente.

**Status: APROVADO**

---

## E. Exposição de Dados

- `wp_json_encode($group_children_map)` em `<script>`: expõe IDs de seções já visíveis no DOM do Customizer. Não é informação sensível.
- **`error_log()` incondicional em `customizer-loader.php`:** 16 chamadas sem guarda `WP_DEBUG`. Em falha de módulo, expõe path absoluto do servidor. Se o log estiver acessível publicamente (má configuração), revela estrutura de diretórios.

**Status: APROVADO COM RESSALVA**

---

## F. `uenf_get_random_image()` — Análise de Segurança

**`inc/template-tags.php` linhas 143–157**

- Guard `WP_DEBUG`: retorna `''` em produção. Superfície de ataque zero em produção.
- `$width` e `$height`: passados por `absint()` — impossível injetar strings ou tags.
- `$bg`: vem de `array_rand()` sobre array hardcoded de hex strings — nenhuma entrada de usuário.
- `esc_url()` aplicado ao resultado final: correto para contexto de atributo `src`.

**Status: APROVADO**

---

## G. Direct File Access Protection

Varredura nos 29 arquivos de `inc/customizer/` — todos contêm `if (!defined('ABSPATH')) { exit; }`. Cobertura 100%.

**Status: APROVADO**

---

## Vulnerabilidades Encontradas

| # | Vulnerabilidade | Severidade | CVSS estimado | Arquivo:Linha | Correção |
|---|---|---|---|---|---|
| 1 | **SVG XSS via `handle_svg_optimization()`** — `$_POST['svg_content']` recebe SVG arbitrário sem sanitização. `validate_svg()` não é chamada aqui (apenas em `handle_icon_upload()`). SVG com `onload="..."` ou `<image href="javascript:...">` passaria. | **Alta** | 7.1 | `class-icon-manager.php:714–715` | Chamar `validate_svg()` antes de `optimize_svg()`; revisar `validate_svg()` para cobrir atributos de evento e `javascript:` URIs |
| 2 | **`$_POST['gradients']` sem sanitização** — acesso direto inconsistente com o padrão do arquivo | **Média** | 4.3 | `class-gradient-manager.php:1064` | Aplicar `$this->sanitize_json_array()` |
| 3 | **`error_log()` incondicional expõe paths do servidor** | **Baixa** | 3.1 | `customizer-loader.php:68,79,94,96,205–270` | Envolver com `if (defined('WP_DEBUG') && WP_DEBUG)` |
| 4 | **`$_POST['layout_data']` sem validação de estrutura** — `json_decode()` sem limite de tamanho ou profundidade | **Baixa** | 3.5 | `class-layout-manager.php:818–830` | Limite de profundidade (`json_decode(..., true, 5)`) e tamanho |

---

## Recomendações

**Críticas (corrigir antes de produção):**

1. **[SVG XSS]** Em `handle_svg_optimization()`, adicionar `validate_svg($svg_content)` antes de `optimize_svg()`. Revisar `validate_svg()` para cobrir atributos de evento (`onload`, `onclick`, `onerror`) e `javascript:` URIs — atualmente só bloqueia tags `script`, `object`, `embed`, `iframe`.

2. **[Gradient POST]** Sanitizar `$_POST['gradients']` em `class-gradient-manager.php:1064` com `sanitize_json_array()`.

**Importantes (próximo sprint):**

3. **[error_log]** Guardar todos os `error_log()` em `customizer-loader.php` com `WP_DEBUG`.

4. **[Layout JSON]** Adicionar validação de tamanho e profundidade ao JSON de layout antes de `set_theme_mod()`.

**Observações arquiteturais:**

5. **[Settings type]** Substituir `type => 'option'` por `type => 'theme_mod'` nos settings `_uenf_group_*` para evitar 16 entradas avulsas em `wp_options`.

6. **[SVG storage]** Auditar `icon_shortcode()` para garantir que SVGs armazenados em `cct_custom_icons_data` são escapados adequadamente no frontend.

---

*Auditoria realizada em 2026-03-31 por André (Security Reviewer) — squad uenf-dev.*
*Metodologia: OWASP Top 10, WordPress Security Coding Standards, análise estática.*
