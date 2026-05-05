# Dev Brief — uenf-dev | Run 2026-03-30-115040
**Agente:** Lucas — WordPress Template Developer
**Branch:** modelos-uenf → main
**Data:** 2026-03-30
**Scope:** Alterações não comitadas na working tree

---

## 1. Mapa de Alterações

### Arquivos Modificados
| Arquivo | Tipo | Intenção Detectada |
|---------|------|-------------------|
| `functions.php` | PHP | Migração de caminho de assets: `/css/` → `/assets/dist/css/` (parcial) |
| `inc/template-tags.php` | PHP | Nova função helper `uenf_get_random_image()` para uso no plugin de templates |
| `scss/style.scss` | SCSS | Estilos de botão pill, utilitários de cor, fix de Query Loop |
| `scss/variables.scss` | SCSS | Novo token `$secondary-color` |
| `docker-compose.yml` | Infra | Integração do plugin `uenf-templates` via volume Docker |
| `assets/dist/css/style.min.css` | CSS | CSS compilado das mudanças SCSS (artefato de build) |

### Arquivos Deletados
| Arquivo | Motivo |
|---------|--------|
| `patterns/contact-card.php` | Migração para plugin `uenf-templates` |
| `patterns/faq-accordion.php` | Migração para plugin `uenf-templates` |
| `patterns/faq-tabs.php` | Migração para plugin `uenf-templates` |
| `patterns/highlights-grid.php` | Migração para plugin `uenf-templates` |
| `patterns/news-list.php` | Migração para plugin `uenf-templates` |
| `patterns/pricing-table.php` | Migração para plugin `uenf-templates` |

---

## 2. Análise Técnica por Arquivo

### `functions.php` — Migração de Assets (Parcial)

**O que mudou:**
```php
// Antes
add_editor_style('css/editor-style.css');
$style_path = get_template_directory() . '/css/style.min.css';

// Depois
add_editor_style('assets/dist/css/style.min.css');
$style_path = get_template_directory() . '/assets/dist/css/style.min.css';
```

**Problema crítico detectado — Migração Incompleta:**
A migração para `assets/dist/css/` é **parcial**. O enqueue principal foi migrado, mas múltiplos stylesheets ainda apontam para `/css/`:

```php
// Ainda em /css/ (NÃO migrados):
wp_enqueue_style('cct-variables',      CCT_THEME_URI . '/css/variables.css', ...)
wp_enqueue_style('cct-reset',          CCT_THEME_URI . '/css/reset.css', ...)
wp_enqueue_style('cct-hero-header-fix',CCT_THEME_URI . '/css/hero-header-fix.css', ...)
wp_enqueue_style('cct-styles-additional', CCT_THEME_URI . '/css/styles.css', ...)
wp_enqueue_style('cct-custom-fixes',   CCT_THEME_URI . '/css/custom-fixes.css', ...)
wp_enqueue_style('cct-patterns',       CCT_THEME_URI . '/css/patterns.css', ...)
wp_enqueue_style('cct-customizer-fix', CCT_THEME_URI . '/css/customizer-fix.css', ...)
// + spacing-fixes.css, 404.css e outros
```

**Implicação:** O diretório `/css/` ainda é necessário e não pode ser removido. A migração de assets está em andamento — este é um estado intermediário intencional ou acidental que precisa ser esclarecido.

**Segundo problema — Editor Style:**
`add_editor_style('assets/dist/css/style.min.css')` aponta para o CSS minificado de produção. O editor Gutenberg carrega este arquivo inline — usar o minificado pode causar:
- Dificuldade de depuração no editor
- Possível conflito com estilos do próprio editor WordPress
- Recomendado: um `editor-style.css` separado com apenas variáveis e estilos de bloco

---

### `inc/template-tags.php` — Helper de Placeholder

**O que foi adicionado:**
```php
function uenf_get_random_image( $width = 1200, $height = 800 ) {
    $colors = ['1d3771', '2c5aa0', '28a745', 'dc3545', 'e0a800'];
    $bg = $colors[ array_rand( $colors ) ];
    return "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image";
}
```

**Avaliação WordPress Standards:**
- ✅ `if ( ! function_exists() )` — pluggable, correto
- ✅ Newline adicionado ao final do arquivo
- ⚠️ DocBlock incompleto — faltam `@param` e `@return`
- ⚠️ Serviço externo `placehold.co` sem proteção para ambiente de produção
- ℹ️ Intenção clara: suporte ao plugin `uenf-templates` que está sendo integrado — a função provavelmente será chamada de dentro do plugin via hook ou include. Correto manter no tema se o plugin depender de funções do tema.

---

### `scss/style.scss` — Estilos + Utilitários

**Mudanças por bloco:**

**1. Classes utilitárias de cor (BLOQUEANTE):**
```scss
.has-primaria-color { color: variables.$primary-color !important; }
.has-secundaria-color { color: variables.$secondary-color !important; }
```
Incompatíveis com o WordPress — os slugs registrados em `functions.php` são `primary` e `secondary` (inglês), gerando `.has-primary-color` e `.has-secondary-color`. **Estas classes nunca são aplicadas pelo Gutenberg.**

**2. Remoção de `!important` em line-height — correto:**
```scss
// Antes: line-height: 1.6 !important;
// Depois: line-height: 1.6; // Allow overrides
```
Boa decisão — reduz especificidade desnecessária e permite overrides por bloco.

