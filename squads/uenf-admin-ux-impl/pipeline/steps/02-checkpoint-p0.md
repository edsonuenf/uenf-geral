---
id: checkpoint-p0
type: checkpoint
---

# Checkpoint 1 — Revisão dos Bugs P0

Felipe concluiu as correções P0. Antes de prosseguir para as melhorias UX/UI, revise os 3 fixes:

1. **Reset de Extensões** — `reset_all_extensions()` → `reset_all_settings()` em `functions.php`
2. **Nomes das extensões** — `$extension['title']` → `$extension['name']` em `functions.php`
3. **Nonce do Reset** — campo `reset_nonce` → `nonce` nos formulários de reset

O que você quer fazer?

1. **Aprovado — continuar para P1** *(Recomendado)* — Diana começa as melhorias UX
2. **Revisar e ajustar** — Edson quer modificar algo antes de continuar
