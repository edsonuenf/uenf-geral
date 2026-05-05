# Build Report — branch `aparencia`
**Engenheiro:** Carlos (Build & Sync Engineer)  
**Data:** 2026-04-10

---

## 1. lint:css — stylelint

**Status:** ❌ FALHOU (configuração ausente — pré-existente)

```
Error: No configuration provided for css/components/footer.css
```

**Diagnóstico:** Não existe arquivo `.stylelintrc` ou `stylelint.config.js` no projeto. O script `lint:css` está configurado no `package.json` mas nunca foi inicializado. Este é um problema **pré-existente**, não introduzido pela branch `aparencia`.

**Impacto no build:** Nenhum — o lint não bloqueia o build.  
**Ação recomendada:** Criar `.stylelintrc.json` mínimo em PR de infra posterior.

---

## 2. lint:js — eslint

**Status:** ❌ FALHOU (configuração ausente — pré-existente)

```
ESLint couldn't find a configuration file
```

**Diagnóstico:** Não existe `.eslintrc` ou `eslint.config.js`. Mesmo problema do lint:css. Pré-existente.

**Impacto no build:** Nenhum.

---

## 3. build — webpack

**Status:** ✅ PASSOU

```
asset css/style.min.css 11.6 KiB [emitted] [minimized]
asset js/main.js 4.84 KiB [emitted] [minimized]
webpack 5.101.0 compiled successfully in 4142 ms
```

**Arquivo gerado:** `assets/dist/css/style.min.css` — 12K  
**Aviso:** Browserslist data está desatualizado (9 meses) — não afeta o build. Rodar `npx update-browserslist-db@latest` na próxima atualização de deps.

---

## 4. Verificação dos assets gerados

| Arquivo | Existe? | Tamanho | Timestamp |
|---------|---------|---------|-----------|
| `assets/dist/css/style.min.css` | ✅ | 12K | 2026-04-10 10:11 |
| `assets/dist/js/main.js` | ✅ | 4.84 KiB | 2026-04-10 10:11 |
| `css/style.min.css` | ✅ | 11K | 2026-04-09 17:23 (desatualizado) |

**Observação:** `css/style.min.css` não é atualizado pelo Webpack (output é `assets/dist/`). Precisa ser copiado manualmente no sync-wp.

---

## Resumo

| Etapa | Status |
|-------|--------|
| lint:css | ❌ Config ausente (pré-existente) |
| lint:js | ❌ Config ausente (pré-existente) |
| build | ✅ Sucesso |
| style.min.css gerado | ✅ |

**Build pronto para sync.**

---

*Relatório gerado por Carlos — uenf-aparencia-release pipeline — Step 4 de 9*
