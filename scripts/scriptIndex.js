

document.addEventListener("DOMContentLoaded", () => {
    init3DBackground();
    initNavbarAndScrollProgress();
    initShowcaseTabs();
    initScrollReveal();
    initTelemetrySimulator();
    initSensorInspector();
});

/**
 * 1. Barra Superior e ScrollSpy
 */
function initNavbarAndScrollProgress() {
    const navbar = document.querySelector(".navbar-canva");
    const navLinks = document.querySelectorAll(".nav-link-canva");
    const sections = document.querySelectorAll("main section[id]");
    const navbarCollapse = document.getElementById("navbarTrackflow");

    const updateOnScroll = () => {
        const scrollTop = window.scrollY;

        if (navbar) {
            navbar.classList.toggle("navbar-scrolled", scrollTop > 24);
        }

        let currentSectionId = "inicio";
        sections.forEach((section) => {
            const sectionTop = section.offsetTop - 160;
            if (scrollTop >= sectionTop) {
                currentSectionId = section.getAttribute("id");
            }
        });

        navLinks.forEach((link) => {
            const href = link.getAttribute("href");
            link.classList.toggle("active", href === `#${currentSectionId}`);
        });
    };

    navLinks.forEach((link) => {
        link.addEventListener("click", () => {
            if (navbarCollapse && navbarCollapse.classList.contains("show") && typeof bootstrap !== "undefined") {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
    });

    window.addEventListener("scroll", updateOnScroll, { passive: true });
    updateOnScroll();
}

/**
 * 1b. Pílulas de Categoria na Hero (Vitrine Estilo Canva)
 */
function initShowcaseTabs() {
    const tabs = document.querySelectorAll("[data-showcase-tab]");
    const stageCard = document.getElementById("canva-showcase-card");
    const kicker = document.getElementById("showcase-kicker");
    const title = document.getElementById("showcase-title");
    const desc = document.getElementById("showcase-desc");
    const tag1 = document.getElementById("showcase-tag-1");
    const tag2 = document.getElementById("showcase-tag-2");

    if (!tabs.length || !title) return;

    const showcaseContent = {
        sensores: {
            kicker: "Painel de Sensores",
            title: "Trens e trilhos monitorados lado a lado",
            desc: "Visualize rapidamente o código do sensor, a velocidade da composição, a temperatura em graus Celsius e o status de funcionamento sem telas complicadas.",
            tag1: "Leitura térmica contínua",
            tag2: "Status imediato"
        },
        frota: {
            kicker: "Trens e Linhas",
            title: "Frota organizada por código e linha",
            desc: "Cadastre novas composições, associe cada trem ao seu corredor ferroviário e consulte toda a lista de veículos ativos em poucos segundos.",
            tag1: "Cadastro simplificado",
            tag2: "Vínculo por linha"
        },
        relatorios: {
            kicker: "Relatórios e Gráficos",
            title: "Desempenho das linhas fácil de acompanhar",
            desc: "Compare a velocidade média das linhas em gráficos no Hub e mantenha o histórico de ocorrências organizado por horário.",
            tag1: "Gráficos no Hub",
            tag2: "Histórico por horário"
        },
        equipe: {
            kicker: "Equipe e Acessos",
            title: "Permissões claras para cada função",
            desc: "Administradores gerenciam o quadro de usuários e configurações, enquanto funcionários consultam tabelas, sensores e relatórios com rapidez.",
            tag1: "Perfis de acesso",
            tag2: "Consulta segura"
        }
    };

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            const key = tab.getAttribute("data-showcase-tab");
            const data = showcaseContent[key];
            if (!data) return;

            tabs.forEach((t) => {
                const active = t === tab;
                t.classList.toggle("active", active);
                t.setAttribute("aria-selected", active ? "true" : "false");
            });

            if (stageCard) stageCard.style.opacity = "0.55";
            setTimeout(() => {
                if (kicker) kicker.textContent = data.kicker;
                title.textContent = data.title;
                if (desc) desc.textContent = data.desc;
                if (tag1) tag1.textContent = data.tag1;
                if (tag2) tag2.textContent = data.tag2;
                if (stageCard) stageCard.style.opacity = "1";
            }, 120);
        });
    });
}
/**
 * 2. Revelação suave de elementos durante a rolagem (IntersectionObserver)
 */
function initScrollReveal() {
    const revealElements = document.querySelectorAll(".reveal-on-scroll");

    if (!("IntersectionObserver" in window)) {
        revealElements.forEach((el) => el.classList.add("is-visible"));
        return;
    }

    document.body.classList.add("js-ready");

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    obs.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.08, rootMargin: "0px 0px -20px 0px" }
    );

    revealElements.forEach((el) => observer.observe(el));
}

/**
 * 3. Cena 3D WebGL (Three.js):
 *    Corredor Ferroviário Duplo em Viaduto Arquitetônico,
 *    Trem Expresso Aerodinâmico Prata/Ciano e Composição de Carga TrackFlow
 */
