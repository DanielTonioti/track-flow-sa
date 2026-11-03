# Identidade Visual — TrackFlow (Ferrorama)

## 1. Proposta do Sistema e Identidade

O **TrackFlow** é o sistema desenvolvido pela equipe para a proposta da **Ferrorama**, voltado ao monitoramento interno de uma empresa ferroviária. Ele reúne o acompanhamento de sensores de trens e trilhos, cadastro de composições e rotas, consulta de relatórios, clima em tempo real e gerenciamento de funcionários e administradores.

O objetivo visual desta etapa foi unificar todas as páginas do sistema com a mesma identidade, saindo de telas soltas ou inacabadas e criando a sensação de um painel real de controle ferroviário: fundo escuro confortável para uso prolongado, informações diretas, cores sólidas sem gradientes exagerados e leitura rápida dos dados.

---

## 2. Referências Visuais Pesquisadas

Durante o desenvolvimento, analisamos referências que já vinham guiando o projeto desde as primeiras versões e adaptamos o que fazia sentido para um painel ferroviário:

- **Canva, Calm e Zola:** serviram de base na reformulação da página inicial (`index.php`) para organizar informações em blocos bem separados, com bordas discretas, cores sólidas e sem poluição visual.
- **Telas minimalistas de login e portais de gestão (como Instagram e Espaço do Estudante):** ajudaram na ideia inicial de manter o login direto ao ponto e agrupar atalhos principais sem excesso de botões espalhados.
- **Painéis escuros de monitoramento ferroviário:** mostraram a importância de deixar os gráficos visíveis ao mesmo tempo (sem esconder dados importantes em carrossel), usar tabelas compactas e reservar cores fortes apenas para alertas e estados dos sensores.

---

## 3. Teoria das Cores e Paleta Escolhida

A escolha das cores seguiu a psicologia das cores e o contraste de luminosidade (evoluindo a base já registrada pela equipe em `Doc/Identidade.md`).

Optamos por trabalhar com **tons escuros de azul no fundo e nos painéis**, porque o azul transmite confiança e estabilidade, além de evitar o cansaço visual que uma tela totalmente branca causa em quem passa muito tempo monitorando dados. Sobre esse fundo escuro, usamos textos claros para garantir contraste alto e leitura imediata.

Para criar destaque sem poluir a tela, combinamos os tons de azul com o **verde-lima (`#c1ff72`)** em detalhes pontuais da marca (como na palavra "ritmo" e no detalhe abaixo da logo) e separamos cores fixas para os estados do sistema (sucesso, atenção, erro e informação), sempre acompanhadas de ícone e texto para que o usuário não dependa apenas da cor.

### Paleta de Cores (HEX e funções)

- `#060d17` e `#081220`: fundo principal das páginas e interior dos campos de formulário.
- `#0b1523` e `#0d1b2e`: fundo dos cartões, painéis, tabelas e janelas modais.
- `#101e31`: fundo de elementos secundários e efeito ao passar o mouse (`hover`).
- `#f4f7fa`: cor principal dos textos, títulos e dados das tabelas.
- `#c2d0e0` e `#8fa3b8`: textos secundários, legendas, cabeçalhos de tabela e dicas de preenchimento.
- `#3b59e8` e `#5271ff`: azul principal da identidade, usado nos botões de ação principal, linhas de gráficos e bordas de destaque.
- `#38bdf8`: azul claro/ciano usado em códigos identificadores (`S.TRM.001`, `#OP-004`), informações e contorno visível de foco do teclado.
- `#c1ff72`: verde-lima de destaque da marca na página inicial, tela de login e barra superior.
- `#22c55e` e `#4ade80`: estado de sucesso, confirmação e sensor operando normalmente (`NOMINAL`).
- `#f59e0b` e `#fbbf24`: estado de atenção e alerta de temperatura ou velocidade (`ATENÇÃO`).
- `#dc2626` e `#fca5a5`: estado de erro, sensor sem comunicação (`INOPERANTE`) e ações de exclusão.

### Estudo de paleta durante o processo

Testamos inicialmente variações com gradientes e um tom ciano fluorescente muito forte, mas descartamos porque deixavam o site artificial e cansativo. Também descartamos o fundo branco comum porque quebrava a ligação com a página inicial noturna. A combinação final de azul escuro sólido com texto claro e poucos pontos de cor ficou muito mais limpa e fiel ao tema ferroviário.

---

## 4. Tipografia e Elementos Gráficos

Definimos três fontes do Google Fonts com funções diferentes em todas as telas (carregadas pelo cabeçalho compartilhado `public/components/head.php`):

- **League Spartan:** usada no nome TrackFlow e nos títulos das páginas e seções, por ter um formato forte e bem marcado que combina com sinalização ferroviária.
- **Inter:** usada nos textos gerais, rótulos de formulários, botões e mensagens, facilitando a leitura em qualquer tamanho de tela.

Nos elementos gráficos, padronizamos os ícones com a biblioteca **Bootstrap Icons** (substituindo imagens soltas e emojis que mudavam conforme o computador), ajustamos a logo da locomotiva (`logo-track-flow.png`) para aparecer em branco dentro de um selo com detalhe verde-lima na barra superior e adicionamos uma linha tracejada discreta abaixo do título das páginas lembrando os dormentes dos trilhos.

---

## 5. Layout, Hierarquia Visual e Problemas Anteriores

### Problemas identificados nas telas antigas

Antes desta revisão, o sistema apresentava vários pontos que prejudicavam o uso:

