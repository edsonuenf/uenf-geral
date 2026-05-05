# Relatório de Correções — Dark/Light Mode UENF
**Fixer:** Carlos Correção 🔧
**Data:** 2026-04-15

---

## P0-01 + P0-02 — output_custom_css corrigido

**Arquivo:** `inc/customizer/class-dark-mode-manager.php`, `output_custom_css`, linhas ~674-685

**Antes:**
```php
// Modo claro — prefixo errado:
echo "  --cct-light-{$color_key}: {$color_value};\n";

// Modo escuro — underscore não convertido:
echo "  --cct-color-{$color_key}: {$color_value};\n";
```

**Depois:**
```php
// Ambos os modos — prefixo correto + underscore → hífen:
$css_key = str_replace('_', '-', $color_key);
echo "  --cct-color-{$css_key}: {$color_value};\n";
```

**Impacto:**
- Modo claro: 15 variáveis agora sobrescrevem o CSS estático corretamente
- Modo escuro: `text-secondary` e `text-muted` agora apontam para as variáveis certas

---

## P1-03 — sanitize_callback condicional para rgba

**Arquivo:** `inc/customizer/class-dark-mode-manager.php`, `add_dark_mode_settings`, linhas ~310-324

**Antes:** todos os `add_setting` de cor usavam `'sanitize_callback' => 'sanitize_hex_color'`

**Depois:**
```php
$rgba_keys = array('shadow', 'overlay');
$sanitize = in_array($color_key, $rgba_keys, true) ? 'sanitize_text_field' : 'sanitize_hex_color';
```

**Impacto:** `shadow` e `overlay` agora aceitam valores rgba sem serem sanitizados para string vazia.

---

## Resumo dos arquivos modificados

| Arquivo | Mudança |
|---------|---------|
| `inc/customizer/class-dark-mode-manager.php` | `output_custom_css`: prefixo + str_replace |
| `inc/customizer/class-dark-mode-manager.php` | `add_dark_mode_settings`: sanitize condicional |
