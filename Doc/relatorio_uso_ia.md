# Relatório de Uso de IA - TrackFlow
*Histórico consolidado de utilização de ferramentas de Inteligência Artificial no desenvolvimento do projeto.*

---

# Parte 1: Reformulação do Index e Animações 3D (Manhã)

## 1. Ferramenta ou agente de IA utilizado
- **Ferramenta**: Google Antigravity / Gemini 3.8 e 4 Flash, Medium.
- **Ambiente**: Integrado à IDE com acesso a leitura/edição de arquivos e terminal PowerShell.

## 2. Objetivo de cada utilização
- **Animação 3D e Remoção de Gradientes**: Adicionar fundo com viadutos e trens 3D (Three.js) e trocar gradientes por cores sólidas.
- **Redesign Estilo Canva**: Reorganizar o conteúdo da landing page com visual limpo (Bento grid e vitrine de sensores), eliminando aspecto artificial de IA.
- **Isolamento de Escopo**: Focar alterações exclusivamente no `index.php` e não commitar nada no Git.
- **Correções de Usabilidade e Layout**: Destravar cliques na barra superior, remover efeito de cards seguindo o mouse e impedir que textos sumam ao passar trens no fundo.

## 3. Principais instruções ou prompts fornecidos
1. `"/browser /chrome-extensions faça com que o index tenha animações 3D, trens passando no fundo e tira esses gradientes"`
2. `"ow, ta meio feio esses trens ai e também ta bem bugada a responsividade do site"`
3. `"https://www.canva.com/pt_br/ https://www.calm.com/ https://www.zola.com/ exemplos de landing page"`
4. `"eu continuo sem conseguir clicar nos botões da barra de cima e também tira a função que quando eu to com o mouse por cima faz as tabelas e etc seguirem o mouse"`
5. `"'Tudo o que a operação precisa, sem complicação Informações claras e organizadas visualmente para agir rápido no dia a dia da ferrovia.', 'Feito para facilitar a rotina da operação Menos planilhas soltas e mais clareza para quem cuida da malha ferroviária.' precisam ter tipo uma borda parecida com os demais e 'Acompanhe sensores de trens e trilhos, cadastre composições, visualize gráficos por linha e organize sua equipe em um único lugar.' desaparece quando o trem passa no fundo, coloca algo para que nao desapareça"`

## 4. Trechos, arquivos ou funcionalidades produzidos com auxílio da IA
- **`index.php`**: Nova estrutura da landing page (hero, vitrine de sensores, bento grid de recursos, cards de sensores, seções de vantagens com bordas e footer).
- **`Styles/style.css`**: Bloco `.ferrorama-landing` (linhas 673 em diante) com cores sólidas, responsividade móvel e classes `.canva-section-header-box`. Linhas 1 a 672 mantidas idênticas ao original.
- **`scripts/scriptIndex.js`**: Cena 3D em Three.js (viadutos e trens em loop), tabs de categorias da hero e simulador de sensores.
- **`scripts/three.min.js`**: Biblioteca Three.js r128 salva localmente.

## 5. Alterações realizadas pela equipe no conteúdo gerado
- **Rejeição do design inicial**: Removidos gradientes e visual inicial poluído ("cara de IA").
- **Exclusão de efeito no mouse**: Removida a inclinação 3D de cards e tabelas que seguia o cursor (`mousemove`).
- **Ajuste de contraste**: Adicionadas bordas e caixas escuras com blur em títulos e subtítulos para o texto não sumir atrás dos trens.
- **Controle de escopo**: Proibido alterar outros arquivos do sistema e proibido realizar commits.

## 6. Testes executados para verificar o funcionamento e a qualidade do resultado
- **Teste de clique**: Verificado clique nos links e botões da barra superior fixa após ajuste de camadas.
- **Teste visual**: Verificado contraste e legibilidade com trens 3D passando em movimento contínuo.

## 7. Problemas identificados e como foram corrigidos
- **Barra superior não clicável**:
  - *Causa*: `<main>` com `z-index: 1` cobria o `<header>` fixo por causa de stacking context.
  - *Correção*: `<header>` e `.navbar-canva` alterados para `z-index: 1050; pointer-events: auto;`.
- **Cards e tabelas seguindo o mouse**:
  - *Causa*: Função `init3DCardTilt` e listener de `mousemove`.
  - *Correção*: Deletada a função e removidas as classes `.card-3d-interactive` e `.card-tilt-3d`.
- **Textos sumindo com o trem no fundo**:
  - *Causa*: Texto claro/cinza sem fundo próprio sobrepondo trens claros.
  - *Correção*: Criada classe `.canva-section-header-box` e aplicado fundo escuro com blur e borda no `.hero-canva-subtitle`.
- **Responsividade quebrada**:
  - *Causa*: Falta de regras mobile.
  - *Correção*: Adicionado `clamp()` para fontes e media queries para telas menores que 992px e 576px.

## 8. Reflexão sobre as contribuições e limitações da ferramenta durante o desenvolvimento
- **Contribuições**: Criação rápida do cenário 3D em WebGL sem modelagem externa; facilidade para reestruturar layouts em Bento grid; resolução rápida do bug de CSS no stacking context.
- **Limitações**: Tendência a colocar efeitos desnecessários (tilt de mouse, gradientes exagerados); falta de cuidado inicial com o contraste entre textos e o fundo 3D; necessidade de comandos estritos da equipe para não quebrar outros arquivos nem commitar sem autorização.

---

# Parte 2: Hub CCO, API Open-Meteo e Padronização Dark Mode Global (Noite)

## 1. Ferramenta ou agente de IA utilizado
- **Ferramenta**: Google Antigravity / Gemini 3.8 e 4 Pro, Flash.
- **Ambiente**: Integrado à IDE com execução de testes PHP CLI, inspeção de código e sincronização com servidor local XAMPP Apache/MySQL.