function init3DBackground() {
    const canvas = document.getElementById("webgl-trains-canvas");
    if (!canvas || typeof THREE === "undefined") {
        return;
    }

    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    const scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0x060f1e, 0.0085);

    const camera = new THREE.PerspectiveCamera(
        38,
        window.innerWidth / window.innerHeight,
        0.5,
        400
    );

    let renderer;
    try {
        renderer = new THREE.WebGLRenderer({
            canvas: canvas,
            alpha: true,
            antialias: true,
            powerPreference: "high-performance"
        });
    } catch (e) {
        console.warn("WebGL não disponível:", e);
        return;
    }

    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(window.innerWidth, window.innerHeight, false);
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.2;

    // Ambiente de iluminação procedural para reflexos metálicos
    try {
        const pmremGenerator = new THREE.PMREMGenerator(renderer);
        const envScene = new THREE.Scene();
        envScene.background = new THREE.Color(0x0b192c);

        const skyPanel = new THREE.Mesh(
            new THREE.BoxGeometry(100, 2, 100),
            new THREE.MeshBasicMaterial({ color: 0xbae6fd })
        );
        skyPanel.position.set(0, 40, 0);
        envScene.add(skyPanel);

        const sidePanel1 = new THREE.Mesh(
            new THREE.BoxGeometry(80, 25, 2),
            new THREE.MeshBasicMaterial({ color: 0x00d4ff })
        );
        sidePanel1.position.set(-35, 12, 35);
        envScene.add(sidePanel1);

        const sidePanel2 = new THREE.Mesh(
            new THREE.BoxGeometry(80, 25, 2),
            new THREE.MeshBasicMaterial({ color: 0x38bdf8 })
        );
        sidePanel2.position.set(35, 12, -35);
        envScene.add(sidePanel2);

        scene.environment = pmremGenerator.fromScene(envScene, 0.04).texture;
        pmremGenerator.dispose();
    } catch (_) {
        // Fallback seguro
    }

    // Iluminação Cênica Harmônica
    const hemiLight = new THREE.HemisphereLight(0xdbeafe, 0x091526, 1.65);
    scene.add(hemiLight);

    const keyLight = new THREE.DirectionalLight(0xf8fafc, 2.3);
    keyLight.position.set(30, 55, 40);
    scene.add(keyLight);

    const fillLightCyan = new THREE.DirectionalLight(0x00d4ff, 1.25);
    fillLightCyan.position.set(-45, 25, 20);
    scene.add(fillLightCyan);

    const backLight = new THREE.DirectionalLight(0x7dd3fc, 0.9);
    backLight.position.set(0, 30, -45);
    scene.add(backLight);

    // CORREDORES FERROVIÁRIOS EM CURVA SUAVE HORIZONTAL (VISÃO LATERAL 3/4)
    // Via 1 (Primeiro Plano — Y = -1.2): Curva suave da esquerda para a direita
    const pointsLine1 = [
        new THREE.Vector3(-120, -1.2, -16),
        new THREE.Vector3(-75, -1.2, -11),
        new THREE.Vector3(-30, -1.2, -5.5),
        new THREE.Vector3(15, -1.2, -4.5),
        new THREE.Vector3(60, -1.2, -8.5),
        new THREE.Vector3(105, -1.2, -15),
        new THREE.Vector3(135, -1.2, -22)
    ];
    const curveLine1 = new THREE.CatmullRomCurve3(pointsLine1, false, "centripetal");

    // Via 2 (Segundo Plano Elevado em Viaduto — Y = 2.6): Curva paralela superior
    const pointsLine2 = [
        new THREE.Vector3(-125, 2.6, -26),
        new THREE.Vector3(-75, 2.6, -20),
        new THREE.Vector3(-25, 2.6, -15),
        new THREE.Vector3(25, 2.6, -14),
        new THREE.Vector3(75, 2.6, -18),
        new THREE.Vector3(120, 2.6, -25),
        new THREE.Vector3(145, 2.6, -32)
    ];
    const curveLine2 = new THREE.CatmullRomCurve3(pointsLine2, false, "centripetal");

    const rotatingAxles = [];
    const pulsingBeacons = [];

    // Constrói Viaduto Arquitetônico, Dormentes, Trilhos de Aço e Pórticos de Catenária
    function buildViaductCorridor(curve, options) {
        const {
            tiesCount = 190,
            railGauge = 0.68,
            pierHeight = 14,
            pierCount = 14,
            hasCatenary = true
        } = options;

        const corridorGroup = new THREE.Group();
        const samples = 200;
        const ptsLeftRail = [];
        const ptsRightRail = [];
        const ptsDeck = [];
        const ptsLeftParapet = [];
        const ptsRightParapet = [];

        for (let i = 0; i <= samples; i++) {
            const u = i / samples;
            const pt = curve.getPointAt(u);
            const tan = curve.getTangentAt(u).normalize();
            const normal = new THREE.Vector3(-tan.z, 0, tan.x).normalize();

            ptsLeftRail.push(pt.clone().addScaledVector(normal, -railGauge));
            ptsRightRail.push(pt.clone().addScaledVector(normal, railGauge));
            ptsDeck.push(pt.clone().add(new THREE.Vector3(0, -0.32, 0)));
            ptsLeftParapet.push(pt.clone().addScaledVector(normal, -1.45).add(new THREE.Vector3(0, 0.05, 0)));
            ptsRightParapet.push(pt.clone().addScaledVector(normal, 1.45).add(new THREE.Vector3(0, 0.05, 0)));
        }

        // Tabuleiro do Viaduto (Deck de Concreto Arquitetônico)
        const deckMat = new THREE.MeshStandardMaterial({
            color: 0x13263e,
            metalness: 0.35,
            roughness: 0.7
        });
        const deckCurve = new THREE.CatmullRomCurve3(ptsDeck, false);
        const deckGeo = new THREE.TubeGeometry(deckCurve, 160, 1.55, 4, false);
        deckGeo.scale(1, 0.26, 1);
        corridorGroup.add(new THREE.Mesh(deckGeo, deckMat));

        // Guardas Laterais do Viaduto com Friso Luminoso Ciano
        const parapetMat = new THREE.MeshStandardMaterial({
            color: 0x1e3a5f,
            metalness: 0.45,
            roughness: 0.5
        });
        const leftParapetCurve = new THREE.CatmullRomCurve3(ptsLeftParapet, false);
        const rightParapetCurve = new THREE.CatmullRomCurve3(ptsRightParapet, false);
        corridorGroup.add(
            new THREE.Mesh(new THREE.TubeGeometry(leftParapetCurve, 140, 0.09, 4, false), parapetMat),
            new THREE.Mesh(new THREE.TubeGeometry(rightParapetCurve, 140, 0.09, 4, false), parapetMat)
        );

        // Trilhos de Aço Polido
        const railMat = new THREE.MeshStandardMaterial({
            color: 0xe2e8f0,
            metalness: 0.92,
            roughness: 0.16
        });
        const leftRailCurve = new THREE.CatmullRomCurve3(ptsLeftRail, false);
        const rightRailCurve = new THREE.CatmullRomCurve3(ptsRightRail, false);
        corridorGroup.add(
            new THREE.Mesh(new THREE.TubeGeometry(leftRailCurve, 180, 0.065, 6, false), railMat),
            new THREE.Mesh(new THREE.TubeGeometry(rightRailCurve, 180, 0.065, 6, false), railMat)
        );

        // Dormentes de Concreto Monobloco
        const tieGeo = new THREE.BoxGeometry(railGauge * 2.8, 0.12, 0.28);
        const tieMat = new THREE.MeshStandardMaterial({
            color: 0x263c57,
            metalness: 0.3,
            roughness: 0.75
        });
        const tiesInstanced = new THREE.InstancedMesh(tieGeo, tieMat, tiesCount);
        const dummy = new THREE.Object3D();

        for (let j = 0; j < tiesCount; j++) {
            const u = j / (tiesCount - 1);
            const pt = curve.getPointAt(u);
            const tan = curve.getTangentAt(u).normalize();

            dummy.position.set(pt.x, pt.y - 0.07, pt.z);
            dummy.lookAt(pt.x + tan.x, pt.y - 0.07, pt.z + tan.z);
            dummy.updateMatrix();
            tiesInstanced.setMatrixAt(j, dummy.matrix);
        }
        tiesInstanced.instanceMatrix.needsUpdate = true;
        corridorGroup.add(tiesInstanced);

        // Pilares do Viaduto e Postes de Catenária
        const pierMat = new THREE.MeshStandardMaterial({
            color: 0x0f2137,
            metalness: 0.4,
            roughness: 0.65
        });
        const mastMat = new THREE.MeshStandardMaterial({
            color: 0x64748b,
            metalness: 0.8,
            roughness: 0.25
        });
        const beaconMat = new THREE.MeshBasicMaterial({ color: 0x00d4ff });

        for (let p = 1; p < pierCount; p++) {
            const u = p / pierCount;
            const pt = curve.getPointAt(u);
            const tan = curve.getTangentAt(u).normalize();

            const pierGroup = new THREE.Group();
            pierGroup.position.copy(pt);
            pierGroup.lookAt(pt.clone().add(tan));

            // Viga capitel superior do pilar
            const capBeam = new THREE.Mesh(new THREE.BoxGeometry(2.7, 0.42, 1.1), parapetMat);
            capBeam.position.set(0, -0.62, 0);
            pierGroup.add(capBeam);

            // Coluna dupla arquitetônica
            const colGeo = new THREE.BoxGeometry(0.95, pierHeight, 0.85);
            const colMesh = new THREE.Mesh(colGeo, pierMat);
            colMesh.position.set(0, -pierHeight / 2 - 0.62, 0);
            pierGroup.add(colMesh);

            // Pórtico de Catenária e Baliza IoT
            if (hasCatenary) {
                const mast = new THREE.Mesh(new THREE.BoxGeometry(0.12, 3.3, 0.12), mastMat);
                mast.position.set(-1.32, 1.55, 0);
                pierGroup.add(mast);

                const cantilever = new THREE.Mesh(new THREE.BoxGeometry(1.55, 0.07, 0.07), mastMat);
                cantilever.position.set(-0.58, 3.05, 0);
                pierGroup.add(cantilever);

                if (p % 2 === 0) {
                    const beacon = new THREE.Mesh(new THREE.SphereGeometry(0.09, 10, 10), beaconMat);
                    beacon.position.set(-1.32, 3.28, 0);
                    pierGroup.add(beacon);
                    pulsingBeacons.push(beacon);
                }
            }

            corridorGroup.add(pierGroup);
        }

        scene.add(corridorGroup);
    }

    buildViaductCorridor(curveLine1, {
        tiesCount: 210,
        railGauge: 0.68,
        pierHeight: 12,
        pierCount: 12,
        hasCatenary: true
    });

    buildViaductCorridor(curveLine2, {
        tiesCount: 210,
        railGauge: 0.68,
        pierHeight: 16,
        pierCount: 14,
        hasCatenary: false
    });

    // TRUQUES FERROVIÁRIOS (BOGIES), RODAS E PANTÓGRAFOS
    const wheelMat = new THREE.MeshStandardMaterial({
        color: 0xcbd5e1,
        metalness: 0.9,
        roughness: 0.2
    });
    const bogieMat = new THREE.MeshStandardMaterial({
        color: 0x1e293b,
        metalness: 0.65,
        roughness: 0.45
    });
    const sensorLedVolt = new THREE.MeshBasicMaterial({ color: 0xc1ff72 });
    const sensorLedCyan = new THREE.MeshBasicMaterial({ color: 0x00d4ff });

    function createBogie(axleCount = 2, gauge = 0.68, ledMat = sensorLedVolt) {
        const bogie = new THREE.Group();
        const wheelRadius = 0.24;
        const frameLen = axleCount === 3 ? 2.0 : 1.35;

        const leftFrame = new THREE.Mesh(new THREE.BoxGeometry(0.12, 0.2, frameLen), bogieMat);
        leftFrame.position.set(-(gauge + 0.06), wheelRadius, 0);

        const rightFrame = new THREE.Mesh(new THREE.BoxGeometry(0.12, 0.2, frameLen), bogieMat);
        rightFrame.position.set(gauge + 0.06, wheelRadius, 0);

        const bolster = new THREE.Mesh(new THREE.BoxGeometry(gauge * 2.1, 0.18, 0.34), bogieMat);
        bolster.position.set(0, wheelRadius + 0.05, 0);

        bogie.add(leftFrame, rightFrame, bolster);

        const zOffsets = axleCount === 3 ? [-0.7, 0, 0.7] : [-0.42, 0.42];
        zOffsets.forEach((zPos, idx) => {
            const axle = new THREE.Group();
            axle.position.set(0, wheelRadius, zPos);

            const shaft = new THREE.Mesh(
                new THREE.CylinderGeometry(0.055, 0.055, gauge * 2.1, 10),
                wheelMat
            );
            shaft.rotation.z = Math.PI / 2;
            axle.add(shaft);

            [-gauge, gauge].forEach((xPos) => {
                const wheel = new THREE.Mesh(
                    new THREE.CylinderGeometry(wheelRadius, wheelRadius, 0.1, 16),
                    wheelMat
                );
                wheel.rotation.z = Math.PI / 2;
                wheel.position.x = xPos;
                axle.add(wheel);
            });

            rotatingAxles.push(axle);
            bogie.add(axle);

            if (idx === 0) {
                const sensorPod = new THREE.Mesh(new THREE.SphereGeometry(0.07, 8, 8), ledMat);
                sensorPod.position.set(gauge + 0.14, wheelRadius, zPos);
                bogie.add(sensorPod);
                pulsingBeacons.push(sensorPod);
            }
        });

        return bogie;
    }

    // TREM 1: FERRORAMA EXPRESS (BRANCO-PRATA AERODINÂMICO + FAIXA CIANO/NAVY)
    // Visual inspirado em trens de alta velocidade Siemens Velaro / Shinkansen

    const expressShellMat = new THREE.MeshStandardMaterial({
        color: 0xeff6ff, // Branco-prata gelo de alto contraste sobre o fundo noturno
        metalness: 0.35,
        roughness: 0.22
    });
    const expressRoofMat = new THREE.MeshStandardMaterial({
        color: 0x94a3b8,
        metalness: 0.6,
        roughness: 0.35
    });
    const expressSkirtMat = new THREE.MeshStandardMaterial({
        color: 0x1e293b,
        metalness: 0.5,
        roughness: 0.5
    });
    const windowBandMat = new THREE.MeshStandardMaterial({
        color: 0x091526,
        metalness: 0.9,
        roughness: 0.1
    });
    const cabinLitWindowMat = new THREE.MeshBasicMaterial({ color: 0xbae6fd });
    const cyanStripeMat = new THREE.MeshBasicMaterial({ color: 0x00d4ff });
    const headlightMat = new THREE.MeshBasicMaterial({ color: 0xffffff });
    const taillightMat = new THREE.MeshBasicMaterial({ color: 0xef4444 });

    function createStreamlinedExpressLocomotive(isLead = true) {
        const unit = new THREE.Group();
        const length = 6.4;
        const halfL = length / 2;

        // Perfil lateral aerodinâmico
        const shape = new THREE.Shape();
        if (isLead) {
            shape.moveTo(-halfL, 0.38);
            shape.lineTo(halfL - 0.25, 0.38);
            shape.quadraticCurveTo(halfL + 0.25, 0.42, halfL, 0.78);
            shape.quadraticCurveTo(halfL - 0.55, 1.35, halfL - 1.75, 1.72);
            shape.lineTo(-halfL, 1.72);
            shape.lineTo(-halfL, 0.38);
        } else {
            shape.moveTo(-halfL + 0.25, 0.38);
            shape.lineTo(halfL, 0.38);
            shape.lineTo(halfL, 1.72);
            shape.lineTo(-halfL + 1.75, 1.72);
            shape.quadraticCurveTo(-halfL + 0.55, 1.35, -halfL, 0.78);
            shape.quadraticCurveTo(-halfL - 0.25, 0.42, -halfL + 0.25, 0.38);
        }

        const rawGeo = new THREE.ExtrudeGeometry(shape, {
            steps: 1,
            depth: 1.42,
            bevelEnabled: true,
            bevelThickness: 0.14,
            bevelSize: 0.12,
            bevelSegments: 4
        });
        rawGeo.translate(0, 0, -1.42 / 2);
        rawGeo.rotateY(-Math.PI / 2);

        const shellMesh = new THREE.Mesh(rawGeo, expressShellMat);
        unit.add(shellMesh);

        // Saia inferior aerodinâmica (Underframe)
        const skirt = new THREE.Mesh(
            new THREE.BoxGeometry(1.54, 0.22, length - 0.7),
            expressSkirtMat
        );
        skirt.position.set(0, 0.32, isLead ? -0.2 : 0.2);
        unit.add(skirt);

        // Para-brisa panorâmico da cabine
        const windshield = new THREE.Mesh(
            new THREE.BoxGeometry(1.38, 0.44, 1.25),
            windowBandMat
        );
        const wsZ = isLead ? halfL - 1.15 : -halfL + 1.15;
        windshield.position.set(0, 1.38, wsZ);
        windshield.rotation.x = isLead ? 0.44 : -0.44;
        unit.add(windshield);

        // Faixa preta contínua de janelas + Janelas iluminadas
        const bandZ = isLead ? -0.55 : 0.55;
        const winBand = new THREE.Mesh(
            new THREE.BoxGeometry(1.68, 0.42, length - 2.1),
            windowBandMat
        );
        winBand.position.set(0, 1.26, bandZ);
        unit.add(winBand);

        for (let w = -1; w <= 1; w++) {
            const litWin = new THREE.Mesh(
                new THREE.BoxGeometry(1.7, 0.28, 0.75),
                cabinLitWindowMat
            );
            litWin.position.set(0, 1.26, bandZ + w * 1.1);
            unit.add(litWin);
        }

        // Faixa lateral Ciano TrackFlow
        const cyanStripe = new THREE.Mesh(
            new THREE.BoxGeometry(1.69, 0.09, length - 1.2),
            cyanStripeMat
        );
        cyanStripe.position.set(0, 0.92, isLead ? -0.35 : 0.35);
        unit.add(cyanStripe);

        // Carenagem de teto + Pantógrafo
        const roofShroud = new THREE.Mesh(
            new THREE.BoxGeometry(1.05, 0.12, 2.4),
            expressRoofMat
        );
        roofShroud.position.set(0, 1.86, isLead ? -0.8 : 0.8);
        unit.add(roofShroud);

        // Faróis de LED / Lanternas Traseiras
        const lightMat = isLead ? headlightMat : taillightMat;
        const noseTipZ = isLead ? halfL - 0.05 : -halfL + 0.05;
        [-0.44, 0.44].forEach((xPos) => {
            const lamp = new THREE.Mesh(new THREE.BoxGeometry(0.24, 0.1, 0.1), lightMat);
            lamp.position.set(xPos, 0.68, noseTipZ);
            unit.add(lamp);
        });

        if (isLead) {
            // Feixe de luz frontal suave
            const beamGeo = new THREE.ConeGeometry(1.5, 11, 16, 1, true);
            beamGeo.translate(0, -5.5, 0);
            beamGeo.rotateX(-Math.PI / 2);
            const beamMat = new THREE.MeshBasicMaterial({
                color: 0xe0f2fe,
                transparent: true,
                opacity: 0.11,
                side: THREE.DoubleSide,
                depthWrite: false
            });
            const beam = new THREE.Mesh(beamGeo, beamMat);
            beam.position.set(0, 0.68, halfL);
            unit.add(beam);
        }

        // Sanfona de conexão (Gangway)
        const gangway = new THREE.Mesh(
            new THREE.BoxGeometry(1.32, 1.28, 0.38),
            expressSkirtMat
        );
        gangway.position.set(0, 1.06, isLead ? -halfL - 0.12 : halfL + 0.12);
        unit.add(gangway);

        // Dois Truques Ferroviários
        const frontBogie = createBogie(2, 0.68, sensorLedVolt);
        frontBogie.position.set(0, 0, 1.8);
        const rearBogie = createBogie(2, 0.68, sensorLedVolt);
        rearBogie.position.set(0, 0, -1.8);
        unit.add(frontBogie, rearBogie);

        return unit;
    }

    function createStreamlinedExpressCoach() {
        const coach = new THREE.Group();
        const length = 5.8;
        const halfL = length / 2;

        const shape = new THREE.Shape();
        shape.moveTo(-halfL, 0.38);
        shape.lineTo(halfL, 0.38);
        shape.lineTo(halfL, 1.72);
        shape.lineTo(-halfL, 1.72);
        shape.lineTo(-halfL, 0.38);

        const rawGeo = new THREE.ExtrudeGeometry(shape, {
            steps: 1,
            depth: 1.42,
            bevelEnabled: true,
            bevelThickness: 0.14,
            bevelSize: 0.12,
            bevelSegments: 4
        });
        rawGeo.translate(0, 0, -1.42 / 2);
        rawGeo.rotateY(-Math.PI / 2);

        const shellMesh = new THREE.Mesh(rawGeo, expressShellMat);
        coach.add(shellMesh);

        const skirt = new THREE.Mesh(
            new THREE.BoxGeometry(1.54, 0.22, length - 0.1),
            expressSkirtMat
        );
        skirt.position.set(0, 0.32, 0);
        coach.add(skirt);

        const winBand = new THREE.Mesh(
            new THREE.BoxGeometry(1.68, 0.42, length - 0.2),
            windowBandMat
        );
        winBand.position.set(0, 1.26, 0);
        coach.add(winBand);

        // 5 Janelas panorâmicas iluminadas por vagão
        for (let w = -2; w <= 2; w++) {
            const litWin = new THREE.Mesh(
                new THREE.BoxGeometry(1.7, 0.28, 0.78),
                cabinLitWindowMat
            );
            litWin.position.set(0, 1.26, w * 1.05);
            coach.add(litWin);
        }

        const cyanStripe = new THREE.Mesh(
            new THREE.BoxGeometry(1.69, 0.09, length),
            cyanStripeMat
        );
        cyanStripe.position.set(0, 0.92, 0);
        coach.add(cyanStripe);

        const hvac = new THREE.Mesh(
            new THREE.BoxGeometry(1.0, 0.12, 1.9),
            expressRoofMat
        );
        hvac.position.set(0, 1.86, 0);
        coach.add(hvac);

        const gangway = new THREE.Mesh(
            new THREE.BoxGeometry(1.32, 1.28, 0.42),
            expressSkirtMat
        );
        gangway.position.set(0, 1.06, -halfL - 0.15);
        coach.add(gangway);

        const frontBogie = createBogie(2, 0.68, sensorLedVolt);
        frontBogie.position.set(0, 0, 1.75);
        const rearBogie = createBogie(2, 0.68, sensorLedVolt);
        rearBogie.position.set(0, 0, -1.75);
        coach.add(frontBogie, rearBogie);

        return coach;
    }

    // Monta Composição 1 (Ferrorama Express: Ponteira + 4 Vagões + Cauda Aerodinâmica)
    const expressConsist = [];
    const expressLead = createStreamlinedExpressLocomotive(true);
    scene.add(expressLead);
    expressConsist.push({ mesh: expressLead, spacing: 0 });

    for (let c = 0; c < 4; c++) {
        const coach = createStreamlinedExpressCoach();
        scene.add(coach);
        expressConsist.push({ mesh: coach, spacing: 6.2 });
    }

    const expressTail = createStreamlinedExpressLocomotive(false);
    scene.add(expressTail);
    expressConsist.push({ mesh: expressTail, spacing: 6.2 });

    // TREM 2: COMPOSIÇÃO DE CARGA TRACKFLOW 
    const cargoLocoPrimaryMat = new THREE.MeshStandardMaterial({
        color: 0x0284c7, // Azul TrackFlow vibrante e visível
        metalness: 0.45,
        roughness: 0.3
    });
    const cargoLocoSecondaryMat = new THREE.MeshStandardMaterial({
        color: 0xf1f5f9,
        metalness: 0.4,
        roughness: 0.25
    });

    function createCargoLocomotive() {
        const loco = new THREE.Group();
        const deckLen = 6.8;

        // Plataforma e trilhos
        const deck = new THREE.Mesh(
            new THREE.BoxGeometry(1.82, 0.22, deckLen),
            expressSkirtMat
        );
        deck.position.set(0, 0.52, 0);
        loco.add(deck);

        const fuelTank = new THREE.Mesh(
            new THREE.BoxGeometry(1.55, 0.34, 2.3),
            bogieMat
        );
        fuelTank.position.set(0, 0.3, -0.1);
        loco.add(fuelTank);

        // Nariz frontal e Cabine larga
        const shortHood = new THREE.Mesh(
            new THREE.BoxGeometry(1.68, 0.82, 1.1),
            cargoLocoPrimaryMat
        );
        shortHood.position.set(0, 1.04, 2.5);
        loco.add(shortHood);

        const cab = new THREE.Mesh(
            new THREE.BoxGeometry(1.78, 1.32, 1.4),
            cargoLocoSecondaryMat
        );
        cab.position.set(0, 1.29, 1.35);
        loco.add(cab);

        const cabWin = new THREE.Mesh(
            new THREE.BoxGeometry(1.6, 0.38, 0.1),
            windowBandMat
        );
        cabWin.position.set(0, 1.6, 2.04);
        loco.add(cabWin);

        // Corpo do motor diesel com faixa Volt
        const longHood = new THREE.Mesh(
            new THREE.BoxGeometry(1.38, 1.26, 3.65),
            cargoLocoPrimaryMat
        );
        longHood.position.set(0, 1.26, -1.15);
        loco.add(longHood);

        const radFlare = new THREE.Mesh(
            new THREE.BoxGeometry(1.66, 0.48, 1.5),
            cargoLocoSecondaryMat
        );
        radFlare.position.set(0, 1.64, -2.15);
        loco.add(radFlare);

        const voltBand = new THREE.Mesh(
            new THREE.BoxGeometry(1.42, 0.12, 3.66),
            sensorLedVolt
        );
        voltBand.position.set(0, 0.98, -1.15);
        loco.add(voltBand);

        // Farol frontal duplo
        const frontLamp = new THREE.Mesh(
            new THREE.BoxGeometry(0.32, 0.14, 0.1),
            headlightMat
        );
        frontLamp.position.set(0, 1.86, 2.05);
        loco.add(frontLamp);

        const bogieF = createBogie(3, 0.68, sensorLedCyan);
        bogieF.position.set(0, 0, 1.9);
        const bogieR = createBogie(3, 0.68, sensorLedCyan);
        bogieR.position.set(0, 0, -1.9);
        loco.add(bogieF, bogieR);

        return loco;
    }

    const containerColors = [0x0ea5e9, 0xf8fafc, 0x22c55e, 0x38bdf8, 0xf59e0b, 0x64748b];

    function createCargoWagon(colorHex) {
        const wagon = new THREE.Group();
        const len = 5.6;

        const flatDeck = new THREE.Mesh(
            new THREE.BoxGeometry(1.74, 0.22, len),
            expressSkirtMat
        );
        flatDeck.position.set(0, 0.5, 0);
        wagon.add(flatDeck);

        const containerMat = new THREE.MeshStandardMaterial({
            color: colorHex,
            metalness: 0.35,
            roughness: 0.38
        });

        const container = new THREE.Mesh(
            new THREE.BoxGeometry(1.58, 1.28, 4.95),
            containerMat
        );
        container.position.set(0, 1.25, 0);
        wagon.add(container);

        // Nervuras verticais do contêiner
        const ribGeo = new THREE.BoxGeometry(1.63, 1.18, 0.1);
        for (let r = -2; r <= 2; r++) {
            const rib = new THREE.Mesh(ribGeo, containerMat);
            rib.position.set(0, 1.25, r * 0.92);
            wagon.add(rib);
        }

        const bogieF = createBogie(2, 0.68, sensorLedCyan);
        bogieF.position.set(0, 0, 1.8);
        const bogieR = createBogie(2, 0.68, sensorLedCyan);
        bogieR.position.set(0, 0, -1.8);
        wagon.add(bogieF, bogieR);

        return wagon;
    }

    const cargoConsist = [];
    const cargoLoco = createCargoLocomotive();
    scene.add(cargoLoco);
    cargoConsist.push({ mesh: cargoLoco, spacing: 0 });

    for (let k = 0; k < 6; k++) {
        const wagon = createCargoWagon(containerColors[k % containerColors.length]);
        scene.add(wagon);
        cargoConsist.push({ mesh: wagon, spacing: 6.1 });
    }

    // PARTÍCULAS ATMOSFÉRICAS FLUTUANTES
    const particleCount = 70;
    const particleGeo = new THREE.BufferGeometry();
    const particlePositions = new Float32Array(particleCount * 3);

    for (let i = 0; i < particleCount; i++) {
        particlePositions[i * 3] = (Math.random() - 0.5) * 120;
        particlePositions[i * 3 + 1] = Math.random() * 24 - 2;
        particlePositions[i * 3 + 2] = -Math.random() * 45 - 2;
    }
    particleGeo.setAttribute("position", new THREE.BufferAttribute(particlePositions, 3));

    const particleMat = new THREE.PointsMaterial({
        color: 0x7dd3fc,
        size: 0.22,
        transparent: true,
        opacity: 0.45
    });
    const ambientParticles = new THREE.Points(particleGeo, particleMat);
    scene.add(ambientParticles);

    // ENQUADRAMENTO RESPONSIVO
    const lenLine1 = curveLine1.getLength();
    const lenLine2 = curveLine2.getLength();

    // Posiciona a composição ao longo do corredor aberto usando os dois truques
    function positionConsistAlongLine(consist, curve, totalLen, headU, dir = 1) {
        let dist = 0;
        const bogieSpanU = 1.75 / totalLen;

        consist.forEach((item) => {
            dist += item.spacing;
            const offsetU = (dist / totalLen) * dir;
            const uCenter = headU - offsetU;

            // Oculta suavemente carros fora dos extremos da linha para reciclar o loop
            if (uCenter < 0.03 || uCenter > 0.97) {
                item.mesh.visible = false;
                return;
            }
            item.mesh.visible = true;

            const uFront = Math.min(0.995, Math.max(0.005, uCenter + bogieSpanU * dir));
            const uRear = Math.min(0.995, Math.max(0.005, uCenter - bogieSpanU * dir));

            const ptFront = curve.getPointAt(uFront);
            const ptRear = curve.getPointAt(uRear);

            item.mesh.position.lerpVectors(ptRear, ptFront, 0.5);
            item.mesh.lookAt(ptFront);
        });
    }

    let baseCam = { x: 0, y: 5.5, z: 24 };
    let baseLook = { x: 0, y: 1.0, z: -10 };

    function updateResponsiveCamera() {
        const w = window.innerWidth;
        const h = window.innerHeight;
        camera.aspect = w / Math.max(1, h);

        if (w < 576) {
            camera.fov = 50;
            baseCam = { x: 0, y: 6.2, z: 30 };
            baseLook = { x: 0, y: 1.2, z: -12 };
        } else if (w < 992) {
            camera.fov = 44;
            baseCam = { x: 0, y: 5.8, z: 27 };
            baseLook = { x: 0, y: 1.1, z: -11 };
        } else {
            camera.fov = 38;
            baseCam = { x: 2, y: 5.2, z: 23 };
            baseLook = { x: 0, y: 1.0, z: -10 };
        }

        camera.updateProjectionMatrix();
        renderer.setSize(w, h, false);
    }

    updateResponsiveCamera();
    window.addEventListener("resize", updateResponsiveCamera, { passive: true });

    let scrollRatio = 0;

    const updateScrollRatio = () => {
        const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
        scrollRatio = maxScroll > 0 ? Math.min(1, Math.max(0, window.scrollY / maxScroll)) : 0;
    };
    window.addEventListener("scroll", updateScrollRatio, { passive: true });
    updateScrollRatio();

    const clock = new THREE.Clock();
    let isTabVisible = true;
    document.addEventListener("visibilitychange", () => {
        isTabVisible = !document.hidden;
    });

    // Posição inicial visível no centro da tela
    positionConsistAlongLine(expressConsist, curveLine1, lenLine1, 0.58, 1);
    positionConsistAlongLine(cargoConsist, curveLine2, lenLine2, 0.42, -1);

    function animate() {
        requestAnimationFrame(animate);
        if (!isTabVisible && !prefersReducedMotion) return;

        const delta = Math.min(clock.getDelta(), 0.1);
        const elapsed = clock.getElapsedTime();

        if (!prefersReducedMotion) {
            // Trem 1 (Ferrorama Express): desliza da esquerda para a direita
            const uExpress = ((0.22 + elapsed * 0.045) % 0.88) + 0.1;
            positionConsistAlongLine(expressConsist, curveLine1, lenLine1, uExpress, 1);

            // Trem 2 (Composição de Carga): desliza da direita para a esquerda no viaduto superior
            const uCargo = 0.9 - ((0.2 + elapsed * 0.032) % 0.85);
            positionConsistAlongLine(cargoConsist, curveLine2, lenLine2, uCargo, -1);

            // Giro físico das rodas
            const spin = delta * 10.0;
            for (let i = 0; i < rotatingAxles.length; i++) {
                rotatingAxles[i].rotation.x += spin;
            }

            // Pulso suave das balizas IoT
            const sScale = 1 + Math.sin(elapsed * 5.0) * 0.2;
            for (let b = 0; b < pulsingBeacons.length; b++) {
                pulsingBeacons[b].scale.setScalar(sScale);
            }

            // Leve flutuação das partículas atmosféricas
            ambientParticles.rotation.y = elapsed * 0.012;

            // Ajuste suave da câmera apenas conforme o scroll da página
            const targetX = baseCam.x;
            const targetY = baseCam.y - scrollRatio * 2.2;
            const targetZ = baseCam.z - scrollRatio * 3.5;

            camera.position.x += (targetX - camera.position.x) * 0.04;
            camera.position.y += (targetY - camera.position.y) * 0.04;
            camera.position.z += (targetZ - camera.position.z) * 0.04;

            camera.lookAt(
                baseLook.x,
                baseLook.y - scrollRatio * 1.2,
                baseLook.z
            );
        } else {
            camera.position.set(baseCam.x, baseCam.y, baseCam.z);
            camera.lookAt(baseLook.x, baseLook.y, baseLook.z);
        }

        renderer.render(scene, camera);
    }

    animate();
}

