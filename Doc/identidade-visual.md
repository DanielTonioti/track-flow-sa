# Identidade Visual - TrackFlow (Ferrorama)

## 1. Sobre o Projeto

O **TrackFlow** é o sistema web que estamos desenvolvendo para a proposta da **Ferrorama**, pensado para o acompanhamento interno de uma empresa ferroviária. Hoje o sistema conta com a página inicial (`index.php`), tela de login (`Login.php`), painel principal com clima em tempo real, gráficos e tabela de sensores (`hub.php`), parte de funcionários e perfis (`Admin.php`, `Usuario_Info.php`, `cadastro_usuario.php`, `editar_usuario.php`) e o cadastro e controle de trens, rotas e trilhos (`trens_trilhos.php` e páginas ligadas). As páginas de Sensores e Relatórios já receberam o mesmo padrão visual do resto do site, mas ficam marcadas como **"Em produção"** para serem finalizadas na próxima etapa.

O objetivo dessa parte do trabalho foi padronizar o visual de todas as telas, porque antes algumas páginas tinham estilos diferentes ou inacabados. Buscamos deixar tudo com cara de sistema real de monitoramento, usando fundo escuro para não cansar a vista, cores sólidas sem exagero de degradê e informações fáceis de achar.

---

## 2. Referências que Usamos

Para montar o visual, olhamos alguns sites e ideias que já vinham ajudando o grupo desde o começo do projeto e adaptamos para o tema de ferrovia:

- **Canva, Calm e Zola:** usamos como base principalmente na página inicial (`index.php`) para separar os assuntos em caixas bem organizadas, com bordas discretas e cores sólidas.
- **Instagram e Espaço do Estudante:** serviram de ideia inicial (como já tínhamos anotado em `Doc/Identidade.md`) para deixar o login simples e direto ao ponto, sem encher a tela de informação, e para organizar os atalhos do sistema sem espalhar vários botões.
- **Painéis escuros de monitoramento:** ajudaram na ideia de mostrar os gráficos abertos ao mesmo tempo (sem esconder em carrossel), deixar as tabelas mais diretas e guardar cores chamativas só para avisos importantes e status dos sensores.

---

## 3. Escolha das Cores e Paleta

Seguimos a ideia de psicologia das cores e contraste que o grupo já tinha começado em `Doc/Identidade.md`, ajustando os tons para funcionarem bem em todas as páginas.

Escolhemos **tons escuros de azul** para o fundo e para os cartões porque o azul passa sensação de confiança e segurança, além de cansar bem menos a visão do que uma tela toda branca para quem fica acompanhando dados por bastante tempo. Por cima do fundo escuro, colocamos os textos em branco e cinza claro para dar bastante contraste e facilitar a leitura.

Para destacar detalhes da marca sem poluir a tela, mantivemos o **verde-lima (`#c1ff72`)** em pontos específicos, como na palavra *"ritmo"* da tela de login e no detalhe embaixo da logo. Já para os avisos do sistema (funcionando normal, atenção, erro e informação), separamos cores fixas sempre junto de um ícone e de uma palavra escrita, para ninguém depender só da cor para entender o que está acontecendo.

### Cores principais utilizadas

- `#060d17` e `#081220`: fundo principal das páginas e fundo dos campos de digitação.
- `#0b1523` e `#0d1b2e`: fundo dos cartões, tabelas, menu lateral e janelas de confirmação.
- `#101e31`: tom um pouco mais claro usado em detalhes e quando passa o mouse por cima (`hover`).
- `#f4f7fa`: cor principal dos textos, títulos e dados das tabelas.
- `#c2d0e0` e `#8fa3b8`: textos secundários, legendas e títulos das colunas.
- `#3b59e8` e `#5271ff`: azul principal usado nos botões de ação, gráficos e itens selecionados.
- `#38bdf8`: azul claro usado em códigos (como `S.TRM.001`), avisos simples e na borda que aparece ao navegar com a tecla `Tab`.
- `#c1ff72`: verde-lima de destaque da marca na página inicial, no login e na barra do topo.
- `#22c55e` e `#4ade80`: cor verde para indicar sucesso e sensor funcionando normal.
- `#f59e0b` e `#fbbf24`: cor amarela/laranja para avisos de atenção ou alerta.
- `#dc2626` e `#fca5a5`: cor vermelha usada apenas para erro, sensor parado e botões de excluir.

