# Script PowerShell para verificar se as configuracoes do tema UENF foram resetadas

Write-Host "=== VERIFICACAO DE RESET DAS CONFIGURACOES DO TEMA UENF ===" -ForegroundColor Cyan
Write-Host ""

# 1. Verificar se os arquivos de reset existem
Write-Host "1. VERIFICANDO ARQUIVOS DO SISTEMA DE RESET:" -ForegroundColor Yellow
Write-Host "-------------------------------------------"

$arquivos_reset = @(
    "inc\class-theme-reset-manager.php",
    "inc\customizer\class-reset-controls.php",
    "js\admin\reset-manager.js",
    "js\customizer-social-media-reset.js"
)

$arquivos_encontrados = 0
foreach ($arquivo in $arquivos_reset) {
    if (Test-Path $arquivo) {
        Write-Host "OK $arquivo - Encontrado" -ForegroundColor Green
        $arquivos_encontrados++
    } else {
        Write-Host "ERRO $arquivo - Nao encontrado" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "Arquivos de reset encontrados: $arquivos_encontrados/$($arquivos_reset.Count)" -ForegroundColor White
Write-Host ""

# 2. Verificar arquivos de configuracao padrao
Write-Host "2. VERIFICANDO CONFIGURACOES PADRAO:" -ForegroundColor Yellow
Write-Host "------------------------------------"

$arquivos_config = @(
    "inc\customizer.php",
    "inc\customizer\class-pattern-library-manager.php",
    "functions.php"
)

foreach ($arquivo in $arquivos_config) {
    if (Test-Path $arquivo) {
        $conteudo = Get-Content $arquivo -Raw -ErrorAction SilentlyContinue
        
        # Contar definicoes de valores padrao
        $defaults_count = [regex]::Matches($conteudo, "default").Count
        $theme_mods_count = [regex]::Matches($conteudo, "theme_mod").Count
        
        Write-Host "Arquivo: $arquivo" -ForegroundColor White
        Write-Host "   - Definicoes de padrao: $defaults_count" -ForegroundColor Gray
        Write-Host "   - Referencias a theme_mod: $theme_mods_count" -ForegroundColor Gray
    }
}

Write-Host ""

# 3. Verificar se existem backups
Write-Host "3. VERIFICANDO BACKUPS:" -ForegroundColor Yellow
Write-Host "----------------------"

$backup_dirs = @("backups", "backup", "config-backup")
$backup_encontrado = $false

foreach ($dir in $backup_dirs) {
    if (Test-Path $dir) {
        $arquivos_backup = Get-ChildItem $dir -File | Measure-Object
        Write-Host "Diretorio de backup encontrado: $dir ($($arquivos_backup.Count) arquivos)" -ForegroundColor Green
        $backup_encontrado = $true
    }
}

if (-not $backup_encontrado) {
    Write-Host "Nenhum diretorio de backup encontrado" -ForegroundColor Yellow
}

Write-Host ""

# 4. Resumo e recomendacoes
Write-Host "4. RESUMO E RECOMENDACOES:" -ForegroundColor Yellow
Write-Host "---------------------------"

if ($arquivos_encontrados -eq $arquivos_reset.Count) {
    Write-Host "OK Sistema de reset esta instalado corretamente" -ForegroundColor Green
} else {
    Write-Host "ATENCAO Alguns arquivos do sistema de reset estao faltando" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "PARA VERIFICAR SE O RESET FOI EXECUTADO:" -ForegroundColor Cyan
Write-Host "1. Acesse o WordPress Admin" -ForegroundColor White
Write-Host "2. Va em Aparencia > Personalizar" -ForegroundColor White
Write-Host "3. Verifique se as configuracoes estao nos valores padrao" -ForegroundColor White
Write-Host "4. Especialmente: Design > Padroes, Tipografia, Cores" -ForegroundColor White
Write-Host ""
Write-Host "OU execute no console do WordPress:" -ForegroundColor Cyan
Write-Host "var_dump(get_theme_mods());" -ForegroundColor White
Write-Host ""
Write-Host "Se retornar array vazio ou apenas valores padrao, o reset foi bem-sucedido!" -ForegroundColor Green

Write-Host ""
Write-Host "=== VERIFICACAO CONCLUIDA ===" -ForegroundColor Cyan
