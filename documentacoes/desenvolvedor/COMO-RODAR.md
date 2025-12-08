# Como Rodar o Projeto com Docker

Este guia explica como rodar o tema `uenf-geral` localmente usando Docker.

## Pré-requisitos

- Docker e Docker Compose instalados.
- Node.js e npm instalados.

## Passo a Passo

1.  **Inicie o Ambiente**:
    Abra o terminal na pasta do projeto e execute:
    ```bash
    ./start-dev.sh
    ```
    Este script irá:
    - Instalar dependências do Node (se necessário).
    - Compilar os assets (CSS/JS).
    - Iniciar os containers do WordPress e MySQL.

2.  **Acesse o WordPress**:
    - Abra o navegador em: [http://localhost:8000](http://localhost:8000)
    - Siga a instalação padrão do WordPress.
    - Faça login no painel administrativo.

3.  **Ative o Tema**:
    - Vá em **Aparência > Temas**.
    - Ative o tema **"UENF Geral"**.

## Comandos Úteis

- **Recompilar Assets Automaticamente**:
  Para editar CSS/JS e ver as mudanças em tempo real:
  ```bash
  npm run watch
  ```

- **Parar o Ambiente**:
  ```bash
  docker compose down
  ```

- **Verificar Status**:
  ```bash
  docker compose ps
  ```
