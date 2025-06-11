@echo off
echo ===================================
echo  Build CSS - Tema UENF Geral
echo ===================================
echo.

echo [1/3] Verificando dependências...

REM Verifica se o PHP está instalado
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERRO: PHP não encontrado no PATH.
    echo Certifique-se de que o PHP está instalado e configurado corretamente.
    pause
    exit /b 1
)

echo [2/3] Executando build do CSS...

REM Navega até o diretório do build
cd /d %~dp0css\build

REM Executa o script de build PHP
php build.php

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ERRO: Falha ao executar o build do CSS.
    pause
    exit /b 1
)

echo [3/3] Build concluído com sucesso!
echo.
echo O arquivo CSS foi gerado em: %~dp0css\style.css
echo.
pause