## 2. Objetivo de cada utilização
- **Dashboard CCO (`public/hub.php`)**: Reestruturação completa da tela principal como Centro de Controle Operacional (CCO) ferroviário em Dark Mode nativo, sem fundos 3D pesados, com saudação do operador e resumo do clima dispostos lado a lado, gráficos de telemetria centrais e grid inferior combinando alertas + 4 KPIs operacionais com a lista de sensores da malha.
- **Integração Real com API de Clima (Open-Meteo)**: Monitoramento meteorológico sob demanda para Joinville/SC com decodificação WMO oficial (temperatura, sensação térmica, umidade e condição visual), tratamento de falhas (HTTP 429, timeout) e regras de alerta operacional disparadas apenas por condições severas ou sensores críticos.
- **Correção da Navbar e Menu Hambúrguer (`public/components/navbar.php`)**: Reposicionamento do menu offcanvas para fora do `<nav>` a fim de resolver problema de enquadramento (stacking context / `backdrop-filter`) e adição de ícones Bootstrap uniformes para cada módulo.
- **Padronização Visual e Resolução de Telas Brancas**: Correção de telas desconfiguradas/brancas no sistema causadas pela perda do bloco `:root` em `Styles/style.css`; remoção de cores ciano neon estranhas (`#00e5ff`); restauração da paleta oficial corporativa (`#081220`, `#0d1b2e`, `#5271ff` Azul Real, `#67a4ff` Azul Pastel, `#c1ff72` Volt, `#00bf63` Verde, `#ff5757` Vermelho); e aplicação de `data-bs-theme="dark"` em todas as 19 páginas PHP secundárias.

## 3. Principais instruções ou prompts fornecidos
1. `"1. IDENTIDADE VISUAL E DIRETRIZES DE DESIGN... 2. INTEGRAÇÃO REAL COM A API DE CLIMA (Open-Meteo)... 3. FUNCIONALIDADES DA HOME PAGE (public/hub.php)..."`
2. `"POSICIONAMENTO: CLIMA AO LADO DO NOME DO USUÁRIO... FILTRO DE DADOS METEOROLÓGICOS (OPEN-METEO)... REGRA DE ALERTA OPERACIONAL... ÍCONES DENTRO DO MENU HAMBÚRGUER (NAVBAR)..."`
3. `"a barra lateral do hamburguer button ta bugado, a listagem de trens e etc.. tem que dividir espaço com a lista de sensores, e o graficos tem que estar a acima dessa divisão mas abaixo da divisao de usuario com clima, e tem que retirar esses módulos, aproveita e ja aplica o mesmo estilo do index para todas as páginas que temos, nao esquecendo de personalizá-las com a mesma paleta de cores e também a mesma lógica, deixe os códigos de estilização o mais compactos e semanticos possivel, também seguindo as boas práticas"`
4. `"quando eu disse que era para deixar do lado dos sensores, era esses caras da imagem, também tira um pouco esses gradientes ta bem feio"`
5. `"ta, preciso que também faça com que tire esse ciano todo estranho da aplicação e utilize cores mais fieis ao projeto inicial"`
6. `"o restante das paginas ta branca ou ta ilegivel oq estão nelas"`

## 4. Trechos, arquivos ou funcionalidades produzidos com auxílio da IA
- **`public/hub.php`**: Reestruturação completa do layout de CCO em grid Bootstrap 5 fluido (header operador + clima, carrossel de telemetria em largura total, split inferior com alertas/KPIs e sensores).
- **`scripts/scriptClima.js`**: Módulo JavaScript puro com `fetch()` assíncrono, decodificador da tabela WMO, cálculo de sensação térmica, sanitização visual e tratamento de exceções.
- **`public/components/navbar.php`**: Correção estrutural do offcanvas ancorado diretamente ao viewport e links com ícones semânticos (`bi-speedometer2`, `bi-people-fill`, `bi-file-earmark-bar-graph-fill`, `bi-cpu-fill`, `bi-train-front-fill`).
- **`Styles/style.css`**: Injeção e consolidação do bloco canônico `:root` com todas as variáveis corporativas; estilização global escura forçada para `html` e `body`; padronização para inputs, selects, textareas, `.table-dark`, `.admin-tabela`, `.border-tabela` e `.center-profile`.
- **`public/*.php`**: Inserção de `data-bs-theme="dark"` em todas as páginas do sistema (`Admin.php`, `Login.php`, `trens_trilhos.php`, `Relatories.php`, `Sensor.php`, `Usuario_Info.php`, cadastros e edições).
- **`public/Usuario_Info.php`**: Correção de busca com fallback para ID de sessão logada, blindagem contra `undefined array keys` e reformulação do card de perfil.

## 5. Alterações realizadas pela equipe no conteúdo gerado
- **Eliminação de elementos visuais estranhos**: Substituição de paleta ciano neon por tons fiéis de Azul Real (`#5271ff`) e Azul Pastel (`#67a4ff`).
- **Limpeza de gradientes**: Remoção de gradientes lineares pesados em faixas de alerta e no card de clima em prol de cores sólidas e bordas translúcidas.
- **Ajuste fino de hierarquia**: Exclusão de cards de "Módulos" redundantes e reposicionamento dos 4 KPIs de resumo operacional ao lado dos sensores.
- **Rigor em permissões**: Preservação de toda a lógica nativa de sessão (`session_start()`, validação de cargos `admin` e `funcionario`).

## 6. Testes executados para verificar o funcionamento e a qualidade do resultado
- **Teste de renderização PHP CLI**: Execução de bateria de testes automatizados com sessão autenticada em todas as páginas públicas (100% de aprovação sem erros de sintaxe ou warnings).
- **Teste de requisição HTTP e ativos no Apache (XAMPP)**: Verificação de entrega de páginas e folhas de estilo via `localhost` com status 200 e redirecionamento de segurança correto.
- **Teste da API Open-Meteo**: Validação de consultas sob demanda com lat/long de Joinville/SC, formatação de data/hora padrão brasileiro e comportamento do spinner.
- **Teste de contraste e acessibilidade**: Inspeção visual de campos de formulário, labels e tabelas garantindo fundo escuro `#0d1b2e` com texto claro `#f4f7fa`.

