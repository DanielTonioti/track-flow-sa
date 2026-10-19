## O que é uma API e qual é sua finalidade;
API(Application Programming Interfaces) são essenciais para a maioria das aplicações modernas e funcionamento da internet. Basicamente a API é um conjunto de regras e definições que permitem que diferentes sistemas se comuniquem. Existem muitos tipos de APIs: APIs de hardware e firmware, APIs de SO, APIs de bibliotecas e frameworks, APIs da web e outras, e em alto nível, todas elas operam da mesma forma: definem um contato entre dois softwares, especificando o que pode ser solicitado e retornado. Ex.: A API é como um garçom que leva seu pedido que está no cardápio(serviço disponível) para a cozinha(servidor), a cozinha retorna a comida e o garçom a leva para você.
## Como funciona a comunicação entre sistemas por meio de APIs;
O cliente faz uma requisição, a API processa a solicitação(enviando a solicitação ao servidor com os dados necessários), o servidor responde e a api devolve o resultado ao usuário. As APIs são, por vezes, entendidas como contratos e a sua documentação representa o acordo estabelecido entre as partes: se uma parte enviar uma solicitação remota em um formato específico, o software da outra parte responderá no mesmo formato. Geralmente as API’s respondem em HTTP ou JSON
## O que são APIs REST:
A API RESTful é uma interface onde dois sistemas usam para trocar informações de forma segura pela internet. Em sua maioria das aplicações de negócios precisa se comunicar com outras aplicações internas e de terceiros para executar várias tarefas. As APIs RESTful suportam essa troca de informações porque seguem padrões de comunicação de software seguros, confiáveis e eficientes. Por exemplo, para gerar contracheques mensais, seu sistema interno de contas precisa compartilhar dados com o sistema bancário de seu cliente a fim de automatizar o faturamento e se comunicar com uma aplicação interna de planilha de horas. 
## O papel dos métodos HTTP, como GET, POST, PUT, PATCH e DELETE:
Os métodos HTTP definem as ações entre cliente e servidor via URL, estruturando as APIs RESTful. O GET lê dados de forma segura e independente, mantendo o estado do servidor intacto. O POST envia dados para criar novos recursos, não sendo constante caso a requisição seja repetida. Para atualizações, o PUT substitui um recurso completamente, enquanto o PATCH realiza apenas modificações parciais em campos específicos para otimizar o tráfego. Por fim, o DELETE remove recursos de maneira invariante. Juntos, esses verbos padronizam a manipulação de dados na web. 
## O que são requisições e respostas HTTP:
Requisições e respostas HTTP são as mensagens trocadas entre clientes e servidores para viabilizar a comunicação na web. A requisição é a solicitação enviada pelo cliente, composta por uma linha inicial contendo o método (como GET ou POST) e a URL, cabeçalhos com metadados e um corpo opcional para o envio de dados. Em contrapartida, a resposta é o retorno do servidor após o processamento desta solicitação, estruturada por uma linha de status com um código numérico (como 200 ou 404) que indica o sucesso ou falha da ação. Além disso, a resposta também inclui cabeçalhos informativos e um corpo opcional contendo a carga útil solicitada, como o código HTML de uma página ou dados em JSON. Esse ciclo contínuo de solicitação e entrega é o que sustenta toda a navegação e a troca de informações na internet.
## O que são endpoints:
O endpoint (ponto de extremidade) refere-se à ponta de um canal de comunicação. Um endpoint é um endereço virtual (uma URL específica) onde um recurso digital pode ser acessado. É exatamente para esses endereços que as requisições HTTP são enviadas pelo cliente com o objetivo de ler, criar, atualizar ou deletar dados no servidor.

## Como funciona a troca de dados no formato JSON:
o cliente envia um arquivo JSON para o sistema
o sistema recebe o arquivo faz um novo arquivo JSON e manda para o cliente
Diferenças entre uma API e um sistema web tradicional;

## A diferença entre API e o sistema web é a questão da função principal o sistema web quer entregar uma página completa com frontend e backend enquanto a API traz os dados e funções que são pedidas pelo cliente normalmente
Como uma aplicação PHP pode consumir uma API;
Vou usar o viacep.com.br como API ele informa o seu cep com seus dados sendo cep,logradouro,complemento e quando o usuário apertar em buscar cep fazendo um pedido para o sistema isso é uma API(o viacep é um web service que é uma API)
## Como uma API pode disponibilizar informações para outros sistemas;
Essa comunicação geralmente ocorre por meio de requisições HTTP realizadas para URLs específicas, chamadas de endpoints. Quando um sistema faz uma solicitação, a API pode retornar os dados em formato JSON, que organiza as informações em pares de chave e valor, facilitando sua leitura e seu processamento por outras aplicações.

## Importância da autenticação, autorização e segurança no consumo de APIs;

A autenticação de API é o processo de verificação da identidade de uma aplicação do cliente que faz uma solicitação de API, geralmente validando as credenciais do cliente como token.
A autorização de API é o processo de verificação de autorização de uma aplicação do cliente que faz uma solicitação de API,geralmente validando se o cliente tem autorização necessária para a ação.
Exemplos de utilização de APIs em sistemas empresariais.
API de pagamento:O cliente a forma de pagamento,O sistema envia a solicitação,API transmite os dados,operação é processada
API de gerenciamento de estoque:cliente faz uma venda,Sistema informa a solicitação,API transmite o dado,Sistema reduz o estoque tem tempo-real
API de controle de usuários:Usuário faz uma alteração,O sistema envia a alteração e o Usuário,API transmite o dado,Sistema registra a alteração junto com o usuário.
## FONTES:

