# TRACKFLOW
## Manual do administrador
### Versão 1.1


## Introdução

O “Track Flow” é um sistema web interno para empresas ferroviárias para automatização de monitoramento de trens, trilhos e sensores em tempo real. A plataforma conta com um sistema de autenticação, geração de relatórios com gráficos de temperatura, velocidade e falhas, gerenciamento completo de sensores, trens e funcionários, e uma separação entre contas de funcionários e administradores com diferentes níveis de acesso.
O sistema tem como público-alvo empresas ferroviárias interessadas em automatizar seus processos e garantir acesso rápido e intuitivo a informações internas da empresa.


## Instruções de uso

### Acesso (Log-in) no sistema

Passo 1: Iniciando na página inicial, entre ná pagina de login por meio de um dos botões de "entrar" destacados com a cor verde.

Passo 2: Clique no campo "e-mail" e insira um e-mail de administrador. Por propósitos de demonstração, sugerimos utilizar o e-mail pré-cadastrado "carlos@empresa.com".

Passo 3: Clique e preencha o campo "senha" com a senha "123456". Essa é a senha padrão de todos os usuário pré-cadastrados.

Passo 5: Clicar no botão “Entrar no sistema”.


### Níveis de acesso.

O sistema separa os usuários em duas categorias, chamadas de "nível de acesso". Sempre que um novo usuário é cadastrado, ele deve ser colocado em uma dessas categorias.
Os administradores/admins possuem acesso total ao sistema, com acesso a todas as páginas, funcionalidades e dados cadastrados. Nessa categoria devem apenas ser cadastrados pessoas de confiaça, responsáveis pelo gerenciamento de trens, rotas e outros usuários.
Os funcionários incluem funcionários "chão de fábrica", que não estão supostos a ter acesso total ao sistema. Eles tem acesso a leitura de dados, com limitações na leitura de funcionário, além da permissão de criar relatórios.


### Página hub / Sensores

Após a entrada do administrador no sistema, você verá diversas informações:

<b> Dados de sessão: </b> No canto superior esquerdo, hávera informações relacionados a sessão: O nome do usuário, seu nível de acesso, e a data atual.
<b> Dados de clima: </b> No canto superior direito, informa dados climáticos relacionados ao CEP cadastrado na conta atual.
<b> Indicadores operacionais: </b> No meio da página, é um carrossel de gráficos que demonstra as informações relevantes coletadas pelos sensores. Clique nas setas a direita do título para alternar entre os gráficos.
<b> Sensores: </b> No fim da página, são duas listas de sensores informando dados relevantes. Na esquerda, há os sensores alocados em trens. Na direita, há sensores alocados em trilhos.
Nesse secção, há botões para gerenciamento e cadastro de sensores.


### Barra de navegação

No topo de todas as páginas, é possível ver a "navbar", que permite uma navegação rápida entre as páginas do sistema.

<b> Barra lateral: </b> O botão no canto esquerdo abre a barra lateral, chamada de "menu". Ela possuí links para as principais áreas do sistema:
 - Dashboard/Início;
 - Funcionários e acessos;
 - Trens e trilhos;
 - Sensores;
 - Relatórios.

Para fechar o menu, é só clicar fora dele, ou clicar no "X".

<b> Home: </b> Representada pela logo, acompanhada de "Trackflow" e "ferrorama". Clicar te leva ao Dashboard/hub, independente de onde você estiver.

<b> Usuário: </b> Mostra informações de sessão, junto da foto de perfil, se houver. Clicar na foto de perfil abre um painel com algumas funções:
 - Ver conta: Permite ver informações da conta cadastrada, incluindo dados pessoais cadastrados e trens vinculados;
 - Trocar tema: Alterna entre tema claro e escuro;
 - Sair: Encerra a sessão e leva para á pagina inicial.


 ### Gerenciamento de usuários

A página de funcionários lista todos os funcionários e administradores na empresa. Admins conseguem cadastrar, editar e excluir funcionários.
Apenas 10 usuários são mostrados na tabela ao mesmo tempo. Se haver mais de 10 usuários, haverá múltiplas páginas para serem acessadas por meio de botões numéricos no canto inferior direito.
No canto superior direito da tabela, há botões para mostrar apenas os funcionários ou apenas os administradores.
No canto superior esquerdo, há uma barra de pesquisa, permitindo buscar usuários pelo nome, e-mail ou cidade. 

