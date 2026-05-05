# UX Writing Report — uenf-dev | Run 2026-03-30-115040
**Agente:** Camila — UX Writer
**Data:** 2026-03-30

---

## Escopo
Textos afetados pelas alterações da branch `modelos-uenf`: `inc/template-tags.php` e strings relacionadas ao sistema de cores e botões. Textos não alterados nesta sessão são citados apenas quando há inconsistência relevante.

---

## Resumo

| Item | Status | Severidade |
|------|--------|------------|
| `?text=UENF+Image` — texto de placeholder em URL | 🟡 Atenção | Médio |
| `"Helper to get random placeholder images for patterns"` — DocBlock em inglês | 🟡 Atenção | Baixo |
| Strings de interface existentes — tom e clareza | ✅ Adequado | — |
| Textos de acessibilidade existentes | ✅ Bom | — |
| Internacionalização (i18n) — strings novas | ⚠️ Ausente | Médio |

---

## 🟡 Texto de Placeholder — `?text=UENF+Image`

```php
return "https://placehold.co/{$width}x{$height}/{$bg}/ffffff?text=UENF+Image";
```

**Problema:** O texto `UENF+Image` aparece renderizado na imagem placeholder como "UENF Image". Este texto:
1. **Está em inglês** — o conteúdo do tema é em português
2. **Não é descritivo** — não informa o tipo de conteúdo esperado na imagem
3. **Pode vazar para produção** — se um editor de conteúdo publicar sem substituir a imagem placeholder, o texto "UENF Image" ficará visível no site

**Alternativas melhores:**
```php
// Opção A — mais descritivo e em português:
$text = urlencode("Imagem {$width}×{$height}");

// Opção B — neutro e genérico:
$text = urlencode("UENF");

// Opção C — com indicação de placeholder para o editor:
$text = urlencode("[Inserir imagem aqui]");
```

**Recomendação:** Usar a opção A (`Imagem 1200×800`) — é mais útil para o editor que vê o preview no Gutenberg e entende imediatamente qual é o tamanho esperado.

---

## 🟡 DocBlock em inglês — inconsistência de idioma

```php
/**
 * Helper to get random placeholder images for patterns
 */
```

O comentário está em inglês, enquanto o restante do tema usa português nos comentários internos (ex.: `// Versão baseada no timestamp do arquivo para evitar cache`).

**Sugestão:**
```php
/**
 * Retorna URL de imagem placeholder aleatória para uso em modelos de demonstração.
 * Uso exclusivo em ambiente de desenvolvimento — não utilizar em produção.
 *
 * @param int $width  Largura da imagem em pixels. Padrão: 1200.
 * @param int $height Altura da imagem em pixels. Padrão: 800.
 * @return string URL da imagem placeholder (serviço externo placehold.co).
 */
```

---

## ⚠️ i18n Ausente — Strings não internacionalizadas

A função `uenf_get_random_image()` não tem strings de interface, então não há problema de i18n direto. Porém, se o texto da imagem (`UENF+Image`) for internacionalizável no futuro, a estrutura atual não suporta isso.

Os textos do Customizer encontrados durante a análise (`_e('Foco animado', 'cct')`, `_e('Buscar padrões...', 'cct')`) usam o textdomain `cct` enquanto o template-tags usa `cct-theme`. **Textdomains inconsistentes** no mesmo tema:
- `inc/template-tags.php` → `'cct-theme'`
- `inc/customizer/*` → `'cct'`

Isso não é uma alteração desta sessão, mas é um problema pré-existente relevante para tradutores.

---

## ✅ Textos de Acessibilidade — Bom nível

Os textos de screen reader existentes estão bem formulados:

```php
// Correto — adiciona contexto para leitores de tela sem ser visível:
__('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'cct-theme')
__('Edit <span class="screen-reader-text">%s</span>', 'cct-theme')
```

O padrão de esconder texto complementar com `.screen-reader-text` está correto e segue as boas práticas do WordPress.

**Único ponto de atenção:** `"Leave a Comment"` está em inglês em um tema em português. Não é uma alteração desta sessão, mas confirmar se esta string está sendo corretamente traduzida via `.po`/`.mo` ou se está hardcoded em inglês.

---

## ✅ Tom de Voz — Consistente com a UENF

As strings alteradas nesta sessão não introduzem tom inadequado. O tema mantém o padrão:
- Profissional e direto
- Sem gírias ou linguagem informal
- Instruções claras para o editor de conteúdo (comentários de código em português)

---

## Recomendações

1. Alterar `?text=UENF+Image` para `?text=Imagem+{width}x{height}` no helper de placeholder
2. Traduzir DocBlock para português com aviso de uso exclusivo em dev
3. **Acompanhar:** Alinhar textdomains (`cct` vs. `cct-theme`) em próxima sessão — não é desta branch