#### ALURA. O que é HTTP? Conheça os conceitos básicos do protocolo. Artigos Alura, [s. d.]. Disponível em: https://www.alura.com.br/artigos/http. Acesso em: 19 out. 2026.
#### AMAZON WEB SERVICES (AWS). O que é API? AWS, [s. d.]. Disponível em: https://aws.amazon.com/pt/what-is/api/. Acesso em: 19 out. 2026.
#### AMAZON WEB SERVICES (AWS). O que é uma API RESTful? AWS, [s. d.]. Disponível em: https://aws.amazon.com/pt/what-is/restful-api/. Acesso em: 19 out. 2026.
#### API7.AI. HTTP Methods in APIs. Learning Center, [s. d.]. Disponível em: https://api7.ai/learning-center/api-101/http-methods-in-apis. Acesso em: 19 out. 2026.
#### APIDOG. Métodos HTTP: O Guia Definitivo com Exemplos Práticos. Blog Apidog, [s. d.]. Disponível em: https://apidog.com/pt/blog/http-methods/. Acesso em: 19 out. 2026.
#### ASUS. O que é API e qual sua importância no mundo corporativo? Blog ASUS Business, [s. d.]. Disponível em: https://www.asus.com/br/business/blog/o-que-e-api/. Acesso em: 19 out. 2026.
#### CLOUDFLARE. O que é o protocolo de transferência de hipertexto (HTTP)? Glossário Cloudflare, [s. d.]. Disponível em: https://www.cloudflare.com/pt-br/learning/ddos/glossary/hypertext-transfer-protocol-http/. Acesso em: 19 out. 2026.
#### CLOUDFLARE. O que é um endpoint? Glossário Cloudflare, [s. d.]. Disponível em: https://www.cloudflare.com/pt-br/learning/security/glossary/what-is-endpoint/. Acesso em: 19 out. 2026.
#### DEVMEDIA. HTTP: Requisição e resposta. DevMedia, [s. d.]. Disponível em: https://www.devmedia.com.br/http-requisicao-e-resposta/41217. Acesso em: 19 out. 2026.
#### ESCOLA BRITÂNICA DE ARTES CRIATIVAS E TECNOLOGIA (EBAC). O que é uma API: para que serve e como utilizar. Blog EBAC, 14 set. 2023. Disponível em: https://ebaconline.com.br/blog/o-que-e-uma-api-seo. Acesso em: 19 out. 2026.
#### FOCUS NFE. O que é API e como funciona no dia a dia? Blog Focus NFe, 5 jun. 2026. Disponível em: https://focusnfe.com.br/blog/o-que-e-uma-api/. Acesso em: 19 out. 2026.
#### FOCUS NFE. O que é API REST? Entenda as características da arquitetura. Blog Focus NFe, [s. d.]. Disponível em: https://focusnfe.com.br/blog/o-que-e-api-rest/. Acesso em: 19 out. 2026.
#### GEEKHUNTER. O que é API: como funciona e porque é essencial. Blog GeekHunter, 28 abr. 2025. Disponível em: https://www.geekhunter.com/pt/blog/api-o-que-e/. Acesso em: 19 out. 2026.
#### IBM. O que é uma API? IBM Think Topics, [s. d.]. Disponível em: https://www.ibm.com/br-pt/think/topics/api. Acesso em: 19 out. 2026.
#### IBM. O que são APIs REST? IBM Think Topics, [s. d.]. Disponível em: https://www.ibm.com/br-pt/think/topics/rest-apis. Acesso em: 19 out. 2026.
#### MDN WEB DOCS. Métodos de requisição HTTP. Mozilla Developer Network (MDN), [s. d.]. Disponível em: https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Reference/Methods. Acesso em: 19 out. 2026.
#### MOESIF. API Methods: Complete Guide to HTTP Verbs. Moesif Blog, [s. d.]. Disponível em: https://www.moesif.com/blog/technical/api-methods/. Acesso em: 19 out. 2026.
#### POSTMAN. What Are HTTP Methods? Postman Blog, [s. d.]. Disponível em: https://blog.postman.com/what-are-http-methods/. Acesso em: 19 out. 2026.
#### RED HAT. O que são interfaces de programação de aplicação (APIs)? Red Hat Topics, [s. d.]. Disponível em: https://www.redhat.com/pt-br/topics/api/what-are-application-programming-interfaces. Acesso em: 19 out. 2026.
#### ROCKETSEAT. O que é REST API e suas principais vantagens. Blog Rocketseat, 4 nov. 2024. Disponível em: https://www.rocketseat.com.br/blog/artigos/post/rest-api-desvendando-o-poder-das-aplicacoes-web. Acesso em: 19 out. 2026.
#### SOPHOS. What is endpoint security? Cybersecurity Explained, [s. d.]. Disponível em: https://www.sophos.com/pt-br/cybersecurity-explained/what-is-endpoint-security. Acesso em: 19 out. 2026.
#### TECNOBLOG. O que é um endpoint em TI? Tecnoblog Responde, [s. d.]. Disponível em: https://tecnoblog.net/responde/o-que-e-um-endpoint-em-ti/. Acesso em: 19 out. 2026.
#### KONG. Understand the differences: API authentication vs API authorization. Kong, 8 jun. 2023. Disponível em: https://konghq.com/blog/engineering/api-authentication-vs-api-authorization. Acesso em: 19 out. 2026.
#### QUALITOR. 5 exemplos práticos de uso de API em ambientes corporativos. Qualitor, 30 out. 2025. Disponível em: https://www.qualitor.com.br/5-exemplos-praticos-de-uso-de-api-em-ambientes-corporativos. Acesso em: 19 out. 2026.