- A logo `logo-track-flow.png` era preta e quase desaparecia sobre o fundo azul escuro do cabeçalho e do login.
- Todas as telas internas tinham um botão vermelho "Voltar" fixo no canto inferior esquerdo, usando cor de erro/perigo em uma navegação comum e cobrindo linhas das tabelas em telas menores.
- Na tela `Admin.php`, a lista de funcionários começava escondida, fazendo a página parecer vazia ao abrir.
- Nos formulários (como `cadastro_usuario.php`), os campos ficavam desalinhados dentro dos rótulos, erros apagavam o endereço já digitado e falhas no CEP abriam um `alert()` do navegador.

### Principais decisões de design e mudanças realizadas

1. **Barra superior limpa com navegação no menu hambúrguer (`public/components/navbar.php`):** mantivemos a navegação concentrada no botão hambúrguer com menu lateral (*offcanvas*) padronizado em todas as telas, deixando o topo limpo apenas com a marca, o botão do menu e o perfil do usuário.
2. **Fim do botão vermelho "Voltar" flutuante:** trocamos o botão vermelho fixo no rodapé por um cabeçalho organizado em cada página, com o caminho da tela (*breadcrumb*) e botões neutros de retorno no topo. O vermelho ficou restrito a erros, alertas críticos e exclusões com confirmação.
3. **Estrutura visual preparada para Sensores e Relatórios:** substituímos os quadrados escuros vazios de `Sensor_Data.php` e os avisos improvisados de *"Pagina em andamento(WIP)"* em `Sensor.php` e `Sensor_Manage.php` por tabelas, filtros e formulários já padronizados na nova identidade visual (assim como em `Relatories.php`), deixando a base pronta para a próxima etapa de integração do módulo.
4. **Padronização dos formulários, tabelas e permissões:** dividimos os cadastros em seções claras com rótulos acima dos campos, opção de mostrar/ocultar senha, aviso do ViaCEP direto na tela (sem `alert()`), filtros diretos na tabela de funcionários e ocultação de dados restritos (como telefone e CEP) para contas com nível de funcionário.

---

## 6. Dificuldades Encontradas e Como Foram Resolvidas

Uma das principais dificuldades visuais apareceu na matriz de sensores (`Sensor_Data.php` e painel principal) e no topo das telas: os cartões antigos deveriam indicar o status, mas apareciam todos escuros, e a logo sumia no cabeçalho.

Ao inspecionar o `Styles/style.css`, vimos que a classe `.border-tabela` tinha um `background-color: #0d1b2e !important;` que sobrescrevia o atributo `data-background="verde"`, além do problema de tentar mostrar o status só pela cor sem nenhum texto. Já a logo era um PNG preto transparente.

Resolvemos isso criando componentes padronizados (`.ops-*`) no `style.css` sem quebrar a página inicial, aplicando `filter: brightness(0) invert(1)` para deixar a logo branca sobre o fundo escuro, organizando as leituras e etiquetas com ícone + texto no painel principal e deixando a listagem estruturada nas páginas de sensores e relatórios.

Outra dificuldade ocorreu no contraste do botão azul principal: o tom `#5271ff` com letras brancas ficava um pouco abaixo do contraste recomendado para textos pequenos (`4,07:1`). Ajustamos o fundo do botão principal para `#3b59e8`, subindo o contraste para `5,56:1` sem perder a identidade azul do projeto.

---

## 7. Testes de Contraste, Legibilidade e Responsividade

Realizamos os seguintes testes para validar o funcionamento real das telas:

- **Contraste e leitura:** verificamos a proporção de contraste entre textos e fundos, garantindo leitura clara tanto nos textos principais brancos sobre azul escuro quanto nos botões e alertas.
- **Teclado e foco:** testamos a navegação usando a tecla `Tab` para confirmar o contorno azul claro (`:focus-visible`) nos links, campos e botões, além de rótulos corretos nos formulários.
- **Funcionamento PHP e MySQL:** testamos cadastros, edições, consultas, níveis de permissão e exclusões nos módulos ativos (usuários, trens, rotas e trilhos) e validamos a renderização sem erros de PHP nas páginas preparadas de sensores e relatórios no servidor local (XAMPP).
- **Responsividade em diferentes telas:** testamos o layout em larguras de computador (`1440px`), tablet (`768px`) e celular (`390px`), verificando o funcionamento do menu lateral, o empilhamento dos cartões e a rolagem interna das tabelas sem estourar a largura da página.

---

## 8. Fontes Consultadas

- W3C — Diretrizes de Acessibilidade para Conteúdo Web (WCAG 2.2): https://www.w3.org/TR/WCAG22/
- MDN Web Docs — Contraste de cores e acessibilidade: https://developer.mozilla.org/pt-BR/docs/Web/Accessibility/Understanding_WCAG/Perceivable/Color_contrast
- Documentação Oficial do Bootstrap 5.3: https://getbootstrap.com/docs/5.3/getting-started/introduction/
- Biblioteca Bootstrap Icons: https://icons.getbootstrap.com/
- Google Fonts (League Spartan, Inter e JetBrains Mono): https://fonts.google.com/
- Documentação da API Open-Meteo: https://open-meteo.com/en/docs
- Webservice ViaCEP: https://viacep.com.br/

---

## 9. Ferramentas Utilizadas

- **Visual Studio Code:** edição de código HTML, CSS, JavaScript, PHP e SQL.
- **XAMPP (Apache e MySQL/MariaDB):** execução local do sistema e testes com banco de dados.
- **Bootstrap 5, Bootstrap Icons:** estrutura responsiva, ícones e gráficos de monitoramento.
- **Google Chrome (DevTools):** inspeção de elementos e testes de responsividade em diferentes resoluções.