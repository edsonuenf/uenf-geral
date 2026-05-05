# Relatório de Auditoria — Dark/Light Mode UENF
**Auditora:** Lara Luz 🔍
**Data:** 2026-04-15

---

## Bug P0-01 — Modo Claro: prefixo CSS errado

**Arquivo:** `inc/customizer/class-dark-mode-manager.php`, `output_custom_css`, linha 676

**Evidência:**
```php
// PHP gera (ERRADO):
echo "  --cct-light-{$color_key}: {$color_value};\n";

// CSS usa:
:root { --cct-color-background: #ffffff; }
```

**Impacto:** 15 cores do modo claro nunca são aplicadas.  
**Correção:** `--cct-light-` → `--cct-color-`

---

## Bug P0-02 — Underscore vs Hífen em 2 variáveis

**Arquivo:** `inc/customizer/class-dark-mode-manager.php`, `output_custom_css`, linhas 676 e 683

**Evidência:**
```php
// PHP gera: --cct-color-text_secondary (underscore)
// CSS usa:  --cct-color-text-secondary (hífen)
```

**Impacto:** `text_secondary` e `text_muted` não funcionam em nenhum modo.  
**Correção:** `str_replace('_', '-', $color_key)` ao montar o nome da variável.

---

## Bug P1-03 — sanitize_hex_color para rgba

**Arquivo:** `inc/customizer/class-dark-mode-manager.php`, `add_dark_mode_settings`, linhas 310-324

**Evidência:**
```php
'shadow'  => 'rgba(0, 0, 0, 0.1)',       // não é hex
'overlay' => 'rgba(255, 255, 255, 0.9)', // não é hex
// mas ambos usam 'sanitize_callback' => 'sanitize_hex_color'
```

**Impacto:** se salvo via color picker, valor fica vazio.  
**Correção:** sanitize condicional por chave.

---

## Tabela Resumo

| Bug | Sev | Correção |
|-----|-----|---------|
| Prefixo `--cct-light-` modo claro | P0 | `--cct-color-` |
| `text_secondary`/`text_muted` underscore | P0 | `str_replace('_','-')` |
| `sanitize_hex_color` para rgba | P1 | sanitize condicional |
