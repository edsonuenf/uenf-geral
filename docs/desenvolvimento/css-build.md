# Processo de Build e Minificação de CSS

Este documento descreve o fluxo de trabalho para desenvolvimento e build de CSS no tema UENF Geral.

## 📁 Estrutura de Arquivos

```
css/
├── components/       # Componentes individuais
├── layout/           # Layout principal
├── utilities/        # Classes utilitárias
└── build/            # Scripts de build
    ├── build.php     # Script principal
    └── config.json  # Configurações do build
```

## 🔄 Fluxo de Trabalho

1. **Desenvolvimento**: Edite os arquivos CSS em `css/components/`
2. **Build**: Execute o script de build para minificar e combinar os arquivos
3. **Teste**: Verifique as alterações no navegador
4. **Commit**: Faça commit das alterações nos arquivos fonte e no CSS minificado

## 🛠️ Comandos Disponíveis

### Build do CSS

```bash
# Na raiz do tema
php css/build/build.php
```

### Watch para Desenvolvimento (opcional)

```bash
# Instale as dependências (uma vez)
npm install

# Inicie o watch
npm run watch
```

## ⚙️ Configuração

O arquivo `css/build/config.json` contém as configurações do build:

```json
{
  "input_dir": "../components",
  "output_file": "../style.min.css",
  "files": [
    "variables.css",
    "reset.css",
    "typography.css",
    "layout/main.css"
  ],
  "options": {
    "minify": true,
    "sourceMap": false,
    "autoprefixer": {
      "browsers": ["last 2 versions", "> 1%", "not dead"]
    }
  }
}
```

## 🔍 Debugging

- **Arquivo de log**: `css/build/build-error.log`
- **Modo de desenvolvimento**: Defina `"minify": false` no `config.json`
- **Source maps**: Ative com `"sourceMap": true`

## 📝 Regras de Minificação CSS

1. **Comentários**:
   - Remover todos os comentários exceto aqueles importantes (que começam com `/*!`)
   - Manter comentários importantes para compatibilidade e documentação

2. **Espaços**:
   - Remover quebras de linha (\r\n, \r, \n) e tabulações (\t)
   - Compactar múltiplos espaços em branco para um único espaço
   - Remover espaços desnecessários antes e depois de caracteres especiais ({}|:;,)

3. **Sintaxe**:
   - Remover o último ponto e vírgula de blocos de propriedades
   - Manter apenas o necessário para a funcionalidade do CSS

4. **Prefixos Vendor**:
   - Adicionar prefixos necessários para propriedades modernas:
     - `appearance`: `-webkit-`, `-moz-`
     - `user-select`: `-webkit-`, `-moz-`, `-ms-`
     - `transition`: `-webkit-`, `-o-`
     - `transform`: `-webkit-`, `-ms-`
     - `flex`: `-webkit-`, `-ms-`
     - `flex-direction`: `-webkit-`
     - `flex-wrap`: `-webkit-`
     - `align-items`: `-webkit-`
     - `justify-content`: `-webkit-`

5. **Arquivos de Entrada**:
   - Processar arquivos na ordem especificada no `config.json`
   - Manter um mapa de origem para cada arquivo processado
   - Gerar relatórios de tamanho e timestamp de cada arquivo

6. **Arquivo de Saída**:
   - Salvar em `style.min.css`
   - Opcionalmente gerar source map se habilitado
   - Adicionar cabeçalho com timestamp e URL do projeto

## 💡 Dicas

1. Nunca edite o arquivo `style.min.css` diretamente
2. Sempre execute o build após alterações nos arquivos fonte
3. Use `/*! */` para comentários importantes que devem ser mantidos
4. Mantenha os imports no topo dos arquivos

## 🔄 Versionamento

- O arquivo `style.min.css` é versionado no repositório
- A versão é baseada no timestamp do arquivo
- Use `filemtime()` para forçar atualização do cache

## 📚 Recursos

- [Guia de Estilo CSS](https://codex.wordpress.org/CSS)
- [Autoprefixer](https://github.com/postcss/autoprefixer)
- [CSS Minification](https://cssminifier.com/)