/*5. Simulador Interativo na Vitrine Principal*/
function initTelemetrySimulator() {
    const scenarioButtons = document.querySelectorAll(".scenario-btn");

    const elTemp = document.getElementById("metric-temp");
    const elVib = document.getElementById("metric-vib");
    const elSpeed = document.getElementById("metric-speed");
    const elTrainName = document.getElementById("metric-train-name");

    const barTemp = document.getElementById("bar-temp");
    const barVib = document.getElementById("bar-vib");

    const badgeCco = document.getElementById("badge-cco-status");
    const badgeTemp = document.getElementById("badge-temp-status");
    const badgeVib = document.getElementById("badge-vib-status");
    const logText = document.getElementById("telemetry-log-text");

    if (!elTemp) return;

    const scenarios = {
        normal: {
            ccoText: "Operação Normal",
            ccoClass: "canva-status-pill status-ok",
            temp: 54.2,
            tempBar: 54,
            tempBarClass: "progress-bar bg-info",
            tempBadgeText: "Funcionando",
            tempBadgeClass: "canva-status-dot status-ok",
            vib: 1.8,
            vibBar: 32,
            vibBarClass: "progress-bar bg-success",
            vibBadgeText: "Estável",
            vibBadgeClass: "canva-status-dot status-ok",
            speed: 68.5,
            trainName: "Velocidade Média — Linha 1",
            log: "Todos os sensores de trens e trilhos operando normalmente."
        },
        hotbox: {
            ccoText: "Atenção: Temperatura Alta",
            ccoClass: "canva-status-pill status-danger-pill",
            temp: 94.0,
            tempBar: 90,
            tempBarClass: "progress-bar bg-danger",
            tempBadgeText: "Aquecido",
            tempBadgeClass: "canva-status-dot status-danger-pill",
            vib: 3.2,
            vibBar: 55,
            vibBarClass: "progress-bar bg-warning",
            vibBadgeText: "Atenção",
            vibBadgeClass: "canva-status-dot status-warn",
            speed: 38.0,
            trainName: "Redução Preventiva — S.TRM.001",
            log: "Temperatura elevada detectada no sensor S.TRM.001. Velocidade reduzida para verificação."
        },
        vibration: {
            ccoText: "Alerta de Via Permanente",
            ccoClass: "canva-status-pill status-warn",
            temp: 59.5,
            tempBar: 60,
            tempBarClass: "progress-bar bg-info",
            tempBadgeText: "Funcionando",
            tempBadgeClass: "canva-status-dot status-ok",
            vib: 7.6,
            vibBar: 84,
            vibBarClass: "progress-bar bg-warning",
            vibBadgeText: "Vibração Alta",
            vibBadgeClass: "canva-status-dot status-warn",
            speed: 45.0,
            trainName: "Inspeção de Trecho — Linha 2",
            log: "Variação detectada no sensor S.TRLH.001. Recomendada verificação preventiva do trecho."
        }
    };

    let currentScenario = "normal";

    const applyScenario = (key) => {
        const data = scenarios[key];
        if (!data) return;
        currentScenario = key;

        if (badgeCco) {
            badgeCco.className = data.ccoClass;
            badgeCco.textContent = data.ccoText;
        }

        elTemp.textContent = `${data.temp.toFixed(1)} °C`;
        if (barTemp) {
            barTemp.className = data.tempBarClass;
            barTemp.style.width = `${data.tempBar}%`;
        }
        if (badgeTemp) {
            badgeTemp.className = data.tempBadgeClass;
            badgeTemp.textContent = data.tempBadgeText;
        }

        if (elVib) elVib.textContent = `${data.vib.toFixed(1)} mm/s`;
        if (barVib) {
            barVib.className = data.vibBarClass;
            barVib.style.width = `${data.vibBar}%`;
        }
        if (badgeVib) {
            badgeVib.className = data.vibBadgeClass;
            badgeVib.textContent = data.vibBadgeText;
        }

        if (elSpeed) elSpeed.textContent = `${data.speed.toFixed(1)} km/h`;
        if (elTrainName) elTrainName.textContent = data.trainName;
        if (logText) logText.textContent = data.log;
    };

    scenarioButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const key = btn.getAttribute("data-scenario");
            scenarioButtons.forEach((b) => {
                const active = b === btn;
                b.classList.toggle("active", active);
                b.setAttribute("aria-pressed", active ? "true" : "false");
            });
            applyScenario(key);
        });
    });

    setInterval(() => {
        const base = scenarios[currentScenario];
        if (!base) return;

        const jitterTemp = (Math.random() - 0.5) * 0.6;
        const jitterVib = (Math.random() - 0.5) * 0.2;
        const jitterSpeed = (Math.random() - 0.5) * 0.9;

        elTemp.textContent = `${(base.temp + jitterTemp).toFixed(1)} °C`;
        if (elVib) elVib.textContent = `${Math.max(0.4, base.vib + jitterVib).toFixed(1)} mm/s`;
        if (elSpeed) elSpeed.textContent = `${Math.max(0, base.speed + jitterSpeed).toFixed(1)} km/h`;
    }, 2800);
}

