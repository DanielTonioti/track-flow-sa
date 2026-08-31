# TrackFlow-SA
---
## Nomes dos membros:
#### Samuel Elias Candinho, Roberto Stockhausen Hille, Lucas Vieira Pedroso , Daniel Tonioti e Natan Vitor Dré Avanço Barbosa
---
## Tecnologias Utilizadas:
- Front-end: HTML / CSS / JavaScript / PHP / SQL;
- Framework: Bootstrap;
- IDE: Visual Studio Code.
---
## Metodologias utilizadas:
- Kanban: Com o objetivo de separar as diversas funções que cada um aplicara no projeto, o kanban foi utilizado para manter um senso de organização encima do que foi feito e o que está para ser feito;
- Sob sugestão do docente, o SCRUM será utilizado para manter a comunicação entre membros em relação ao progresso de cada dia. Estamos supostos a realizar uma reunião diária (Provavelmente próxima ao fim da aula) para conversar sobre o que cada um fez e quais são os próximos passos. Ainda estamos a definir o "SCRUM Master";
---

## Formatação
- Supostamente vamos usar snake_case para o projeto. (camelCase é objetivamente feio);

---
## Conceito:

#### O projeto consiste em fazer um aplicativo de um sistema interno de uma empresa ferroviária, com funções de geração de relatórios, gerenciamento de usuários, sensores, linhas e trens, além de um sistema de autenticação para permitir acesso apenas a pessoal autorizado. Usuários são separados entre Funcionários e Administradores, com o primeiro tendo apenas permissões de gerenciamento de relatórios, enquanto o último tem acesso total as funcioanlidades do site. 
#### O objetivo do projeto é criar um aplicativo que consiga, de maneira automática, registrar, monitorar e relatar a atual situação dos trens e trilhos com seus respectivos sensores, no final, tendo um sistema automatizado.
---
## Requisitos Funcionais Previstos:

### Login/Logout:
- Login(Conseguir colocar email e senha para entrar no aplicativo)
- Logout(sair da conta)
---
###  Gerenciamento de Sensores (Exclusivo de administradores):

- Cadastrar novo sensor, vinculado a um trem ou linha;
- Remover sensores;

---
### Gerenciamento de Funcionários (Exclusivo de administradores):

- Listagem de todos os usuários, com duas tabelas para administradores e funcionários;
- Função de Cadastrar usuários
- Função de Remover usuários
- Função de Permitir mudança de acesso de funcionários
---
### Relatórios:

- Função de para gerar um novo relatório usando um trem ou linha com sensores cadastrados;
- Listagem de relatórios passados;
- Opção de visualizar detalhes de um relatório passado, mostrado gráficos de alterações ao longo do tempo especificado;
- Função de excluir um relatório passado.

---
### Gerenciamento de Trens (Exclusivo de administradores):

- Cadastrar trens(Conseguir adicionar novo trem)
- Remover trens(Conseguir remover trem)
---
### Gerenciamento de Tags Padrão (Exclusivo de administradores):

- Tela de confirmação(Segunda etapa para remoção de dados)
