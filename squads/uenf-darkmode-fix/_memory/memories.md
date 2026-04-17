# Squad Memory — uenf-darkmode-fix

## Run 2026-04-15-120000

**Bugs encontrados e corrigidos em `class-dark-mode-manager.php`:**

- P0-01: Modo claro usava prefixo `--cct-light-{key}` mas CSS usa `--cct-color-{key}` → corrigido com `--cct-color-{css_key}`
- P0-02: Chaves com underscore (`text_secondary`, `text_muted`) geravam variáveis erradas → corrigido com `str_replace('_', '-', $color_key)`
- P1-03: `shadow` e `overlay` têm valores rgba mas usavam `sanitize_hex_color` → corrigido com sanitize condicional `sanitize_text_field`

**Aprovado para commit mas usuário escolheu encerrar sem commit.**
