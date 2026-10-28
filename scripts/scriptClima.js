/**
 * scripts/scriptClima.js
 * Monitoramento Meteorológico em Tempo Real - Ferrorama (TrackFlow)
 * Consome a API pública Open-Meteo para Joinville / SC
 * Renderiza estritamente: Temperatura atual, Sensação térmica, Umidade relativa,
 * Ícone da condição e Localização.
 */

(function () {
    "use strict";

    const CONFIG_CLIMA = {
        latitude: -26.3045,
        longitude: -48.8487,
        localizacao: "Joinville / SC",
        timeoutMs: 10000
    };

    /**
     * Decodifica o código WMO em ícone visual e descrição amigável
     */
    function decodificarWMO(codigo) {
        const code = Number(codigo);

        const mapaWMO = {
            0:  { texto: "Céu Limpo", icone: "☀️", severo: false },
            1:  { texto: "Predominantemente Limpo", icone: "🌤️", severo: false },
            2:  { texto: "Parcialmente Nublado", icone: "⛅", severo: false },
            3:  { texto: "Nublado", icone: "☁️", severo: false },
            45: { texto: "Nevoeiro", icone: "🌫️", severo: false },
            48: { texto: "Nevoeiro com Geada", icone: "🌫️", severo: false },
            51: { texto: "Garoa Leve", icone: "🌦️", severo: false },
            53: { texto: "Garoa Moderada", icone: "🌦️", severo: false },
            55: { texto: "Garoa Densa", icone: "🌧️", severo: false },
            56: { texto: "Garoa Congelante", icone: "🌧️", severo: false },
            57: { texto: "Garoa Congelante", icone: "🌧️", severo: false },
            61: { texto: "Chuva Leve", icone: "🌧️", severo: false },
            63: { texto: "Chuva Moderada", icone: "🌧️", severo: false },
            65: { texto: "Chuva Forte", icone: "🌧️", severo: false },
            66: { texto: "Chuva Congelante", icone: "🌧️", severo: false },
            67: { texto: "Chuva Congelante", icone: "🌧️", severo: false },
            71: { texto: "Neve Leve", icone: "❄️", severo: false },
            73: { texto: "Neve Moderada", icone: "❄️", severo: false },
            75: { texto: "Neve Forte", icone: "❄️", severo: false },
            77: { texto: "Grãos de Neve", icone: "❄️", severo: false },
            80: { texto: "Pancadas Leves", icone: "🌦️", severo: false },
            81: { texto: "Pancadas de Chuva", icone: "🌧️", severo: false },
            82: { texto: "Pancadas Fortes", icone: "⛈️", severo: false },
            85: { texto: "Pancadas de Neve", icone: "❄️", severo: false },
            86: { texto: "Neve Intensa", icone: "❄️", severo: false },
            95: { texto: "Tempestade", icone: "⛈️", severo: true },
            96: { texto: "Tempestade com Granizo", icone: "⛈️", severo: true },
            99: { texto: "Tempestade Severa", icone: "⛈️", severo: true }
        };

        if (Object.prototype.hasOwnProperty.call(mapaWMO, code)) {
            return mapaWMO[code];
        }

        if (code >= 50 && code <= 69) {
            return { texto: "Chuva", icone: "🌧️", severo: false };
        }
        if (code >= 70 && code <= 79) {
            return { texto: "Neve", icone: "❄️", severo: false };
        }
        if (code >= 80 && code <= 89) {
            return { texto: "Pancadas de Chuva", icone: "🌧️", severo: false };
        }
        if (code >= 90 && code <= 99) {
            return { texto: "Tempestade", icone: "⛈️", severo: true };
        }

        return { texto: "Tempo Estável", icone: "⛅", severo: false };
    }

    /**
     * Formata horário atual para HH:mm:ss
     */
    function formatarHora(data) {
        const d = data instanceof Date ? data : new Date();
        const h = String(d.getHours()).padStart(2, "0");
        const m = String(d.getMinutes()).padStart(2, "0");
        const s = String(d.getSeconds()).padStart(2, "0");
        return `${h}:${m}:${s}`;
    }

    /**
     * Consulta a API Open-Meteo e renderiza os dados na interface
     */
    async function consultarClima() {
        const widget = document.getElementById("widget-clima-cco");
        const btn = document.getElementById("btn-atualizar-clima");
        const spinner = document.getElementById("clima-spinner");
        const btnIcon = document.getElementById("clima-btn-icon");

        const elTemp = document.getElementById("clima-temperatura");
        const elSensacao = document.getElementById("clima-sensacao");
        const elUmidade = document.getElementById("clima-umidade");
        const elIcone = document.getElementById("clima-icone");
        const elCondicao = document.getElementById("clima-condicao");
        const elLocalizacao = document.getElementById("clima-localizacao");
        const elAtualizacao = document.getElementById("clima-atualizacao");
        const elErroBox = document.getElementById("clima-erro-box");
        const elErroMsg = document.getElementById("clima-erro-mensagem");
        const elAlertaExtremo = document.getElementById("clima-alerta-extremo");

        if (!widget) return;

        const lat = parseFloat(widget.getAttribute("data-lat")) || CONFIG_CLIMA.latitude;
        const lon = parseFloat(widget.getAttribute("data-lon")) || CONFIG_CLIMA.longitude;
        const localNome = widget.getAttribute("data-local") || CONFIG_CLIMA.localizacao;

        // Estado visual de carregamento discreto
        if (btn) btn.disabled = true;
        if (spinner) spinner.classList.remove("d-none");
        if (btnIcon) btnIcon.classList.add("d-none");
        if (elErroBox) elErroBox.classList.add("d-none");

        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), CONFIG_CLIMA.timeoutMs);

        const params = new URLSearchParams({
            latitude: String(lat),
            longitude: String(lon),
            current: "temperature_2m,relative_humidity_2m,apparent_temperature,weather_code",
            timezone: "America/Sao_Paulo"
        });

        const url = `https://api.open-meteo.com/v1/forecast?${params.toString()}`;

        try {
            const resposta = await fetch(url, {
                method: "GET",
                headers: { "Accept": "application/json" },
                signal: controller.signal,
                cache: "no-store"
            });

            clearTimeout(timeoutId);

            if (resposta.status === 429) {
                const err = new Error("Limite de consultas temporariamente atingido. Tente mais tarde.");
                err.status = 429;
                throw err;
            }

            if (!resposta.ok) {
                throw new Error(`Falha de comunicação com Open-Meteo (HTTP ${resposta.status}).`);
            }

            const dados = await resposta.json();
            const atual = dados.current || dados.current_weather;

            if (!atual) {
                throw new Error("Dados meteorológicos não disponíveis no momento.");
            }

            const temp = Number(atual.temperature_2m ?? atual.temperature);
            const sensacao = Number(atual.apparent_temperature ?? temp);
            const umidade = atual.relative_humidity_2m ?? "--";
            const codeWMO = Number(atual.weather_code ?? atual.weathercode ?? 0);

            const info = decodificarWMO(codeWMO);

            if (elLocalizacao) {
                elLocalizacao.textContent = localNome;
            }
            if (elTemp) {
                elTemp.textContent = Number.isFinite(temp) ? `${temp.toFixed(1)} °C` : "-- °C";
            }
            if (elSensacao) {
                elSensacao.textContent = Number.isFinite(sensacao) ? `${sensacao.toFixed(1)} °C` : "-- °C";
            }
            if (elUmidade) {
                elUmidade.textContent = `${umidade}%`;
            }
            if (elIcone) {
                elIcone.textContent = info.icone;
            }
            if (elCondicao) {
                elCondicao.textContent = info.texto;
            }
            if (elAtualizacao) {
                elAtualizacao.textContent = `Atualizado às ${formatarHora(new Date())}`;
            }

            // Exibe alerta meteorológico SOMENTE em caso de tempestades severas extremas (WMO 95, 96, 99)
            if (elAlertaExtremo) {
                if (info.severo) {
                    elAlertaExtremo.classList.remove("d-none");
                } else {
                    elAlertaExtremo.classList.add("d-none");
                }
            }
        } catch (erro) {
            clearTimeout(timeoutId);

            let msg = "Não foi possível carregar o clima agora.";
            if (erro && erro.status === 429) {
                msg = "Limite de consultas temporariamente atingido. Tente mais tarde.";
            } else if (erro && erro.name === "AbortError") {
                msg = "Tempo limite de conexão esgotado.";
            } else if (erro && erro.message) {
                msg = erro.message;
            }

            if (elErroBox && elErroMsg) {
                elErroMsg.textContent = msg;
                elErroBox.classList.remove("d-none");
            }
        } finally {
            if (btn) btn.disabled = false;
            if (spinner) spinner.classList.add("d-none");
            if (btnIcon) btnIcon.classList.remove("d-none");
        }
    }

    // Inicialização ao carregar o DOM
    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.getElementById("btn-atualizar-clima");
        if (btn) {
            btn.addEventListener("click", consultarClima);
        }
        consultarClima();
    });
})();
