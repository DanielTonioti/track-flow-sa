# PDO no PHP

## O que é PDO?

 PDO é a sigla de **PHP Data Objects** e consiste em uma extensão do PHP que fornece uma interface padronizada e orientada a objetos para acessar bancos de dados.

Ele funciona como uma camada de comunicação entre a aplicação e o SGBD, utilizando drivers como `pdo_mysql`, `pdo_pgsql` e `pdo_sqlite`.

No PHP, é utilizado para estabelecer conexões, executar comandos SQL, cadastrar, consultar, atualizar e excluir registros, controlar transações e tratar erros.

A conexão é criada por uma instância da classe `PDO`, informando o DSN, o usuário e a senha.

>**Diferentemente do DNS, o DSN não traduz nomes em endereços IP**, pois ele reúne informações da conexão, como o driver, o servidor, o banco e a codificação.

---

##  Principais características do PDO

As principais características do PDO são a compatibilidade com diferentes SGBDs, o suporte a **Prepared Statements**, o tratamento de erros por exceções, o controle de transações e os diferentes modos de recuperação dos resultados.

### PDO x MySQLi

PDO e MySQLi podem conectar o PHP ao MySQL, mas o PDO trabalha com vários bancos, enquanto o MySQLi é específico para o MySQL.

Além disso, o PDO possui somente uma interface orientada a objetos e aceita parâmetros nomeados e posicionais, enquanto o MySQLi também possui uma interface procedural.

Entre as vantagens do PDO estão sua organização, segurança, flexibilidade e facilidade de manutenção.

Suas desvantagens incluem a necessidade de drivers específicos e a possível ausência de recursos exclusivos de determinados bancos.

---

## Prepared Statements

Prepared Statements são instruções SQL que utilizam marcadores no lugar dos valores, separando os dados da estrutura do comando.

Isso ajuda a prevenir ataques de **SQL Injection**, embora não substitua a validação dos dados, a proteção das credenciais e o controle das permissões.

O PDO é uma boa escolha para aplicações orientadas a objetos que precisam de transações, consultas seguras ou compatibilidade com diferentes bancos.

---
## Fontes utilizadas

As fontes utilizadas foram:

### Manual do PHP — PDO e conexões

O Manual do PHP sobre PDO e conexões, disponível em:

[Manual do PHP sobre PDO](https://www.php.net/manual/pt_BR/book.pdo.php?utm_source=chatgpt.com)

### Comparação entre PDO e MySQLi

A comparação entre PDO e MySQLi, disponível em:

[Comparação entre PDO e MySQLi](https://www.php.net/manual/en/mysqlinfo.api.choosing.php?utm_source=chatgpt.com)

### OWASP — SQL Injection

Guia da OWASP sobre SQL Injection, disponível em:

[OWASP — SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html?utm_source=chatgpt.com)
