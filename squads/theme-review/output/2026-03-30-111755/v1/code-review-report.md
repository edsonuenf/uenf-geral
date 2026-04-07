# Code Review Report — theme-review
**Data:** 2026-03-30
**Branch:** modelos-uenf
**Executado por:** Code Reviewer (Opensquad)

---

## Resumo Executivo

| Arquivo | Status | Severidade |
|---------|--------|------------|
| `functions.php` | ✅ Aprovado com observações | Baixa |
| `inc/template-tags.php` | ⚠️ Aprovado com pendências | Média |
| `scss/style.scss` | ⚠️ Aprovado com pendências | Média |
| `scss/variables.scss` | ✅ Aprovado | Baixa |
| `docker-compose.yml` | ⚠️ Aprovado com observações | Baixa |
| Patterns deletados (6) | ℹ️ Informacional | — |

**Veredicto geral: CONDITIONAL APPROVE** — Aprovado para continuar desenvolvimento, mas com 3 itens que devem ser resolvidos antes do merge em `main`.

---

## Análise por Arquivo

### 1. `functions.php`

**Alterações:** Refatoração dos caminhos de assets de `css/` para `assets/dist/css/`.

#### Aprovado ✅
- A refatoração para `assets/dist/css/style.min.css` segue boas práticas de organização de assets compilados.
- Uso correto de `file_exists()` antes de `filemtime()` evita avisos PHP.
- Sem quebra de lógica detectada.

#### Observação 🟡
- **`add_editor_style('assets/dist/css/style.min.css')`**: Usar o CSS minificado para o editor pode dificultar a depuração no Gutenberg. Considere manter um arquivo não-minificado para o editor em ambiente de dev, ou gerar um `editor-style.css` separado apenas com variáveis e estilos de bloco.

---

### 2. `inc/template-tags.php`

**Alterações:** Adição da função `uenf_get_random_image()` + correção do final de arquivo.

#### Aprovado ✅
- Uso correto de `if ( ! function_exists() )` garante compatibilidade com plugins que possam redeclarar.
- Newline adicionado ao final do arquivo — correto.

#### Problemas detectados ⚠️

**P1 — Dependência de serviço externo (Severidade: Média)**
```php
return "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image";
```
- A função chama `placehold.co`, um serviço externo. Em produção, isso cria:
  1. Dependência de disponibilidade do serviço
  2. Potencial lentidão em ambientes sem internet
  3. Preocupações com LGPD (requisição externa ao carregar página)
- **Recomendação:** Esta função é claramente para uso em padrões de demonstração/desenvolvimento. Adicione um comentário explícito: `// Helper para uso exclusivo em patterns de desenvolvimento — não usar em produção.`

**P2 — Documentação ausente (Severidade: Baixa)**
```php
function uenf_get_random_image( $width = 1200, $height = 800 ) {
```
- Falta DocBlock com `@param`, `@return` e descrição da função, seguindo WordPress Coding Standards.
- **Recomendação:**
```php
/**
 * Retorna URL de imagem placeholder aleatória para uso em padrões.
 *
 * @param int $width  Largura da imagem em pixels. Padrão: 1200.
 * @param int $height Altura da imagem em pixels. Padrão: 800.
 * @return string URL da imagem placeholder.
 */
```

---

### 3. `scss/style.scss`

**Alterações:** Classes utilitárias de cor, estilos de botão pill, fix de Query Loop.

#### Aprovado ✅
- Remoção de `!important` de `line-height` em h2/h3 — correto, reduz especificidade desnecessária.
- Estilos de botão pill bem estruturados com hover/focus states.
- Fix de `list-style` no `.wp-block-post-template` resolve bug visual real.

#### Problemas detectados ⚠️

**P3 — Inconsistência de nomes de classe (Severidade: Média)**
```scss
.has-primaria-color { ... }      // linha adicionada (português)
.has-secundaria-color { ... }    // linha adicionada (português)

.wp-block-cover .has-secondary-color { ... }  // mais abaixo (inglês!)
```
- `.has-secundaria-color` e `.has-secondary-color` são **classes diferentes**. Se o editor Gutenberg aplica `.has-secondary-color` (padrão WordPress), os estilos de `.has-secundaria-color` nunca serão aplicados.
- **Recomendação crítica:** Verificar como as cores estão registradas no `theme.json` ou via `add_theme_support('editor-color-palette')`. Os slugs de cor determinam os nomes das classes. Padronizar em apenas uma nomenclatura.

**P4 — Cor hardcoded em vez de variável (Severidade: Baixa)**
```scss
background-color: #0693e3 !important;  // em 2 lugares
```
- `#0693e3` aparece hardcoded mas não corresponde nem a `$primary-color` (#1d3771) nem a `$secondary-color` (#2c5aa0).
- **Recomendação:** Criar `$button-cover-color: #0693e3` em `variables.scss` ou usar uma variável existente.

**P5 — Arquivo sem newline ao final (Severidade: Baixa)**
- O arquivo `scss/style.scss` não termina com newline (`\n`). Causa diffs ruidosos no git.
- **Recomendação:** Adicionar newline ao final do arquivo.

---

### 4. `scss/variables.scss`

**Alterações:** Adição de `$secondary-color: #2c5aa0`.

#### Aprovado ✅
- Adição correta e necessária, mantém consistência com `$primary-color`.
- Valor `#2c5aa0` é a variante mais clara do azul UENF — semanticamente coerente.

---

### 5. `docker-compose.yml`

**Alterações:** Novo volume para plugin `uenf-templates` + volume nomeado para uploads.

#### Aprovado ✅
- Volume nomeado `wp_uploads` para uploads persistentes — boa prática.

#### Observação 🟡
- `../uenf-templates:/var/www/html/wp-content/plugins/uenf-templates` usa caminho relativo que assume estrutura específica de diretórios. Outros desenvolvedores precisarão ter o plugin em `../uenf-templates` relativo ao projeto.
- **Recomendação:** Documentar no README que `uenf-templates` deve ser clonado no diretório pai, ou tornar o path configurável via `.env`.

---

### 6. Patterns deletados (informacional)

Seis arquivos de padrões foram deletados:
- `contact-card.php`, `faq-accordion.php`, `faq-tabs.php`
- `highlights-grid.php`, `news-list.php`, `pricing-table.php`

**Observação:** Se esses padrões foram migrados para outro local (ex.: plugin `uenf-templates`), está correto. Se foram removidos permanentemente, verificar se há referências a eles em outros arquivos.

---

## Itens para resolver antes do merge

| # | Arquivo | Problema | Prioridade |
|---|---------|----------|------------|
| P3 | `scss/style.scss` | Inconsistência `.has-secundaria-color` vs `.has-secondary-color` | 🔴 Alta |
| P1 | `inc/template-tags.php` | `placehold.co` sem comentário de uso restrito | 🟡 Média |
| P4 | `scss/style.scss` | Cor `#0693e3` hardcoded sem variável | 🟡 Média |

## Sugestões não-bloqueantes

- Considerar editor style separado do CSS minificado de produção
- DocBlock na função `uenf_get_random_image()`
- Newline ao final de `style.scss`
- Documentar dependência de `../uenf-templates` no README
