# Backend Test Report — uenf-dev run 2026-03-31

**Autor:** Diego (Backend Tester)
**Branch:** `aparencia`
**Data:** 2026-03-31

---

## Status Geral

**APROVADO COM RESSALVAS** — Nenhum bug crítico. Dois bugs de severidade alta identificados.

---

## A. Hooks `customize_register` — Sequência de Prioridades

**Bug A-1 (ALTA): Painel `uenf_panel` registrado duas vezes.**
- `functions.php:152` registra `uenf_panel` na prioridade 5.
- `inc/customizer/customizer-loader.php:82` registra o mesmo ID na prioridade 10.
- `WP_Customize_Manager::add_panel()` ignora duplicatas silenciosamente — não explode, mas é bug de arquitetura.

**Sequência verificada — correta:**
| Prioridade | Ação |
|------------|------|
| 5 | `uenf_panel` criado |
| 10 | `cct_customize_register` + `load_modules` registram managers |
| 15 | Managers instanciados, seções com `'panel' => 'uenf_panel'` registradas |
| 999 | `$wp_customize->sections()` populado — mutação efetiva |
| 1000 | Seções em `uenf_panel` — renomeação funciona |
| 1001 | `WP_Customize_Control` disponível — anonymous class funciona |

---

## B. Mutação de Objetos no Hook 999

**Verificado — correto:** `$wp_customize->sections()` retorna array com handles de objetos PHP. `foreach` faz `$section` ser handle (referência), não cópia. `$section->panel = 'uenf_panel'` muta o objeto original. Efetivo.

**Verificado — correto:** `remove_panel()` remove apenas o painel do array `$this->panels`. As seções permanecem em `$this->sections`. Como a mutação de `$section->panel` ocorre antes de `remove_panel()`, as seções ficam corretamente atribuídas ao `uenf_panel`.

**Bug B-1 (MÉDIA):** O segundo loop captura apenas seções `cct_*` sem painel. Seções sem prefixo `cct_` pertencentes a painéis legados ficam órfãs após `remove_panel()`. Risco latente se plugins registrarem seções nesses painéis.

---

## C. Anonymous Class no Hook 1001

**Verificado — correto em todos os pontos:**
- Sintaxe `new class(...) extends WP_Customize_Control` válida desde PHP 7.0; tema requer PHP 7.4.
- `WP_Customize_Control` disponível — carregada pelo WP antes de `customize_register` disparar.
- `$wp_customize->add_control()` aceita objetos; verifica `instanceof WP_Customize_Control` — anonymous class herda, passa.
- `render_content()` vazio funcional; CSS `display:none !important` oculta o wrapper.

---

## D. `uenf_get_random_image()` — Hardening

- **Guard `WP_DEBUG`:** ✅ Correto. Retorna `''` em produção.
- **`absint($width) ?: 1200`:** ✅ Correto. `absint(0)` retorna `0` (falsy) → fallback `1200` ativado.
- **`esc_url()`:** ✅ Correto. Variáveis já sanitizadas. Camada defensiva adicional válida.

**Observação (BAIXA):** Nome `uenf_get_random_image()` não indica restrição `WP_DEBUG` — risco de uso inadvertido por outro desenvolvedor.

---

## E. Remoção de `cct_menu_settings`

**Grep em todo o projeto:** Zero referências a `cct_menu_settings` em arquivos PHP ativos.

**Verificado — seguro:** A seção equivalente existe como `menu_settings` em `class-menu-customizer.php` com todos os settings. Remoção sem perda funcional.

---

## F. Compatibilidade WordPress/PHP

**PHP:** `composer.json` declara `"php": ">=7.4"`. Todos os recursos usados estão cobertos:
- Anonymous classes: PHP 7.0+
- Spread em `array_merge`: PHP 5.6+
- Short list em foreach: PHP 7.1+

**WordPress:** `style.css` **não declara `Requires at least`**. O tema usa `theme.json` (requer WP 5.8+) mas não documenta o requisito mínimo no header — impede que o WordPress bloqueie ativação em versões incompatíveis.

---

## Bugs Encontrados

| Bug | Severidade | Arquivo:Linha | Recomendação |
|---|---|---|---|
| Painel `uenf_panel` registrado duas vezes | **Alta** | `functions.php:152` / `customizer-loader.php:82` | Remover registro do loader |
| `setInterval(800)` sem cleanup — polling CPU contínuo | **Alta** | `functions.php:651` | Substituir por eventos nativos `wp.customize.section` |
| Seções sem prefixo `cct_` de painéis legados ficam órfãs após `remove_panel()` | **Média** | `functions.php:362–376` | Ampliar loop para capturar por `$section->panel === $old_panel_id` |
| `style.css` sem `Requires at least: 5.8` | **Média** | `style.css:1` | Adicionar `Requires at least: 5.8` no header |
| `error_log()` sem guard `WP_DEBUG` em `customizer-loader.php` | **Baixa** | `customizer-loader.php:68,79,205-211` | Envolver com `if (defined('WP_DEBUG') && WP_DEBUG)` |
| Nome `uenf_get_random_image()` não indica restrição `WP_DEBUG` | **Baixa** | `inc/template-tags.php:147` | Adicionar `@internal` na docblock |

---

## Recomendações

1. Eliminar registro duplicado de `uenf_panel` no `customizer-loader.php` linhas 82-87.
2. Substituir `setInterval` por eventos nativos do Customizer JS.
3. Declarar `Requires at least: 5.8` em `style.css`.
4. Ampliar loop de migração (hook 999) para cobrir seções de painéis legados sem prefixo `cct_`.
5. Guardar todos os `error_log()` com `WP_DEBUG` em `customizer-loader.php`.
6. Validar slugs em `theme.json` para cobrir todos os valores do antigo `add_theme_support`.
