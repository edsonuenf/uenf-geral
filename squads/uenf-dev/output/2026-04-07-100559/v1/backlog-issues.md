# Backlog de Pendências — Sessão 2026-04-07

Issues identificados nesta sessão e **não corrigidos**. Ordenados por prioridade de resolução.

---

## Prioridade Alta

### BACK-001 — Duplicação de carregamento de módulos (`customize_register`)

| Campo            | Detalhe                                                                 |
|------------------|-------------------------------------------------------------------------|
| **Arquivo**      | `functions.php`                                                         |
| **Severidade**   | Alta                                                                    |
| **Esforço**      | M (médio)                                                               |
| **Risco**        | Instanciação dupla de hooks/módulos; pode causar comportamento imprevisível em ambiente de produção |
| **Ação sugerida**| Auditar todos os `add_action('customize_register', ...)` e garantir registro único por callback |

---

### BACK-002 — `ob_end_clean()` chamado antes da verificação de `ABSPATH`

| Campo            | Detalhe                                                                 |
|------------------|-------------------------------------------------------------------------|
| **Arquivo**      | Arquivo de inicialização do tema (não identificado com precisão)        |
| **Severidade**   | Alta                                                                    |
| **Esforço**      | P (pequeno)                                                             |
| **Risco**        | Acesso direto ao arquivo pode não ser bloqueado antes do `ob_end_clean`, expondo saída parcial |
| **Ação sugerida**| Mover a verificação `if (!defined('ABSPATH')) exit;` para a primeira linha do arquivo |

---

## Prioridade Média

### MED-003 — `<p data-excerpt>` invisível sem JavaScript

| Campo            | Detalhe                                                                 |
|------------------|-------------------------------------------------------------------------|
| **Arquivo**      | Template(s) de listagem de posts / cards                                |
| **Severidade**   | Média                                                                   |
| **Esforço**      | P (pequeno)                                                             |
| **Risco**        | Conteúdo do excerpt não acessível para usuários sem JS; afeta SEO e leitores de tela |
| **Ação sugerida**| Renderizar o excerpt com CSS visível por padrão; usar JS apenas para comportamentos adicionais (truncate, expand) |

---

### TC-008 — Teste de truncate com valor `null` falhando

| Campo            | Detalhe                                                                 |
|------------------|-------------------------------------------------------------------------|
| **Arquivo**      | Suite de testes frontend (função `truncate`)                            |
| **Severidade**   | Média                                                                   |
| **Esforço**      | P (pequeno)                                                             |
| **Risco**        | Regressão em contextos onde título ou excerpt retorna `null` (posts sem conteúdo) |
| **Ação sugerida**| Adicionar guard `if (!str) return '';` ou equivalente antes do truncate |

---

## Prioridade Baixa / Auditoria Manual Pendente

### AUD-001 — Formulário em `front-page.php` sem auditoria de segurança

| Campo            | Detalhe                                                                 |
|------------------|-------------------------------------------------------------------------|
| **Arquivo**      | `front-page.php`                                                        |
| **Severidade**   | A definir (depende da auditoria)                                        |
| **Esforço**      | M (médio)                                                               |
| **Ação sugerida**| Verificar nonce, sanitização de inputs e escape de outputs              |

---

### AUD-002 — `inc/security.php` não auditado

| Campo            | Detalhe                                                                 |
|------------------|-------------------------------------------------------------------------|
| **Arquivo**      | `inc/security.php`                                                      |
| **Severidade**   | A definir (depende da auditoria)                                        |
| **Esforço**      | M (médio)                                                               |
| **Ação sugerida**| Revisar implementação completa de medidas de segurança do arquivo       |

---

### AUD-003 — Cabeçalhos HTTP de segurança não verificados

| Campo            | Detalhe                                                                 |
|------------------|-------------------------------------------------------------------------|
| **Arquivo**      | Configuração de servidor / `functions.php`                              |
| **Severidade**   | Média                                                                   |
| **Esforço**      | M (médio)                                                               |
| **Ação sugerida**| Verificar presença de `X-Content-Type-Options`, `X-Frame-Options`, `Content-Security-Policy`, `Referrer-Policy` |

---

## Resumo

| Issue     | Severidade | Esforço | Status        |
|-----------|:----------:|:-------:|---------------|
| BACK-001  | Alta       | M       | Não corrigido |
| BACK-002  | Alta       | P       | Não corrigido |
| MED-003   | Média      | P       | Não corrigido |
| TC-008    | Média      | P       | Não corrigido |
| AUD-001   | A definir  | M       | Não auditado  |
| AUD-002   | A definir  | M       | Não auditado  |
| AUD-003   | Média      | M       | Não auditado  |

**Legenda de esforço:** P = Pequeno (< 1h) / M = Médio (1–4h) / G = Grande (> 4h)