### O que testamos e descartamos

Durante os testes, chegamos a experimentar fundos com degradê e um azul ciano bem forte, mas achamos que ficou artificial e cansativo de olhar. Também descartamos deixar as páginas internas claras porque destoava totalmente da página inicial escura. Por isso ficamos com os tons de azul escuro sólido e poucos detalhes coloridos.

---

## 4. Fontes e Ícones

Padronizamos três fontes do Google Fonts em todas as páginas pelo arquivo `public/components/head.php`:

- **League Spartan:** usada no nome TrackFlow, na frase principal do login e nos títulos das páginas, por ser uma fonte mais forte e fácil de identificar.
- **Inter:** usada nos textos normais, nomes de campos, botões e avisos, por ser simples de ler em qualquer tamanho.
- **JetBrains Mono:** fonte com letras e números de mesmo espaço, usada nos códigos de sensores, quilometragem, temperaturas, velocidades e horários para os números ficarem alinhados nas tabelas.

Nos ícones, trocamos imagens soltas e emojis pela biblioteca **Bootstrap Icons**, mantendo o mesmo estilo em todo o site (inclusive nos ícones de clima). Também ajustamos a logo da locomotiva (`logo-track-flow.png`) para ficar branca dentro de uma caixinha no topo e colocamos uma linha tracejada simples embaixo do título das páginas para lembrar o formato dos trilhos.

---

## 5. Mudanças de Layout e Decisões do Grupo

### O que estava ruim antes

- A logo `logo-track-flow.png` era preta e quase sumia em cima do fundo azul escuro.
- As páginas internas tinham um botão vermelho escrito "Voltar" preso no canto de baixo da tela, usando cor de erro numa ação comum e ficando por cima das tabelas em telas menores.
- Apareciam siglas e textos desnecessários (como *"CCO"*) e caixas de resumo repetindo números que não precisavam aparecer logo de cara.
- Na página `Admin.php`, a lista de usuários começava escondida e tinha uma tabela extra só para dizer a quantidade de pessoas.
- Nos cadastros, os campos ficavam tortos dentro dos `<label>`, um erro no CEP abria `alert()` travando a tela e faltava esconder telefone e CEP quando o usuário logado era apenas funcionário.
- As telas de sensores e relatórios mostravam apenas quadrados escuros vazios ou um texto improvisado de *"WIP"*.

### Decisões que tomamos para resolver

1. **Navegação só pelo botão hambúrguer (`public/components/navbar.php`):** tiramos links espalhados do meio da barra superior e deixamos toda a navegação entre páginas dentro do menu lateral do botão hambúrguer. Assim o topo ficou limpo só com o botão do menu, a logo TrackFlow na esquerda e o usuário logado na direita, além de removermos a sigla *"CCO"* do site.
2. **Tela de login mais limpa (`public/Login.php`):** tiramos textos grandes e cartões extras do lado esquerdo do login, deixando só a logo com o botão `Página Inicial` em cima e a frase *"No ritmo que move estados."* no meio. No formulário, colocamos o botão para mostrar/esconder a senha e trocamos o aviso de esqueci minha senha por uma mensagem direta pedindo para entrar em contato com a empresa.
3. **Retirada de informações repetidas no Hub e em Funcionários (`public/hub.php` e `public/Admin.php`):** tiramos os cartões de resumo do topo do painel principal para ir direto ao clima, aos três gráficos e à tabela de sensores. Em `Admin.php`, deixamos a lista visível direto ao abrir e tiramos a tabela de contagem separada, já que os próprios botões de filtro (`Todos`, `Administradores` e `Funcionários`) mostram a quantidade entre parênteses.
4. **Fim do botão vermelho "Voltar" preso no canto:** trocamos o botão vermelho flutuante por botões normais no topo de cada página junto com o caminho de onde o usuário está, deixando a cor vermelha só para erros e exclusões com confirmação.
5. **Formulários alinhados e bloqueio de dados para funcionário (`Admin.php`, `Usuario_Info.php`, `cadastro_usuario.php`, `editar_usuario.php`):** colocamos os nomes dos campos em cima dos inputs, trocamos o `alert()` do ViaCEP por um aviso escrito embaixo do campo e ajustamos a permissão: quem entra como `funcionario` não pode ver o `CEP` de ninguém e a parte de `Telefone` nem aparece para ele.
6. **Páginas de Sensores e Relatórios com aviso de "Em produção":** deixamos `Sensor_Data.php`, `Sensor.php`, `Sensor_Manage.php`, `Relatories.php` e as páginas ligadas já com o mesmo cabeçalho, tabelas e formulários do resto do sistema, mostrando um aviso claro de **"Página em produção"** / **"Em produção"** para o usuário.