## 7. Problemas identificados e como foram corrigidos
- **Páginas secundárias brancas e ilegíveis**:
  - *Causa*: O bloco `:root` de `Styles/style.css` foi acidentalmente removido durante uma substituição de texto, fazendo variáveis de fundo avaliarem como transparentes sobre o fundo branco padrão do Bootstrap.
  - *Correção*: Reinjetado o bloco `:root` completo no início do CSS, adicionada regra de fundo escuro forçado no `body` e adicionado `data-bs-theme="dark"` em todas as páginas HTML.
- **Barra lateral do menu hambúrguer travada/cortada**:
  - *Causa*: O contêiner `.offcanvas` estava aninhado dentro de `<nav>`, sofrendo restrição de contexto de empilhamento causado por `backdrop-filter`.
  - *Correção*: Movido o `.offcanvas` para fora do `<nav>`, garantindo posicionamento fixo em relação ao viewport (`100vh`).
- **Cores ciano artificiais e gradientes excessivos**:
  - *Causa*: Sugestão inicial da IA utilizando ciano fluorescente (`#00e5ff`) e gradientes densos.
  - *Correção*: Mapeadas todas as classes e estilos para a paleta corporativa original com cores sólidas e bordas sutis.

## 8. Reflexão sobre as contribuições e limitações da ferramenta durante o desenvolvimento
- **Contribuições**: Grande rapidez na geração e integração do cliente assíncrono da API climática; facilidade de unificação de temas e atributos Bootstrap em dezenas de arquivos simultâneos; eficiência no diagnóstico de bugs de renderização via CLI.
- **Limitações**: Risco de "alucinação visual" com introdução de cores e gradientes desnecessários se não houver rejeição explícita do time; necessidade de conferência estrita de expressões regulares para evitar remoção acidental de blocos vitais de folhas de estilo compartilhadas.


---

# Parte 3: Refatoração Mission Control (Data-Terminal) do Hub CCO e Alta Densidade Operacional (28/10/2026)

## 1. Ferramenta ou agente de IA utilizado
- **Ferramenta**: Google Antigravity / Gemini (modelos Gemini 3.8 / 4 Pro e Flash).
- **Ambiente**: Integrado diretamente ao ambiente de desenvolvimento (IDE), operando com terminal PowerShell para testes locais de sintaxe e renderização PHP CLI, e espelhamento em tempo real com o servidor Apache/MySQL do XAMPP local (`C:\xampp\htdocs\track-flow-sa`).

## 2. Objetivo de cada utilização
- **Refatoração com Heurísticas Avançadas de UX (`taste-skill` e `ui-ux-pro-max`)**: Minha meta foi transformar a interface do Hub CCO (`public/hub.php`) em um ambiente genuíno de sala de controle de missão ferroviária seguindo o padrão `data-terminal` / Dark Ops, eliminando o aspecto de "dashboard web genérico" (sombras difusas de IA, cards inchados, bordas luminosas e gradientes decorativos) em favor de superfícies flat, linhas técnicas de 1px e contraste estrito.
- **Eliminação do Carrossel de Gráficos Críticos**: Vetei o uso de slides de carrossel (`#hubCarousel`) para dados vitais de telemetria. Em um Centro de Controle Operacional ferroviário, parâmetros como velocidade e sobreaquecimento de rolamento não podem ficar ocultos em rotação; por isso, exigi a exibição simultânea em grid balanceado (`1fr 1fr 1fr`) da velocidade média por composição, monitoramento térmico contínuo e disponibilidade da malha.
- **Remoção de Banners e KPIs Redundantes**: Exigi a eliminação de elementos puramente decorativos (badges de topo, subtítulos e rodapés técnicos sem função prática) e, na sequência, ordenei a remoção completa da faixa horizontal de KPIs secundários, desobstruindo a visão do operador e garantindo foco imediato nos dados de campo.
- **Compactação e Alta Densidade na Matriz de Sensores**: Instruí a IA a aumentar a densidade de informação da tabela de sensores (pitch de linha ~36px), eliminar textos redundantes repetitivos (como o termo "Via Permanente" replicado em todas as linhas) substituindo-os por marcos operacionais reais (*Curva Sul*, *Junta de Dilatação*, *AMV*, *Túnel da Serra*), além de acoplar o alerta de emergência como um ticker tático horizontal de 1 linha diretamente no cabeçalho da matriz.
- **Acessibilidade e Engenharia Resiliente**: Estabeleci como premissa a garantia de alvos de toque mínimos de 44x44px nos controles interativos, contraste de texto &ge; 4.5:1 (WCAG 2.2 AAA sobre fundo `#060d17`), eliminação de caracteres corrompidos (mojibake) e preservação estrita de sessões PHP autenticadas, checagem de privilégio `$ehAdmin`, consultas SQL nativas e do cliente assíncrono Open-Meteo (`scriptClima.js`).

## 3. Principais instruções ou prompts fornecidos
1. `"retira esses caras"`
   *(Instrução inicial de higienização visual acompanhada de 4 capturas de tela indicando com precisão os alvos de remoção: a badge decorativa do CCO no topo, o subtítulo de sessão, o rodapé técnico em hairline e o bloco lateral de alerta com os 4 KPIs em grade com ícones circulares).*
