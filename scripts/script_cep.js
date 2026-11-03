function exibirFeedbackCep(mensagem, tipo) {
    var box = document.getElementById('cep-feedback');
    if (!box) {
        if (tipo === 'erro' && mensagem) {
            alert(mensagem);
        }
        return;
    }

    if (!mensagem) {
        box.textContent = '';
        box.className = 'ops-help d-none';
        return;
    }

    var icone = 'bi-info-circle';
    var corClasse = 'text-cyan';
    if (tipo === 'erro') {
        icone = 'bi-exclamation-triangle-fill';
        corClasse = 'text-danger';
    } else if (tipo === 'sucesso') {
        icone = 'bi-check-circle-fill';
        corClasse = 'text-volt';
    }

    box.className = 'ops-help d-flex align-items-center gap-1 mt-1 ' + corClasse;
    box.innerHTML = '<i class="bi ' + icone + '" aria-hidden="true"></i><span></span>';
    var span = box.querySelector('span');
    if (span) {
        span.textContent = mensagem;
    }
}

function limpa_formulário_cep() {
    // Limpa valores do formulário de cep.
    var rua = document.getElementById('rua');
    var bairro = document.getElementById('bairro');
    var cidade = document.getElementById('cidade');
    var uf = document.getElementById('uf');
    if (rua) rua.value = "";
    if (bairro) bairro.value = "";
    if (cidade) cidade.value = "";
    if (uf) uf.value = "";
}

function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        // Atualiza os campos com os valores.
        document.getElementById('rua').value = (conteudo.logradouro || "");
        document.getElementById('bairro').value = (conteudo.bairro || "");
        document.getElementById('cidade').value = (conteudo.localidade || "");
        document.getElementById('uf').value = (conteudo.uf || "");
        exibirFeedbackCep("Endereço localizado automaticamente via ViaCEP.", "sucesso");
    } else {
        // CEP não Encontrado.
        limpa_formulário_cep();
        exibirFeedbackCep("CEP não encontrado na base dos Correios. Verifique os 8 dígitos.", "erro");
    }
}

function pesquisacep(valor) {
    // Nova variável "cep" somente com dígitos.
    var cep = String(valor || "").replace(/\D/g, '');

    // Verifica se campo cep possui valor informado.
    if (cep !== "") {
        // Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        // Valida o formato do CEP.
        if (validacep.test(cep)) {
            // Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('rua').value = "...";
            document.getElementById('bairro').value = "...";
            document.getElementById('cidade').value = "...";
            document.getElementById('uf').value = "...";
            exibirFeedbackCep("Consultando CEP nos Correios...", "info");

            // Cria um elemento javascript.
            var script = document.createElement('script');

            // Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
            script.onerror = function () {
                limpa_formulário_cep();
                exibirFeedbackCep("Não foi possível consultar o ViaCEP agora. Preencha o endereço manualmente.", "erro");
            };

            // Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);
        } else {
            // cep é inválido.
            limpa_formulário_cep();
            exibirFeedbackCep("Formato de CEP inválido. Digite exatamente 8 números.", "erro");
        }
    } else {
        // cep sem valor, limpa formulário.
        exibirFeedbackCep("", "");
    }
}