---

## 6. Problemas que Tivemos e Como Resolvemos

- **Logo preta sumindo e quadrados antigos sem cor (`Styles/style.css`):** como o arquivo da logo era preto com fundo transparente, não dava para enxergar direito no topo escuro, e uma regra antiga `.border-tabela` tinha `!important` travando a cor de fundo dos blocos antigos. Resolvemos aplicando `filter: brightness(0) invert(1)` no CSS para deixar a logo branca sem estragar a imagem original e criando classes novas padronizadas para as tabelas e etiquetas de status.
- **Palavra "ritmo" com fonte estranha no login (`Login.php` e `Styles/style.css`):** no título *"No ritmo que move estados."*, a palavra *"ritmo"* ficava dentro de um `<span>` para ganhar a cor verde-lima, mas uma regra antiga `* { font-family: var(--Fonte-padrao); }` no CSS fazia esse `<span>` pegar a fonte `Inter` em vez da `League Spartan` do resto da frase. Arrumamos fazendo `.brand-font *` usar a mesma fonte do título.
- **Contraste do botão azul:** o azul `#5271ff` com texto branco ficava um pouco claro demais para letras pequenas. Escurecemos um pouco o fundo do botão principal para `#3b59e8`, melhorando a leitura sem mudar a cara azul do projeto.

---

## 7. Testes Realizados

Fizemos os seguintes testes locais para conferir se tudo continuava funcionando:

- **Leitura e contraste:** conferimos pelo navegador se os textos claros, botões e avisos estavam fáceis de ler em cima do fundo azul escuro.
- **Navegação pelo teclado:** testamos apertar `Tab` nas páginas e formulários para ver se o contorno azul claro mostrava certinho onde o foco estava.
- **Testes no XAMPP (PHP, banco de dados e cargos):** testamos login, cadastro, edição e exclusão de usuários, trens, rotas e trilhos no MySQL. Também entramos com conta de `funcionario` para confirmar que o `CEP` e o `Telefone` realmente não aparecem, e abrimos as páginas de sensores e relatórios para garantir que não davam erro de PHP.
- **Tamanhos de tela diferentes:** usamos o modo responsivo do Google Chrome (DevTools) para testar as páginas em tela de computador (`1440px`), tablet (`768px`) e celular (`390px`), conferindo o menu hambúrguer, os gráficos empilhados e a barra de rolagem dentro das tabelas.

---

## 8. Fontes de Pesquisa

- W3C - Diretrizes de Acessibilidade para Conteúdo Web (WCAG): https://www.w3.org/TR/WCAG22/
- MDN Web Docs - Contraste de cores e acessibilidade: https://developer.mozilla.org/pt-BR/docs/Web/Accessibility/Understanding_WCAG/Perceivable/Color_contrast
- Documentação do Bootstrap 5.3: https://getbootstrap.com/docs/5.3/getting-started/introduction/
- Bootstrap Icons: https://icons.getbootstrap.com/
- Google Fonts (League Spartan, Inter e JetBrains Mono): https://fonts.google.com/
- Google Charts: https://developers.google.com/chart
- API Open-Meteo: https://open-meteo.com/en/docs
- ViaCEP: https://viacep.com.br/

---

## 9. Ferramentas Utilizadas

- **Visual Studio Code:** usado para editar os arquivos PHP, HTML, CSS, JavaScript e SQL.
- **XAMPP (Apache e MySQL):** usado para rodar o site localmente e testar o banco de dados.
- **Bootstrap 5, Bootstrap Icons, Google Charts e Three.js:** usados no layout responsivo, nos ícones, nos gráficos do painel e no fundo 3D da página inicial.
- **Google Chrome (DevTools):** usado para inspecionar o CSS, checar contraste e testar tamanhos de tela de celular e tablet.
- **Google Antigravity / Gemini:** usado como apoio para padronizar o CSS e ajustar o visual das páginas PHP, seguindo as correções e escolhas definidas pelo grupo.