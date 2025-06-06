# Addon: Page Visibility

Este addon permite ocultar páginas específicas dos menus de navegação do site, mesmo quando essas páginas estão incluídas em menus personalizados do WordPress.

## Funcionalidades

- Adiciona uma opção para ocultar páginas dos menus
- Interface simples no editor de páginas
- Compatível com o editor de menus nativo do WordPress
- Compatível com o plugin Polylang

## Instalação

1. O addon já está integrado ao tema UENF Geral
2. Ele é carregado automaticamente através do arquivo `functions.php`
3. Nenhuma configuração adicional é necessária

## Como usar

1. Vá até a edição de qualquer página
2. Localize a caixa "Visibilidade no Menu" no painel lateral
3. Marque a opção "Ocultar esta página dos menus de navegação"
4. Atualize a página
5. A página será removida de todos os menus do site

## Solução de Problemas

### Páginas não estão sendo ocultadas

1. Verifique se o cache do WordPress foi limpo
2. Verifique se há conflitos com outros plugins (especialmente plugins de cache)
3. Verifique se o Polylang está atualizado
4. Verifique os logs de erro do WordPress para mensagens relevantes

### O meta box não aparece

1. Verifique se o usuário tem permissões suficientes
2. Verifique os logs de erro do WordPress
3. Verifique se o arquivo do addon está sendo carregado corretamente

## Estrutura de Arquivos

```
addons/
└── page-visibility/
    ├── assets/
    │   ├── css/
    │   │   └── admin.css
    │   └── js/
    │       └── admin.js
    ├── page-visibility.php  # Arquivo principal
    └── README.md
```

## Hooks e Filtros

O addon utiliza os seguintes hooks do WordPress:

- `wp_get_nav_menu_items` - Filtra os itens do menu para remover páginas ocultas
- `wp_get_nav_menu_objects` - Filtro adicional para garantir compatibilidade
- `add_meta_boxes` - Adiciona o meta box às páginas
- `save_post` - Salva as configurações de visibilidade

## Compatibilidade

- WordPress 5.0 ou superior
- Tema UENF Geral
- Compatível com Polylang

## Changelog

### 1.0.0 - 30/05/2025
- Versão inicial
- Adicionada funcionalidade básica de ocultar páginas
- Integração com o tema UENF Geral
