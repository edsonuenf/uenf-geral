@echo off
echo ===================================
echo  Watch CSS - Tema UENF Geral
echo ===================================
echo.

echo [1/3] Verificando dependências...

REM Verifica se o bash está disponível (Git Bash, WSL, etc.)
where bash >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERRO: Bash não encontrado no PATH.
    echo Certifique-se de que o Git Bash ou WSL está instalado.
    pause
    exit /b 1
)

REM Verifica se o inotify-tools está instalado
bash -c "command -v inotifywait >/dev/null 2>&1"
if %ERRORLEVEL% NEQ 0 (
    echo ERRO: inotifywait não encontrado.
    echo No Ubuntu/Debian, instale com: sudo apt-get install inotify-tools
    echo No CentOS/RHEL, instale com: sudo yum install inotify-tools
    pause
    exit /b 1
)

echo [2/3] Iniciando monitoramento de arquivos CSS...
echo Pressione Ctrl+C para parar o monitoramento.
echo.

REM Navega até o diretório do tema e executa o script de watch
cd /d %~dp0
bash css/build/watch.sh

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ERRO: Falha ao iniciar o monitoramento.
    pause
    exit /b 1
)