**3. Botão Pill Outline — bem estruturado:**
```scss
.wp-block-button__link { border-radius: 50px !important; ... }
```
- Estilos de hover/focus presentes ✅
- Exceção para Cover blocks documentada com comentário ✅
- Problema: cor `#0693e3` hardcoded (não usa `$secondary-color`) ⚠️

**4. `.wp-block-cover .has-secondary-color` — consistência com o acima:**
Usa `.has-secondary-color` (inglês) — correto em relação aos slugs WordPress, mas inconsistente com as classes utilitárias criadas acima (`.has-secundaria-color`).

**5. Fix Query Loop — correto e necessário:**
```scss
.wp-block-post-template { list-style: none !important; }
```
Fix legítimo — o WordPress gera `<ul>` para Query Loop que herda bullets indesejados.

**6. Arquivo sem newline ao final** — menor, mas causa diffs ruidosos.

---

### `scss/variables.scss` — Novo Token

```scss
$secondary-color: #2c5aa0;
```
Adição correta e necessária. `#2c5aa0` é semanticamente o azul UENF intermediário entre o primário (`#1d3771`) e o fundo claro. Consistente com a identidade visual.

---

### `docker-compose.yml` — Integração de Plugin

```yaml
- ../uenf-templates:/var/www/html/wp-content/plugins/uenf-templates
- wp_uploads:/var/www/html/wp-content/uploads
```

**Avaliação:**
- Volume nomeado `wp_uploads` — correto, garante persistência de uploads entre rebuilds
- `../uenf-templates` — caminho relativo que pressupõe estrutura de diretórios específica. Qualquer colaborador precisa clonar o plugin no mesmo diretório pai do tema.
- Sem `.env` para tornar o path configurável — pode ser aprimorado

---

## 3. Diagnóstico de Arquitetura

### Contexto: Separação Tema / Plugin
A deleção de 6 patterns e a adição do volume Docker confirmam uma **migração arquitetural em andamento**: os patterns estão sendo movidos do tema para o plugin `uenf-templates`. Esta é a decisão correta — patterns ficam no plugin, o tema fica responsável apenas por templates de página e estilos globais.

**Estado atual da migração:**
- ✅ Patterns PHP deletados do tema
- ✅ Plugin montado via Docker
- ⏳ `cct-patterns` CSS ainda carregado do tema (`/css/patterns.css`) — será migrado também?
- ⏳ `uenf_get_random_image()` no tema — OK se o plugin chamar funções do tema, mas documentar a dependência
- ❓ Os patterns registrados via `register_block_pattern()` no tema — foram removidos? Verificar se havia chamadas além dos arquivos PHP deletados

### Migração de Assets para `/assets/dist/css/`
Incompleta. Clarificar se:
- A intenção é migrar TODOS os CSS compilados para `/assets/dist/css/`
- Ou apenas o stylesheet principal (`style.min.css`) foi movido intencionalmente

---

## 4. Issues Priorizados para os Demais Agentes

### 🔴 Crítico
| ID | Issue | Arquivo |
|----|-------|---------|
| D1 | Classes `.has-primaria/secundaria-color` incompatíveis com slugs WordPress | `scss/style.scss` |

### 🟡 Médio
| ID | Issue | Arquivo |
|----|-------|---------|
| D2 | Migração de assets incompleta — múltiplos CSS ainda em `/css/` | `functions.php` |
| D3 | `add_editor_style()` usando CSS minificado de produção | `functions.php` |
| D4 | `uenf_get_random_image()` chama `placehold.co` sem guard de ambiente | `inc/template-tags.php` |
| D5 | Cor `#0693e3` hardcoded em botões de Cover — não usa variável | `scss/style.scss` |

### 🟢 Baixo
| ID | Issue | Arquivo |
|----|-------|---------|
| D6 | DocBlock incompleto em `uenf_get_random_image()` | `inc/template-tags.php` |
| D7 | `../uenf-templates` sem configuração via `.env` | `docker-compose.yml` |
| D8 | `style.scss` sem newline ao final | `scss/style.scss` |
| D9 | Dependência tema→plugin (`uenf_get_random_image`) não documentada | `inc/template-tags.php` |

---

## 5. Contexto para Agentes Específicos

**→ Isabela (Design System):** Atenção para D1 (slugs de cor), D5 (cor hardcoded) e a relação entre `$secondary-color` (#2c5aa0 no SCSS) e a cor `secondary` registrada via PHP (#222a3b — valores DIFERENTES).

**→ Marina (UX/UI):** Revisar estilos de botão pill e o elemento `.has-secondary-color` dentro de Cover blocks — este elemento tem comportamento visual de botão mas pode não ser semanticamente um botão.

**→ Camila (UX Writing):** Textos de botões e `screen-reader-text` nos templates não foram alterados nesta sessão, mas verificar template-tags.php para textos de acessibilidade existentes.

**→ Pedro (SEO/GEO):** Nenhum template PHP de página foi alterado. Foco: verificar se a remoção dos patterns afeta structured data ou headings em páginas que dependiam deles.

**→ Rafael (Frontend):** CSS compilado em `assets/dist/css/style.min.css` foi atualizado. Verificar se as mudanças visuais (botões pill, cores) estão corretamente compiladas.

**→ Diego (Backend):** Verificar se havia `register_block_pattern()` chamando os 6 arquivos deletados e se essas chamadas foram removidas.

**→ André (Segurança):** `uenf_get_random_image()` usa URL externa — verificar se há validação de `$width`/`$height` para evitar injeção de parâmetros maliciosos na URL.