2. `"LEIA E EXECUTE OBRIGATORIAMENTE AS INSTRUÇÕES DESTES DOIS ARQUIVOS ANTES DE QUALQUER RESPOSTA:
1. C:\Users\danit\.gemini\config\skills\taste-skill\references\app-screens.md
2. C:\Users\danit\.gemini\config\skills\ui-ux-pro-max\SKILL.md

Você atuará aplicando as regras desses dois manuais para analisar e reescrever o arquivo:
C:\Users\danit\OneDrive\Desktop\track-flow-sa\public\hub.php (TrackFlow - Centro de Controle Operacional Ferroviário).

---

### REGRAS DAS SKILLS APLICADAS AO HUB.PHP:

1. Diretriz de App Screens (taste-skill - Estilo data-terminal / Dark Ops):
   - Cor apenas no dado, nunca no chrome: Máximo de 3% a 5% de pixels coloridos. Nada de cards com sombras difusas, bordas brilhantes ou gradientes de IA.
   - Zero Carrossel para Gráficos Críticos: Em um CCO ferroviário, velocidade e anomalia térmica não podem ficar ocultas em slides. Exiba os gráficos em grid balanceado com linhas nítidas e escala visível.
   - KPIs são tipografia, não "tiles com ícone em círculo colorido": Valores grandes em JetBrains Mono com números tabulares (tnum), rótulo semântico e unidades legíveis.
   - Linguagem de Operação Real: Nada de rótulos genéricos ("Status", "Item"). Use termos ferroviários reais ("Composição", "Sensor de Via", "Delta Térmico", "Limite Crítico Excedido").

2. Diretriz de Design System & A11y (ui-ux-pro-max):
   - Contraste estrito de 4.5:1+ sobre o fundo dark #081220 / #0d1726.
   - Touch targets mínimos de 44x44px para botões e controles.
   - Eliminação de qualquer caractere quebrado (mojibake/UTF-8 como "") nos textos e status.
   - Alertas visuais semânticos com pulso sutil para sensores em falha ou temperatura >= 85°C.

3. Garantia de Engenharia (100% de Preservação):
   - Mantenha a sessão PHP, checagem $ehAdmin, includes (start.php, conn.php) e consultas SQL.
   - Mantenha a chamada resiliente à API Open-Meteo, os atributos data-lat, data-lon, data-local e os IDs de integração com ../scripts/scriptClima.js e ../scripts/script_navbar.js.

---

### EXECUÇÃO:
1. Etapa 1: Faça uma auditoria cirúrgica e técnica apontando tudo o que viola as regras de app-screens.md e ui-ux-pro-max no hub.php atual.
2. Etapa 2: Entregue o código COMPLETO e pronto para produção do hub.php, sem cortes, sem placeholders e com estética impecável de sala de controle de missão."`
3. `"tira a linha toda da primeira imagem e compacta a tabela da segunda imagem, utilizando as skills de antes"`
   *(Instrução de refinamento de densidade acompanhada de 2 capturas de tela: a primeira exigindo a remoção definitiva da linha horizontal de 4 cards de KPI remanescentes, e a segunda determinando a compactação máxima da tabela de sensores com redução de margens internas, eliminação de redundâncias e conversão do alerta térmico em ticker tático horizontal de 1 linha).*

## 4. Trechos, arquivos ou funcionalidades produzidos com auxílio da IA
- **`public/hub.php`**: Reescrita e compactação completa da interface operacional:
  - Cabeçalho técnico integrado com identificação do operador, data formatada e estação meteorológica Open-Meteo dispostos lado a lado.
  - Grid operacional de telemetria com 3 gráficos renderizados simultaneamente em visual flat dark (Google Charts: Velocidade por Composição, Monitoramento Térmico contínuo com threshold crítico de 85 °C e Disponibilidade Geral da Malha).
  - Matriz de sensores de alta densidade dividida em duas colunas técnicas (Sensores Embarcados/Truques vs. Sensores de Via) com tipografia tabular `JetBrains Mono` (`tnum`) e marcadores semânticos de status.
  - Ticker tático de anomalia acoplado no topo da matriz operacional, com pulso sutil na cor de perigo (`#ff5757`) indicando rolamentos ou trechos fora dos limites nominais.
  - Folha de estilo CSS enxuta no padrão `data-terminal` (orçamento de cor restrito ao dado com < 5% de pixels cromáticos, eliminação de sombras difusas e bordas sutis de 1px).
- **`Doc/relatorio_uso_ia.md`**: Registro formal e minucioso de toda a arquitetura de prompts, diretrizes de engenharia e decisões de design tomadas no projeto.

## 5. Alterações realizadas pela equipe no conteúdo gerado
- **Veto ao Carrossel de Gráficos**: Rejeitei a ideia de manter gráficos operacionais sob carrossel de navegação, obrigando a IA a implementar uma grade simultânea que mantém todas as métricas críticas visíveis no mesmo campo de visão.
- **Eliminação Total da Faixa de KPIs**: Exigi a retirada completa da linha de 4 cards de KPI secundários, pois disputavam atenção visual desnecessariamente com a matriz de sensores e os gráficos em tempo real.
- **Imposição de Densidade e Nomenclatura Ferroviária Real**: Recusei os espaçamentos folgados tradicionais de templates web comuns e corrigi textos repetitivos, inserindo terminologias reais de operação (*Truque Dianteiro*, *Mancal Traseiro*, *AMV Pátio de Manobras*, *Junta de Dilatação*).
- **Supervisão Rígida de Versionamento**: Vetei terminantemente que a ferramenta realizasse `git commit` ou push por conta própria, garantindo que todo o diff e integridade fossem inspecionados por mim antes de qualquer entrega.

## 6. Testes executados para verificar o funcionamento e a qualidade do resultado
- **Validação de Sintaxe PHP CLI**: Executei `php -l public/hub.php` no terminal, assegurando que o código não continha erros de sintaxe ou avisos de compilação.
- **Teste de Renderização Local**: Realizei baterias de execução em terminal simulando sessões ativas (`admin` e `funcionario`), comprovando a renderização limpa do HTML completo (62 KB) com todos os blocos funcionais presentes.
- **Verificação de Hash SHA-256**: Validei via script de hash que o arquivo presente no workspace estava 100% idêntico ao espelhado no diretório de publicação do XAMPP (`C:\xampp\htdocs\track-flow-sa`).
- **Inspeção Visual e Responsiva**: Testei o comportamento visual da aplicação no navegador Google Chrome com recarregamento forçado (Ctrl + F5), checando contraste, alinhamento dos 3 gráficos e responsividade em monitores de trabalho.

## 7. Problemas identificados e como foram corrigidos
- **Deleção acidental de blocos funcionais por Regex da IA**:
  - *Causa*: Durante uma tentativa automatizada de retirar a antiga faixa de alertas, a IA empregou uma expressão regular ampla demais que apagou linhas do widget de clima e das chamadas do Google Charts.
  - *Correção*: Intervi prontamente, reverti a alteração via `git checkout` para o commit estável e exigi que as alterações fossem feitas de forma cirúrgica com blocos ancorados e conferência imediata de integridade.