/*6. Seletor de Sensores na Seção 04*/
function initSensorInspector() {
    const sensorCards = document.querySelectorAll("[data-sensor-card]");
    const inspectorPanel = document.getElementById("sensor-inspector-panel");
    const inspectorTitle = document.getElementById("sensor-inspector-title");
    const inspectorDesc = document.getElementById("sensor-inspector-desc");
    const inspectorRange = document.getElementById("sensor-inspector-range");
    const inspectorFreq = document.getElementById("sensor-inspector-freq");
    const inspectorAction = document.getElementById("sensor-inspector-action");

    if (!inspectorPanel || !inspectorTitle) return;

    const sensorSpecs = {
        temperatura: {
            title: "Monitoramento térmico de eixos e rolamentos",
            desc: "Acompanha variações de temperatura nos vagões e trilhos para avisar a equipe antes que uma peça sofra desgaste severo.",
            range: "Eixos dos trens e trilhos",
            freq: "Graus Celsius (°C) em tempo real",
            action: "Evita quebras durante a viagem"
        },
        vibracao: {
            title: "Acompanhamento da condição dos trilhos",
            desc: "Identifica trechos da linha que apresentam trepidação fora do normal, desnível nos dormentes ou dilatação causada pelo calor.",
            range: "Trechos da via permanente",
            freq: "Estabilidade e nível de vibração",
            action: "Facilita a manutenção preventiva"
        },
        velocidade: {
            title: "Controle de velocidade média por linha",
            desc: "Registra a velocidade das composições em operação para alimentar os gráficos de desempenho do Hub e o histórico de relatórios.",
            range: "Composições em circulação",
            freq: "Quilômetros por hora (km/h)",
            action: "Histórico completo no painel"
        }
    };

    const selectSensor = (sensorKey) => {
        const spec = sensorSpecs[sensorKey];
        if (!spec) return;

        sensorCards.forEach((card) => {
            card.classList.toggle(
                "sensor-card-selected",
                card.getAttribute("data-sensor-card") === sensorKey
            );
        });

        inspectorPanel.style.opacity = "0.5";
        setTimeout(() => {
            inspectorTitle.textContent = spec.title;
            inspectorDesc.textContent = spec.desc;
            if (inspectorRange) inspectorRange.textContent = spec.range;
            if (inspectorFreq) inspectorFreq.textContent = spec.freq;
            if (inspectorAction) inspectorAction.textContent = spec.action;
            inspectorPanel.style.opacity = "1";
        }, 120);
    };

    sensorCards.forEach((card) => {
        card.addEventListener("click", () => {
            selectSensor(card.getAttribute("data-sensor-card"));
        });
    });
}
