# Documentação de Personalização do Tema UENF Geral

Este documento fornece informações detalhadas sobre a personalização do tema UENF Geral, incluindo estrutura, configurações disponíveis e guias de solução de problemas.

## 📋 Visão Geral

O tema UENF Geral é um tema WordPress desenvolvido para atender às necessidades da Universidade Estadual do Norte Fluminense (UENF). Ele foi construído com foco em desempenho, acessibilidade e facilidade de personalização.

## 🎨 Personalização de Cores

### Cores Principais
- **Cor Primária:** `#1d3771`
- **Cor Primária Clara:** `#2a4a8a`
- **Cor do Texto:** `#333333`
- **Cor dos Links:** `#26557d`
- **Cor dos Links (Hover):** `#1d4b6e`

### Como Alterar as Cores

1. Acesse o **Personalizador do WordPress**
2. Navegue até **Aparência > Personalizar**
3. Selecione **Cores do Tema**
4. Ajuste as cores conforme necessário

## 🖋️ Tipografia

O tema utiliza uma hierarquia tipográfica responsiva:

- **Título Principal (h1):** 2.5rem
- **Subtítulo (h2):** 2rem
- **Subtítulo Menor (h3):** 1.75rem
- **Texto Base:** 1rem (16px)

## 🖥️ Layout Responsivo

O tema é totalmente responsivo e utiliza os seguintes breakpoints:

- **Mobile:** Até 575px
- **Tablet:** 576px - 991px
- **Desktop:** 992px - 1199px
- **Widescreen:** Acima de 1200px

## 🧩 Componentes Personalizáveis

### Cabeçalho
- Logotipo personalizável
- Menu de navegação principal
- Seletor de idiomas

### Rodapé
- Widget areas personalizáveis
- Informações de contato
- Links de navegação secundários

## 🔧 Solução de Problemas Comuns

### 1. Cores não estão atualizando
- Limpe o cache do navegador
- Verifique se há plugins de cache ativos
- Confira se há erros no console do navegador (F12 > Console)

### 2. Menu não está aparecendo corretamente
- Verifique se o menu está configurado em **Aparência > Menus**
- Confirme se o local do menu está atribuído corretamente
- Verifique se há conflitos com plugins

### 3. Problemas com imagens
- Verifique as permissões dos arquivos
- Confirme se as imagens foram enviadas corretamente
- Verifique os tamanhos de imagem nas configurações do WordPress

## 🛠️ Estrutura de Arquivos

```
theme-uenf-geral/
├── assets/          # Recursos estáticos (imagens, ícones, etc.)
├── css/            # Folhas de estilo
│   ├── components/  # Estilos de componentes individuais
│   └── layout/      # Estilos de layout
├── fonts/           # Fontes personalizadas
├── inc/             # Arquivos de inclusão PHP
│   ├── customizer.php
│   ├── optimization.php
│   ├── seo.php
│   └── template-functions.php
├── js/              # Arquivos JavaScript
├── template-parts/  # Partes de templates reutilizáveis
├── 404.php
├── archive.php
├── footer.php
├── functions.php
├── header.php
├── index.php
└── style.css
```

## 🔄 Atualizações

Antes de atualizar o tema, sempre faça backup do seu site. As atualizações podem ser feitas através do painel do WordPress ou via FTP.

## 📞 Suporte

Para suporte técnico, entre em contato com a equipe de desenvolvimento da UENF.

---

📅 **Última Atualização:** Junho de 2024