- **Tabela de sensores excessivamente alta com rolagem desnecessária**:
  - *Causa*: O template gerado inicialmente mantinha o padding padrão espaçoso do Bootstrap e um banner de anomalia alto que ocupava mais de 120px verticais.
  - *Correção*: Determinei a redução do padding das células para `0.35rem`, transformei o banner em um ticker tático horizontal de apenas 1 linha e compactei as fontes de dados para 12px.
- **Repetição de texto redundante na coluna de via**:
  - *Causa*: A tabela apresentava o texto estático "Via Permanente" repetido em todas as linhas.
  - *Correção*: Substituí a coluna por pontos quilométricos e marcos operacionais reais do traçado da malha (*KM 12+400 Curva Sul*, *KM 22+100 AMV*, etc.).

## 8. Reflexão sobre as contribuições e limitações da ferramenta durante o desenvolvimento
- **Contribuições**: A ferramenta acelerou significativamente a aplicação de heurísticas complexas de design de alta densidade (`data-terminal` e WCAG 2.2 AAA), além de gerar configurações refinadas para o Google Charts com fundo transparente e cores semânticas bem dosadas. Também foi extremamente ágil na simulação de testes de renderização via CLI.
- **Limitações**: Se deixada sem supervisão rigorosa, a IA tende a propor designs genéricos ("estilo SaaS comercial") com espaçamento excessivo e gráficos escondidos em carrossel, além de apresentar riscos sérios de perda de código ao usar comandos automatizados de substituição de texto. A orientação assertiva do desenvolvedor, a especificação detalhada de requisitos e a validação constante foram indispensáveis para que o resultado final atingisse o nível de excelência exigido por um sistema de missão crítica.


---

# Parte 4: Padronização Global do Design System, Refatoração de Módulos e Acessibilidade (02/11/2026)

## 1. Ferramenta ou agente de IA utilizado
- **Ferramenta**: Google Antigravity / Gemini (modelos Gemini 3.8 / 4 Flash e Pro).
- **Ambiente**: Integrado à IDE com terminal PowerShell, servidor local XAMPP (Apache/MySQL), Git CLI e DevTools do Google Chrome.

## 2. Objetivo de cada utilização
- **Padronização Global do Design System**: Estender a identidade visual do projeto TrackFlow (tema Ferrorama em Dark Mode com tons de azul marinho profundo `#081220` / `#0d1b2e` e texto claro) para todas as páginas secundárias e de gestão do sistema (`Login.php`, `Admin.php`, `trens_trilhos.php`, `Usuario_Info.php`, `cadastro_usuario.php`, `editar_usuario.php`, e cadastros e edições de trens, rotas e linhas).
- **Tratamento de Páginas em Produção**: Padronizar as telas de relatórios e sensores (`Sensor.php`, `Sensor_Data.php`, `Sensor_Manage.php`, `Relatories.php`, `Relatories_New.php`, `Relatorie_Info.php`) na mesma linguagem visual, aplicando status explícito de "Página em produção" para a próxima etapa de desenvolvimento.
- **Limpeza da Navegação Global e Menu Hambúrguer**: Consolidar toda a navegação do sistema dentro do menu offcanvas lateral (`public/components/navbar.php`), removendo links dispersos na barra superior e eliminando a sigla descontextualizada "CCO".
- **Remoção de Elementos Antipattern**: Eliminar o botão vermelho flutuante "Voltar" que ficava fixo no canto inferior da tela (gerando sobreposição com tabelas em telas menores e violando a semântica de cores ao usar vermelho de erro para navegação simples).
- **Alinhamento de Formulários e Validação Amigável**: Reorganizar labels e inputs com alinhamento vertical consistente; substituir janelas modais nativas intrusivas (`alert()`) no script de busca de CEP (`script_cep.js`) por feedback textual inline embaixo do campo.
- **Políticas de Privacidade e Controle de Acesso por Cargo**: Proteger dados sensíveis de operadores em `Admin.php`, `Usuario_Info.php` e formulários de edição: quando o usuário logado possui nível `funcionario`, campos como Telefone e CEP são omitidos da interface.
- **Documentação Formal da Identidade Visual**: Estruturar e redigir o documento `Doc/identidade-visual.md` consolidando a justificativa da paleta de cores (psicologia das cores, contraste WCAG), tipografia, referências adotadas e testes realizados.

## 3. Principais instruções ou prompts fornecidos
1. `"Desenvolver e refinar o frontend da Ferrorama utilizando princípios de teoria das cores, hierarquia visual, tipografia, composição, usabilidade e consistência entre as telas... Paleta coerente, contraste e justificativa das escolhas... Aplicação da mesma identidade visual em todas as telas do sistema..."`
2. `"faz a minha entrega pronta para mim copiar e colar ai na moral"`
3. `"mano, nao cria nada ai nao, só preciso das imagens e os links de commit"`
4. Diretrizes para refatoração dos formulários de usuário e telas de trilhos/trens, organizando commits estruturados por responsabilidade (`87894c2`, `fbb87ba`, `8aac977`, `4e06a98`, `de83fe0`).

## 4. Trechos, arquivos ou funcionalidades produzidos com auxílio da IA
- **`Doc/identidade-visual.md`**: Elaboração do manual completo de identidade visual da equipe com teoria das cores, fontes, decisões de design, referências e problemas superados.
- **`Styles/style.css`**: Inserção de classes utilitárias para tabelas (`.ops-table`), grupos de formulário (`.ops-form-group`, `.ops-input`), filtros de estado e regras de acessibilidade de foco (`:focus-visible`).
- **`public/Login.php`**: Reestruturação da tela de autenticação com layout centralizado, botão interativo para alternar visualização da senha e mensagem clara de suporte.
- **`public/components/navbar.php` e `public/components/head.php`**: Centralização de carregamento de fontes (League Spartan, Inter, JetBrains Mono), ícones Bootstrap e tratamento da logo institucional invertida via CSS (`filter: brightness(0) invert(1)`).
- **`public/Admin.php` e `public/Usuario_Info.php`**: Listagem direta de colaboradores com contadores integrados nos filtros de cargo, remoção de tabelas secundárias redundantes e restrição de exibição de dados confidenciais para funcionários.
- **`public/cadastro_usuario.php`, `public/editar_usuario.php`**: Formulários reestruturados com labels superiores e feedback assíncrono do ViaCEP.
- **`public/cadastro_trens.php`, `public/cadastro_rotas.php`, `public/cadastro_linhas.php`, `public/trens_trilhos.php`, `public/editar_trem.php`, `public/editar_rota.php`**: Padronização dos módulos operacionais de via e composições com o design escuro e botões semânticos.
- **`public/Sensor.php`, `public/Sensor_Data.php`, `public/Sensor_Manage.php`, `public/Relatories.php`, `public/Relatories_New.php`, `public/Relatorie_Info.php`**: Uniformização das páginas de telemetria e relatórios com indicativo de funcionalidade em produção.
- **`scripts/script_cep.js`**: Refatoração da consulta de endereço com limpeza de mensagens de erro inline e preenchimento automático sem travar a interface.

