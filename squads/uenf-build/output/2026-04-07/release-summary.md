# Release Summary — uenf-geral-claude v0.1.0

**Data:** 2026-04-07
**Branch:** aparencia
**Build:** Webpack 5.101.0 — modo production

---

## Pacote

| Item | Detalhe |
|------|---------|
| Arquivo | `uenf-geral-claude-v0.1.0.zip` |
| Tamanho | 14 MB |
| Total de arquivos | 301 |
| Localização | `squads/uenf-build/output/2026-04-07/` |

## Assets Compilados

| Arquivo | Tamanho | Notas |
|---------|---------|-------|
| `assets/dist/css/style.min.css` | 11 KB | SCSS compilado + autoprefixado + minificado |
| `assets/dist/js/main.js` | 4,9 KB | JS transpilado (Babel) + minificado (sem console.log) |
| Source maps | — | Não gerados (correto para produção) |

## Commits desta Release (desde v0.0.3)

| Hash | Mensagem |
|------|----------|
| f727706 | Segurança e qualidade: corrige issues críticos identificados pelos agentes de QA |
| 34d1fe1 | Atualiza tema: header, customizer, SCSS e documentação |
| db90421 | feat: melhorias de responsabilidade mobile (header fixo, breadcrumbs e painel de atalhos) |
| b3f1050 | Adiciona screenshot.png do tema |
| a2a6f09 | Lista de posts: exibição responsiva, filtro de categorias e design system |
| c5d7a47 | Reverte alterações de espaçamento do hero (hero-header-fix.css) |
| 04e6787 | Corrige espaçamento hero/conteúdo e parse error em content.php |
| 4716feb | Design system: tokeniza #0693e3 como acento no tema |
| 069949e | Reorganiza Customizer: consolida todos os painéis sob uenf_panel |
| 8384f98 | Merge branch 'modelos-uenf' |
| 6b91e1c | Migra patterns para plugin e limpa arquivos obsoletos |
| 88d2e65 | Update GitHub Theme URI in style.css |

## Como Instalar

1. Acesse o painel WordPress → **Aparência → Temas → Adicionar Novo**
2. Clique em **Fazer upload do tema**
3. Selecione o arquivo `uenf-geral-claude-v0.1.0.zip`
4. Clique em **Instalar agora**
5. Ative o tema

**Ou via WP-CLI:**
```bash
wp theme install uenf-geral-claude-v0.1.0.zip --activate
```

## Score de Qualidade (uenf-dev — última execução)

| Área | Score |
|------|-------|
| Segurança | 7,0/10 → ~8,5/10 após correções |
| TDD (JS) | 23 PASS / 1 FAIL (TC-008: truncate(null)) |
| Issues críticos corrigidos | 9 de 15 identificados |

## Pendências (não-bloqueantes)

Ver `squads/uenf-dev/output/2026-04-07-100559/v1/backlog-issues.md` para lista completa.

Principais:
- BACK-001: Duplicação `customize_register` em `functions.php`
- BACK-002: `ob_end_clean()` antes de `ABSPATH`
- TC-008: `truncate(null, 10)` causa TypeError
