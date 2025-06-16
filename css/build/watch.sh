#!/bin/bash

# Cores para o terminal
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Diretório base do tema
THEME_DIR=$(dirname "$(dirname "$0")")
BUILD_SCRIPT="$THEME_DIR/build/build.php"

# Verifica se o script de build existe
if [ ! -f "$BUILD_SCRIPT" ]; then
    echo -e "${YELLOW}❌ Erro: Script de build não encontrado em $BUILD_SCRIPT${NC}"
    exit 1
fi

echo -e "\n${GREEN}=== 🚀 Watch CSS Iniciado ===${NC}"
echo -e "${YELLOW}📁 Monitorando arquivos CSS em $THEME_DIR${NC}"
echo -e "🔄 Pressione Ctrl+C para parar\n"

# Função para executar o build
run_build() {
    echo -e "\n${YELLOW}🔄 Detectadas alterações em $1${NC}"
    echo -e "⚙️  Executando build..."
    
    # Executa o build e captura a saída
    OUTPUT=$(php "$BUILD_SCRIPT" 2>&1)
    RESULT=$?
    
    # Exibe a saída com cores
    if [ $RESULT -eq 0 ]; then
        echo -e "${GREEN}✅ Build concluído com sucesso!${NC}"
    else
        echo -e "${YELLOW}⚠️  Erro durante o build:${NC}"
        echo "$OUTPUT"
    fi
    
    echo -e "${YELLOW}👀 Aguardando alterações...${NC}"
}

# Monitora alterações nos arquivos CSS
echo -e "${YELLOW}🔍 Iniciando monitoramento...${NC}"

# Usa inotifywait para monitorar alterações nos arquivos CSS
while true; do
    # Encontra todos os arquivos .css nos diretórios componentes e layout
    CSS_FILES=$(find "$THEME_DIR/css" -type f -name "*.css" ! -name "*.min.css" | tr '\n' ' ')
    
    # Se não encontrar arquivos, aguarda um pouco e tenta novamente
    if [ -z "$CSS_FILES" ]; then
        echo -e "${YELLOW}⚠️  Nenhum arquivo CSS encontrado. Verificando novamente em 5 segundos...${NC}"
        sleep 5
        continue
    fi
    
    # Monitora alterações nos arquivos
    inotifywait -q -e modify,move,create,delete $CSS_FILES
    run_build "$CSS_FILES"
done