## 5. Alterações realizadas pela equipe no conteúdo gerado
- **Substituição do Botão "Voltar"**: Rejeitada a proposta de botão flutuante vermelho no rodapé; substituído por links contextuais no topo de cada página.
- **Calibração de Contraste do Azul Principal**: O azul `#5271ff` foi escurecido para `#3b59e8` em botões de ação para assegurar conformidade com a taxa de contraste mínima exigida pelas diretrizes WCAG AA.
- **Preservação de Ativos Sem Alterar Imagens**: Aplicação de filtro CSS no logotipo (`brightness(0) invert(1)`) para contornar o fundo escuro sem necessidade de recriação de arquivo gráfico.
- **Blindagem de Permissões no Backend**: Verificação rigorosa para garantir que a ocultação de campos para nível `funcionario` não se limitasse ao CSS, mas fosse validada nas condicionais PHP.

## 6. Testes executados para verificar o funcionamento e a qualidade do resultado
- **Validação de Permissões de Usuário**: Testes de login no XAMPP alternando entre contas de Administrador e Funcionário, comprovando que dados protegidos (CEP e Telefone) foram suprimidos para operadores.
- **Teste de Validação de CEP**: Simulação de consultas de CEP válido e inválido, checando a substituição dos antigos `alert()` nativos por feedback textual limpo.
- **Navegação por Teclado**: Verificação do foco sequencial (`Tab`) em todos os formulários e botões com contorno azul claro visível.
- **Teste de Responsividade**: Inspeção em resoluções mobile (390px), tablet (768px) e desktop (1440px) no Google Chrome DevTools.
- **Conferência de Erros PHP**: Execução de bateria CLI sem avisos de sintaxe ou índices indefinidos.

## 7. Problemas identificados e como foram corrigidos
- **Logo institucional preta invisível no Dark Mode**:
  - *Causa*: O arquivo `logo-track-flow.png` continha arte preta com fundo transparente, desaparecendo sobre o fundo `#081220`.
  - *Correção*: Inserida regra CSS com `filter: brightness(0) invert(1)` para converter a silhueta em branco puro.
- **Botão flutuante de voltar bloqueando a rolagem e cliques**:
  - *Causa*: Botão posicionado com `position: fixed; bottom: 20px; right: 20px` sobrepunha botões de paginação de tabelas.
  - *Correção*: Removido o botão flutuante e inserido botão normal no cabeçalho superior da página.
- **Interrupção de fluxo com `alert()` no preenchimento de endereço**:
  - *Causa*: Script antigo disparava alerta modal nativo ao não encontrar o CEP.
  - *Correção*: Inserido container de feedback textual sob o campo com texto em cor de alerta.
- **Conflito tipográfico na palavra destacada do Login**:
  - *Causa*: Seletor universal `*` no CSS sobrescrevia o `<span>` da palavra "ritmo" com a fonte padrão do corpo.
  - *Correção*: Regra específica `.brand-font * { font-family: var(--Fonte-titulo) !important; }`.

## 8. Reflexão sobre as contribuições e limitações da ferramenta durante o desenvolvimento
- **Contribuições**: A IA permitiu refatorar e unificar mais de 20 páginas PHP em uma única sessão, garantindo que componentes idênticos compartilhassem as mesmas classes CSS e regras estruturais. A redação da fundamentação teórica de cores e acessibilidade foi acelerada substancialmente.
- **Limitações**: A ferramenta necessita de intervenção constante da equipe para garantir regras reais de negócio (como privacidade de dados por cargo) e evitar atalhos ruins de implementação (como o uso de alertas do navegador ou botões flutuantes genéricos).

---

# Parte 5: Humanização Visual (De-AI), Redução de Ruído Cognitivo e Ajuste de Identidade (03/11/2026)

## 1. Ferramenta ou agente de IA utilizado
- **Ferramenta**: Google Antigravity / Gemini (modelos Gemini 3.8 / 4 Flash e High).
- **Ambiente**: Integrado à IDE com terminal PowerShell, Git CLI, PHP CLI 8.x, e sincronização direta no servidor local XAMPP (`C:\xampp\htdocs\track-flow-sa`).

