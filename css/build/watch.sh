#!/bin/bash

echo "=== Watch CSS iniciado ==="
echo "Monitorando arquivos CSS para mudanças..."

echo "\n⚙️ Arquivos sendo monitorados:"
for file in \
    "../variables.css" \
    "../layout/main.css" \
    "../components/header.css" \
    "../components/menu.css" \
    "../components/search.css" \
    "../components/footer.css" \
    "../custom-fontawesome.css" \
    "../fonts_css.css" \
    "../404.css" \
    "../search.css" \
    "../styles.css"
do
    if [ -f "$file" ]; then
        echo "- $(basename $file)"
    else
        echo "❌ Arquivo não encontrado: $(basename $file)"
    fi
done

echo "\n⚙️ Iniciando monitoramento..."

# Monitorar mudanças usando inotifywait
inotifywait -m -e modify ../variables.css ../layout/main.css ../components/header.css ../components/menu.css ../components/search.css ../components/footer.css ../custom-fontawesome.css ../fonts_css.css ../404.css ../search.css ../styles.css | while read path action file; do
    echo "\n✅ Mudança detectada em: $file"
    echo "  Data/hora: $(date '+%Y-%m-%d %H:%M:%S')"
    
    # Executar o build
    echo "⚙️ Iniciando minificação..."
    php build.php
    echo "✅ Minificação e combinação de CSS concluída"
done
