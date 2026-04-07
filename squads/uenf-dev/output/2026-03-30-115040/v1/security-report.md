# Security Report — uenf-dev | Run 2026-03-30-115040
**Agente:** André — Security Reviewer
**Data:** 2026-03-30
**Metodologia:** OWASP Top 10 + WordPress Security Coding Standards

---

## Resumo Executivo

**Veredicto:** CONDITIONAL APPROVE — Nenhuma vulnerabilidade crítica introduzida. Um issue de médio impacto (`uenf_get_random_image` sem validação) e uma superfície de risco em produção a mitigar.

| Categoria | Status | Severidade |
|-----------|--------|------------|
| Direct file access protection | ✅ Implementado | — |
| Output escaping — template-tags.php | ✅ Correto | — |
| Nonces — code alterado nesta sessão | ✅ N/A (sem forms) | — |
| `uenf_get_random_image` — validação de input | ⚠️ Ausente | Médio |
| `placehold.co` em produção — superfície de risco | 🟡 Risco indireto | Médio |
| Padrões de hardcoded credentials | ✅ Ausente | — |
| Exposição de paths | ✅ Sem nova exposição | — |

---

## ✅ Direct File Access — Corretamente Protegido

`functions.php` tem **múltiplas** verificações de ABSPATH:
```php
if (!defined('ABSPATH')) { exit; }          // linha 33 — início do arquivo
if (!defined('ABSPATH')) { exit; }          // linha 313
if (!defined('ABSPATH')) { exit(); }        // linha 330
```

`inc/template-tags.php` **não tem** verificação de ABSPATH — mas isso é aceitável pois o arquivo é incluído via `require`/`get_template_part` pelo WordPress, nunca acessado diretamente como endpoint. A proteção em `functions.php` é suficiente.

---

## ✅ Output Escaping — `inc/template-tags.php` exemplar

O código existente em `template-tags.php` usa escaping correto e consistente:

```php
esc_attr(get_the_date(DATE_W3C))           // atributo HTML
esc_html(get_the_date())                   // texto HTML
esc_url(get_permalink())                   // URLs
wp_kses(...)                               // HTML permitido
wp_kses_post(get_the_title())              // HTML de post
esc_html__('Posted in %1$s', 'cct-theme')  // i18n + escape
```

A nova função `uenf_get_random_image()` **retorna** uma string sem fazer output direto — isso é correto. O risco está em **como o retorno é usado** (ver issue abaixo).

---

## ⚠️ MÉDIO — `uenf_get_random_image()` sem validação de parâmetros

```php
function uenf_get_random_image( $width = 1200, $height = 800 ) {
    $colors = ['1d3771', '2c5aa0', '28a745', 'dc3545', 'e0a800'];
    $bg = $colors[ array_rand( $colors ) ];
    return "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image";
}
```

**Vetor de risco:** Os parâmetros `$width` e `$height` são interpolados diretamente na URL sem sanitização. Se o retorno for usado com `echo` sem `esc_url()`:

```php
// Uso inseguro (potencial XSS se $width vier de input externo):
echo '<img src="' . uenf_get_random_image($_GET['w'], $_GET['h']) . '">';

// Uso correto:
echo '<img src="' . esc_url(uenf_get_random_image(1200, 800)) . '">';
```

**Avaliação de probabilidade:** Baixa — a função provavelmente é chamada apenas com valores literais pelo desenvolvedor, não com input de usuário. Mas a ausência de validação interna cria um contrato inseguro para qualquer desenvolvedor que use a função no futuro.

**Correção recomendada:**
```php
function uenf_get_random_image( $width = 1200, $height = 800 ) {
    $width  = absint( $width )  ?: 1200;  // force int positivo, fallback 1200
    $height = absint( $height ) ?: 800;   // force int positivo, fallback 800
    $colors = ['1d3771', '2c5aa0', '28a745', 'dc3545', 'e0a800'];
    $bg     = $colors[ array_rand( $colors ) ];
    return esc_url( "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image" );
}
```

---

## 🟡 `placehold.co` — Superfície de Risco em Produção

Além do risco SEO apontado por Pedro, há uma dimensão de segurança:

1. **Dependência de terceiro não controlado:** `placehold.co` é um serviço externo. Se o domínio for comprometido ou redirecionado para conteúdo malicioso, o tema servirá imagens de um servidor externo potencialmente hostil em páginas da UENF.

2. **CSP (Content Security Policy):** Se a UENF implementar CSP headers no futuro, `placehold.co` precisará ser explicitamente allowlistado. URLs de terceiros em imagens são um vetor comum de violação de CSP.

3. **Mitigação:** O guard de `WP_DEBUG` recomendado por Lucas e Diego resolve completamente — a função ficaria inativa em produção.

```php
if ( ! ( defined('WP_DEBUG') && WP_DEBUG ) ) {
    return ''; // Nunca retorna URL externa em produção
}
```

---

## ✅ Nonces — Sem Forms Novos

As alterações desta sessão não introduzem novos formulários, endpoints AJAX ou ações administrativas. O nonce existente em `functions.php` (linha 987) para `cct_extensions_nonce` não foi alterado e está correto.

---

## ✅ Sem Credenciais Hardcoded

Nenhuma senha, token, chave de API ou credencial encontrada nas alterações desta sessão.

---

## ✅ `docker-compose.yml` — Segurança de Desenvolvimento

As credenciais no `docker-compose.yml` são valores padrão de desenvolvimento (`wordpress`/`wordpress`). Aceitável para ambiente local — **nunca usar em produção**. Não é responsabilidade do tema, mas documentar no README é boa prática.

---

## Checklist de Segurança

| Critério | Status |
|----------|--------|
| ABSPATH em files públicos | ✅ |
| Output escaping em template-tags | ✅ Exemplar |
| `uenf_get_random_image` com `absint()` | ❌ Ausente — corrigir |
| `uenf_get_random_image` retorna `esc_url()` | ❌ Ausente — corrigir |
| Guard de produção para `placehold.co` | ❌ Ausente — adicionar |
| Nonces em forms/AJAX novos | ✅ N/A nesta sessão |
| Sem credenciais hardcoded | ✅ |
| Sem novos endpoints vulneráveis | ✅ |