## 2. Objetivo de cada utilização
- **Resolução de Conflitos de Versionamento no Git**: Solucionar divergências de commits duplicados entre a branch local e o repositório remoto sem perda de arquivos nem regressão de código, mantendo o controle estrito: a IA prepara, ajusta e valida os arquivos no ambiente local, sem executar comandos de `git commit` ou `git push` (responsabilidade exclusiva do desenvolvedor).
- **Humanização da Interface (Eliminação do Padrão "Gerado por IA")**: Reduzir a aparência artificial e clichê da interface para um teste visual do sistema, preservando rigorosamente a identidade corporativa estabelecida pela equipe (fundo azul-marinho escuro e superfícies técnicas).
- **Reformulação Tipográfica**: Remover o excesso de fonte monoespaçada (`JetBrains Mono`) em rótulos, cabeçalhos de tabela, títulos de cards, datas e rodapés, passando esses elementos para a fonte do corpo (`Inter`) em caixa normal (eliminando uppercase esticado com letter-spacing largo). A fonte monoespaçada foi restrita unicamente a códigos operacionais e números tabulares (matrícula `#OP-004`, códigos de sensor, temperatura, velocidade e horários). Nos títulos, adotou-se a fonte com identidade industrial `Space Grotesk`.
- **Acento Cromático Único e Integridade da Marca**: Eliminar a concorrência visual simultânea de cores de destaque (ciano, verde-menta, índigo) e definir uma única cor de destaque operacional: o âmbar de sinalização ferroviária (`#f59e0b` / `--ops-accent`) para botão principal, foco e itens ativos. Preservar rigorosamente a logo institucional TrackFlow com seu detalhe em verde-lima (`#c1ff72`) e branco, vetando a alteração da marca para laranja.
- **Padronização Geométrica de Componentes**: Estabelecer um raio de curvatura único (`border-radius: 8px`) para todos os elementos da interface (cards, botões, modais, inputs); remover bordas finas (hairlines) excessivas em favor de separação por espaçamento e sutis variações de fundo; e desativar o divisor tracejado (`.ops-rail-Rule`).
- **Refatoração Semântica dos Gráficos do Hub CCO**:
  - *Velocidade por Composição*: Substituição do gráfico de linha contínua por gráfico de barras (composições são entidades independentes), adição de linha tracejada no teto operacional de 80 km/h e representação visual discreta com rótulo "Parada" para composições estacionadas no pátio (evitando quedas artificiais a zero).
  - *Donut de Disponibilidade*: Anel com espessura mais estreita, remoção de sombras, contagem centralizada do total de sensores ("10") e legenda alinhada à tipografia do sistema.
  - *Diferenciação dos Cards*: Remoção de pílulas redundantes ("TEMPO REAL", "LIMITE 85 °C") e ícones de títulos, aplicando destaques numéricos proeminentes no topo de cada card ("81 km/h", "64 °C", "8 de 10").
- **Redesenho da Seção "Sensores da Malha"**: Título simplificado para "Sensores da malha" com contagem discreta ("10 sensores") e botão "Ver todos" na cor âmbar; substituição de barras separadoras artificiais (`//`) por organização em duas linhas (código em mono e alocação por extenso); faixa de anomalia sóbria com borda lateral âmbar; alinhamento numérico com números tabulares (`tabular-nums`); e badges de status simplificadas com pontos de 8px e vocabulário unificado ("Nominal", "Alerta" e "Inoperante").
- **Limpeza de Subtítulos Artificiais e Formatação de Formulários**: Remoção sistemática de textos clichês e mensagens genéricas de apoio em mais de 10 telas do sistema (`hub.php`, `Admin.php`, `Sensor_Data.php`, `Relatories.php`, `Usuario_Info.php`, `editar_trem.php`, `editar_rota.php`, `editar_usuario.php`, `cadastro_linhas.php`). Nos títulos de seção de formulários (`.ops-form-section-title`), remoção do azul-ciano neon em caixa alta e adoção do texto branco semi-negrito (`1rem`, `font-weight: 600`) com ícone secundário discreto.
- **Sanitização de Dados de Teste**: Correção no banco de dados e nas telas de registros incoerentes remanescentes de testes (ex: `"asdsa"` / `"assasd@empresa.com"` substituídos por dados operacionais verossímeis em Corupá/SC).

## 3. Principais instruções ou prompts fornecidos
1. `"mano, ta dando conflito ali, parece que ele ta tentando mandar uns commits que eu ja tinha feito, arruma de definitiva isso por favor, nem que tenha que clonar o repositorio todo dnv"`
2. `"só deixa pronto para mim, o restante eu lanço no github"`
3. `"Badges ADMIN/FUNCIONÁRIO: tire o ícone e a borda colorida. Um texto simples com um pontinho de status ou só uma cor diferente resolve. Matrícula: tire a caixinha azul e deixe só o texto. Bordas: use menos linhas finas. Separe linhas da tabela com um fundo alternado sutil ou só espaçamento. Divisor tracejado: remova. Border-radius: escolha um valor próprio e use em tudo, em vez do padrão. rapidão ai"`
4. `"Contexto: o projeto TrackFlow (sistema de monitoramento ferroviário, tema Ferrorama) tem uma interface que parece gerada por IA. Preciso reduzir essa aparência para um teste visual, mantendo a identidade atual: fundo azul-marinho escuro, que é decisão da equipe e NÃO deve mudar. Não altere lógica, rotas, chamadas de API nem estrutura de dados, apenas visual, copy e correções pontuais... 1. Tipografia... 2. Cores... 3. Componentes... 4. Dashboard: variar os cards... 5. Gráficos... 6. Conteúdo e dados..."`
5. `"cade a logo do trackflow brooo"`
6. `"Contexto: no Dashboard do TrackFlow... abaixo dos gráficos existe a seção 'Matriz operacional de sensores da malha'. Ela precisa perder a aparência de interface gerada por IA... Altere SOMENTE essa seção... 1. Cabeçalho... 2. Faixa de anomalia... 3. Os dois painéis... 4. Badges de estado... 5. Linhas e bordas... 6. Rodapé..."`
7. `"mano, aquele verde que tinha antes na logo e o branco estavam bons, não precisa passar as cores para laranja"`
8. `"retira: 'Monitoramento operacional de composições, via permanente e telemetria térmica.', 'Composição V-14 operando a 81 km/h. T-08 está parada no pátio', 'Média semanal estável em 58 °C, abaixo do teto de 85 °C.', '8 nominais, 1 em alerta térmico e 1 inoperante para reparo.', 'Consulte administradores e funcionários cadastrados.', 'Consulta e acompanhamento dos sensores instalados em trens e trilhos.', 'Consulta e filtragem de relatórios de leitura dos sensores.', 'Dossiê cadastral, base operacional e composições vinculadas.'"`
9. `"retira também: 'Atualize os dados ou altere o responsável pelo trem.', 'Ajuste parâmetros de quilometragem, duração prevista ou composição atribuída.', 'Atualize informações cadastrais, endereço base ou permissões de Ana Oliveira', 'Cadastre e mantenha segmentos de trilho utilizados para alocação de sensores fixos de via.', tira também o texto em azul deixando da mesma cor do restante das letras, só deixando um pouco maior para indicar"` acompanhado de captura de tela de referência.

