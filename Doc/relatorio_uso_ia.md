# Relatório de Uso de IA - 26/10/2026

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