#### Cadastrar funcionário

 - Clique em "Adicionar funcionário" ná página de usuários.
 - Preencha as informações solicitadas. A foto de perfil é o único campo opcional.
 - Informa se o usuário é um administrador ou funcionário. Isso define o nível de acesso dele no sistema.
 - Clique em "Cadastrar funcionário".

 #### Editar/Excluir funcionário

 - Na lista de funcionários, clique em "editar" ao lado do funcionário
 - Altere qualquer informação desejada.
 - Clique em "Salvar Alterações".
 - Se você desejar excluir a conta, essa ação é efetuada por meio da página de edição também. Apenas clique no botão de "Excluir conta" destacado em vermelho e clique em "Confirmar exclusão" no pop-up que aparecer.

### Gerenciamento de trems e rotas

A página de funcionários lista todos os trems e rotas cadastrados. A tabela na esquerda corresponde aos trens, e a da direita corresponde as rotas. 
Admins conseguem cadastrar, editar e excluir trens e rotas.
Novamente, apenas 10 trens/rotas são mostrados na tabela de cada vez. Mais que isso e botões para acessar o resto apareceram.
Na direita de cada entrada das tabela há um botão de "consultar", que revela dados extras sobre o objeto consultado.
No canto superior esquerdo, há uma barra de pesquisa, permitindo buscar trens e rotas pelo nome, modelo e itinerário. 

#### Cadastrar trem

 - Clique em "Novo Trem" dentro da tabela de trens à esquerda, na página de trens e rotas. Alternativamente, você pode clicar no botão "Gerenciar trens" no canto superior direito.
 - Preencha as informações solicitadas.
 - Informa o usuário responsável pelo trem. Você precisa ter pelo menos um usuário cadastrado.
 - Clique em "Cadastrar trem".

 #### Editar trem

 - Na mesma página do cadastro de trem, é possível ver uma lista de trens cadastrados. Novamente, essa página pode ser acessada pelo botão "Novo trem" ou "Gerenciar trens" na página de trens e rotas.
 - Clique em "editar" no trem que você deseja editar. 
 - Altere qualquer informação desejada.
 - Clique em "Salvar Alterações".

#### Excluir trem

 - Na mesma página do cadastro de trem, é possível ver uma lista de trens cadastrados. Novamente, essa página pode ser acessada pelo botão "Novo trem" ou "Gerenciar trens" na página de trens e rotas.
 - Clique em "excluir" no trem que você deseja excluir. 
 - Clique em "Confirmar Exclusão"

 #### Cadastrar trem

 - Clique em "Nova Rota" dentro da tabela de rotas à direita, na página de trens e rotas. Alternativamente, você pode clicar no botão "Gerenciar rotas" no canto superior direito.
 - Preencha as informações solicitadas.
 - Informa o trem que passa por essa rota. Você precisa ter pelo menos um trem cadastrado.
 - Clique em "Cadastrar rota".

 #### Editar trem

 - Na mesma página do cadastro de rotas, é possível ver uma lista de rotas cadastrados. Novamente, essa página pode ser acessada pelo botão "Nova rotas" ou "Gerenciar rotas" na página de trens e rotas.
 - Clique em "editar" na rota que você deseja editar. 
 - Altere qualquer informação desejada.
 - Clique em "Salvar Alterações".

#### Excluir trem

 - Na mesma página do cadastro de rotas, é possível ver uma lista de rotas cadastrados. Novamente, essa página pode ser acessada pelo botão "Nova rota" ou "Gerenciar rotas" na página de trens e rotas.
 - Clique em "excluir" na rota que você deseja excluir. 
 - Clique em "Confirmar Exclusão"

### Gerenciamento de sensores.

O sistema final não possui a funcionalidade completa de gerenciamento de sensores. Os dados e sensores apresentados no Dashboard são decorativos, apresentando como seria se essa funcionalidade existisse.

É possível acessar á página principal, junto das páginas de criação e gerenciamente de sensores, mas não é possível realizar nada nelas. O sistema informa que são páginas em produção.

### Gerenciamento de relatórios.

Assim como o gerenciamento de sensores, o gerenciamento de relatórios não está completo. Isso é devido a sua dependencia ao funcionamento de sensores.

É possível acessar á página principal, junto das páginas de criação e gerenciamente de relatórios, mas não é possível realizar nada nelas. O sistema informa que são páginas em produção.




