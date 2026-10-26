# Relatório de Uso de IA - 26/10/2026

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
3. `"ow, ta meio feio esses trens ai e também ta bem bugada a responsividade do site"`
4. `"https://www.canva.com/pt_br/ https://www.calm.com/ https://www.zola.com/ exemplos de landing page"`
5. `"eu continuo sem conseguir clicar nos botões da barra de cima e também tira a função que quando eu to com o mouse por cima faz as tabelas e etc seguirem o mouse"`
6. `"'Tudo o que a operação precisa, sem complicação Informações claras e organizadas visualmente para agir rápido no dia a dia da ferrovia.', 'Feito para facilitar a rotina da operação Menos planilhas soltas e mais clareza para quem cuida da malha ferroviária.' precisam ter tipo uma borda parecida com os demais e 'Acompanhe sensores de trens e trilhos, cadastre composições, visualize gráficos por linha e organize sua equipe em um único lugar.' desaparece quando o trem passa no fundo, coloca algo para que nao desapareça"`

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
