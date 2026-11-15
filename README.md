# TrackFlow-SA
---
## Nomes dos membros:
Samuel Elias Candinho, Roberto Stockhausen Hille, Lucas Vieira Pedroso, Daniel Tonioti e Natan Vitor Dré Avanço Barbosa

## Conceito:

O projeto consiste em desenvolver um aplicativo web para o sistema interno de uma empresa ferroviária, com funções de autenticação, gerenciamento de usuários, trens, rotas e linhas, além da estrutura prevista para monitoramento de sensores e geração de relatórios. Os usuários são separados entre Funcionários e Administradores, sendo que os administradores possuem acesso total às funcionalidades de gestão do sistema.
O objetivo do projeto é criar uma plataforma que permita registrar, monitorar e organizar a operação dos trens e trilhos com seus respectivos sensores.

## Requisitos Funcionais e Status:

### Login/Logout — *(Implementado)*
- Log-in;
- Log-out.

### Gerenciamento de Funcionários (Exclusivo de administradores) — *(Implementado)*
- Listagem de todos os usuários, separada por administradores e funcionários;
- Função de cadastrar usuários;
- Função de editar e remover usuários;
- Função de permitir mudança de acesso de funcionários.

### Gerenciamento de Trens e Rotas (Exclusivo de administradores) — *(Implementado)*
- Cadastrar, editar e remover trens;
- Cadastrar, editar e remover rotas vinculadas aos trens.

### Gerenciamento de Linhas / Trilhos (Exclusivo de administradores) — *(Implementado)*
- Cadastrar, editar e remover linhas/trilhos.

### Gerenciamento de Sensores (Exclusivo de administradores) — *(Em produção / Não feito)*
> **Status:** Módulo em desenvolvimento (CRUD ainda não implementado; a página exibe aviso de página em desenvolvimento).
- Cadastrar novo sensor, vinculado a um trem ou linha *(não feito)*;
- Listar, editar e remover sensores *(não feito)*.

### Relatórios — *(Em produção / Não feito)*
> **Status:** Módulo em desenvolvimento (CRUD ainda não implementado; a página exibe aviso de página em desenvolvimento).
- Função para gerar um novo relatório usando um trem ou linha com sensores cadastrados *(não feito)*;
- Listagem de relatórios passados *(não feito)*;
- Opção de visualizar detalhes de um relatório passado, mostrando gráficos de alterações ao longo do tempo especificado *(não feito)*;
- Função de excluir um relatório passado *(não feito)*.

---
## Tecnologias Utilizadas:
- Front-end: HTML / CSS / JavaScript / Bootstrap / Three.js;
- Back-end e Banco de Dados: PHP / MySQL (SQL);
- IDE: Visual Studio Code.

## Metodologias utilizadas:
Kanban: Com o objetivo de separar as diversas funções que cada um aplicará no projeto, o kanban foi utilizado para manter um senso de organização em cima do que foi feito e o que está para ser feito. Foram definidas mais de 60 funções para serem divididas entre os membros da equipe.
Sob sugestão do docente, o SCRUM foi utilizado para manter a comunicação entre membros em relação ao progresso de cada dia, com reuniões diárias para alinhar o que cada um fez e quais são os próximos passos. O projeto também possui seções bem definidas, facilitando a organização de sprints (como autenticação, gerenciamento de usuários e gestão de frota/linhas).

## Formatação
Nos arquivos do projeto foram adotadas convenções mistas ao longo do desenvolvimento, com uso de snake_case nos arquivos PHP mais recentes e camelCase nos scripts JavaScript. As operações de banco de dados (autenticação, gerenciamento de usuários, trens, rotas e linhas) são realizadas via PHP com MySQLi/SQL, enquanto o JavaScript é utilizado para interações de interface, gráficos, validação e animações visuais.
