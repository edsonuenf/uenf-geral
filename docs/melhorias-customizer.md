# Melhorias para o Customizer do Tema UENF

## 📌 Visão Geral
Este documento descreve as melhorias necessárias para o sistema de personalização do tema UENF Geral, visando resolver problemas atuais e implementar novas funcionalidades.

## 🔧 Problemas Atuais

1. **Inconsistência nos Valores Padrão**
   - Valores definidos em múltiplos locais (`style.css`, `customizer.php`)
   - Dificuldade de manutenção e atualização
   - Risco de conflitos entre estilos

2. **Estrutura de Arquivos**
   - Variáveis CSS não estão centralizadas
   - Ordem de carregamento dos estilos pode causar problemas de sobrescrita
   - Código CSS duplicado

3. **Falta de Funcionalidades**
   - Não há opção para redefinir as configurações
   - Feedback visual limitado durante as alterações
   - Documentação insuficiente

## 🎯 Melhorias Propostas

### 1. Padronização de Valores

#### 1.1 Constantes para Valores Padrão
```php
// Em functions.php
define('CCT_DEFAULT_PRIMARY_COLOR', '#1d3771');
define('CCT_DEFAULT_TEXT_COLOR', '#333333');
// ... outras constantes
```

#### 1.2 Unificação das Definições
- Criar arquivo `inc/customizer/defaults.php` para armazenar todos os valores padrão
- Garantir que `style.css` e `customizer.php` usem as mesmas constantes

### 2. Estrutura de Arquivos

#### 2.1 Organização do CSS
```
css/
  ├── variables.css    # Todas as variáveis CSS
  ├── base/           
  │   └── typography.css
  ├── components/
  └── customizer/      # Estilos específicos do customizer
```

#### 2.2 Ordem de Carregamento
1. `variables.css` (valores padrão)
2. Outros estilos do tema
3. Estilos do Customizer (inline)

### 3. Implementação do Botão "Redefinir"

#### 3.1 Interface
- Adicionar botão "Redefinir Padrões" na barra superior do Customizer
- Modal de confirmação antes de redefinir

#### 3.2 Código
```php
// Adicionar controle para o botão de reset
$wp_customize->add_control('reset_theme_options', array(
    'type' => 'button',
    'settings' => array(),
    'priority' => 1000,
    'section' => 'cct_main_colors',
    'input_attrs' => array(
        'value' => __('Redefinir Padrões', 'cct'),
        'class' => 'button button-primary',
    ),
));
```

### 4. Otimizações de Desempenho

#### 4.1 Redução de Código
- Remover duplicações de estilos
- Usar herança CSS de forma eficiente
- Minificar o CSS gerado

#### 4.2 Cache
- Implementar cache para o CSS gerado
- Invalidar cache apenas quando necessário

### 5. Documentação

#### 5.1 Comentários no Código
```php
/**
 * Define as configurações de cores do tema
 * 
 * @param WP_Customize_Manager $wp_customize Instância do Customizer
 * @return void
 */
function cct_register_color_settings($wp_customize) {
    // Implementação...
}
```

#### 5.2 Guia do Desenvolvedor
- Documentar a estrutura das opções
- Explicar como adicionar novas seções/controles
- Fornecer exemplos de uso

## 📅 Plano de Implementação

1. **Fase 1: Preparação**
   - [ ] Criar estrutura de arquivos
   - [ ] Definir constantes de valores padrão
   - [ ] Documentar estrutura atual

2. **Fase 2: Implementação**
   - [ ] Atualizar sistema de variáveis CSS
   - [ ] Implementar botão de redefinição
   - [ ] Otimizar carregamento de estilos

3. **Fase 3: Testes**
   - [ ] Testar em diferentes navegadores
   - [ ] Verificar desempenho
   - [ ] Validar acessibilidade

4. **Fase 4: Documentação**
   - [ ] Atualizar documentação técnica
   - [ ] Criar guia do usuário
   - [ ] Registrar mudanças no changelog

## 🔍 Considerações Adicionais

- Garantir compatibilidade com versões antigas
- Manter a retrocompatibilidade
- Testar com plugins populares
- Considerar desempenho em dispositivos móveis

---

Atualizado em: 06/06/2025