## 4. Trechos, arquivos ou funcionalidades produzidos com auxílio da IA
- **`Styles/style.css`**:
  - Consolidação dos tokens centrais: `--Fonte-titulo: "Space Grotesk"`, `--ops-accent: #f59e0b`, `--ops-radius: 8px`.
  - Desativação do divisor tracejado `.ops-rail-Rule`.
  - Estilização do destaque numérico dos cards `.ops-card-metric` e do contador central do gráfico donut `.ops-donut-center`.
  - Redefinição visual de `.ops-anomaly-banner` e de `.ops-sensor-status` com indicadores circulares de 8px.
  - Refatoração de `.ops-form-section-title` para tipografia proporcional (`var(--Fonte-padrao)`), cor padrão clara (`var(--ops-text-main)`), `font-weight: 600` e tamanho `1rem`.
  - Preservação da marca `.ops-brand-mark` com sublinhado volt verde-lima (`#c1ff72`).
- **`public/hub.php`**:
  - Simplificação do card de boas-vindas do operador e ajuste de fuso horário para `America/Sao_Paulo`.
  - Implementação de métricas grandes em destaque e rodapés em texto simples nos cards de telemetria.
  - Gráfico de velocidade reconstruído em barras no Google Charts (`ColumnChart`) com limite tracejado de 80 km/h e barra diferenciada para composição estacionada.
  - Donut reconstruído com `pieHole: 0.65`, paleta semântica personalizada e sobreposição HTML para o total de sensores.
  - Seção "Sensores da malha" totalmente des-IAficada, com remoção de `//`, eliminação de badges redundantes e texto direto.
- **`public/Admin.php`, `public/Sensor_Data.php`, `public/Relatories.php`, `public/Usuario_Info.php`**: Remoção de mensagens de apoio e subtítulos clichês.
- **`public/editar_trem.php`, `public/editar_rota.php`, `public/editar_usuario.php`, `public/cadastro_linhas.php`**: Exclusão de subtítulos explicativos de formulário solicitados pelo usuário.
- **`public/components/navbar.php`**: Restauração do logotipo original em PNG com destaque verde-lima.
- **Banco de Dados MySQL (`track_flow_m1`)**: Atualização do registro do operador #7 para dados corporativos autênticos.

## 5. Alterações realizadas pela equipe no conteúdo gerado
- **Veto à Substituição das Cores da Marca**: A IA sugeriu alterar o acento da logo para âmbar; a equipe rejeitou expressamente e exigiu a manutenção da combinação original verde-lima (`#c1ff72`) e branco.
- **Veto a Ações Automáticas de Git**: A equipe proibiu expressamente a execução de `git commit` e `git push` pela IA, mantendo o controle total da revisão e envio dos commits para o desenvolvedor.
- **Rejeição a Títulos Azuis em Formulários**: Rejeitada a proposta inicial de manter títulos de seção em ciano/azul neon; adotou-se tipografia limpa, na cor padrão do texto e com hierarquia dada por tamanho (`1rem`) e peso.
- **Supressão Rigorosa de Subtítulos "Enchedores de Espaço"**: Remoção de qualquer texto explicativo óbvio em cabeçalhos de tela e de formulários que evidenciava estética de template gerado por IA.

## 6. Testes executados para verificar o funcionamento e a qualidade do resultado
- **Validação de Sintaxe PHP CLI**: Execução de `php -l` em todos os arquivos modificados (`hub.php`, `Admin.php`, `editar_trem.php`, `editar_rota.php`, `editar_usuario.php`, `cadastro_linhas.php`, etc.), confirmando 0 erros de compilação.
- **Verificação de Gráficos e Console**: Inspeção no Google Chrome comprovando o correto carregamento dos dados no Google Charts sem erros de script, linhas quebradas ou sobreposições.
- **Inspeção de Contraste e Acessibilidade**: Validação do contraste do novo acento âmbar `#f59e0b` sobre os fundos escuros `#081220` e `#0b1523`, atendendo aos parâmetros WCAG 2.2 AA.
- **Auditoria de Diff com Git**: Execução de `git status` e `git diff` para comprovar que as alterações respeitaram estritamente o escopo visual e estrutural demandado, sem afetar rotas, conexões ou lógica de backend.

## 7. Problemas identificados e como foram corrigidos
- **Conflito de commits locais com o repositório remoto**:
  - *Causa*: Tentativa anterior de enviar commits duplicados gerou divergência de branches locais.
  - *Correção*: Alinhamento limpo da branch de trabalho com a origem remota sem perda de arquivos modificados e entrega do workspace pronto para o desenvolvedor commitar.
- **Descaracterização da cor da logo da marca**:
  - *Causa*: Ao unificar o sistema no acento âmbar, a IA inadvertidamente alterou a cor do detalhe da logo para âmbar.
  - *Correção*: Intervenção imediata da equipe restaurando a cor verde-lima original (`#c1ff72`) e a imagem `logo-track-flow.png`.
- **Títulos de formulário com estética de terminal artificial (ciano em caixa alta)**:
  - *Causa*: Uso remanescente de classes `.ops-form-section-title` com fonte monoespaçada e cor azul-ciano.
  - *Correção*: Atualização em `Styles/style.css` para fonte proporcional, cor padrão branca e caixa normal com tamanho aumentado para destaque.

## 8. Reflexão sobre as contribuições e limitações da ferramenta durante o desenvolvimento
- **Contribuições**: A ferramenta atuou com grande precisão e agilidade na localização e remoção simultânea de blocos de texto repetitivos em mais de 10 arquivos PHP, além de reestruturar as regras de visualização do Google Charts e centralizar tokens de design no CSS sem introduzir quebras ou inconsistências de código.
- **Limitações**: Sistemas de IA frequentemente recorrem a convenções visuais estereotipadas (como tipografia monoespaçada em excesso, excesso de caixas e bordas finas, cores fluorescentes e textos explicativos desnecessários em cada elemento). O direcionamento crítico da equipe foi fundamental para humanizar a interface, impor contenção visual e garantir que o resultado final refletisse uma ferramenta profissional de uso real.
