const btnSidebar = document.getElementById("btnSidebar");
const sidebar = document.getElementById("sidebar");
const menuItems = document.querySelectorAll(".menu-item");
const menuLinks = document.querySelectorAll(".menu-item a[href$='.php']");
const menuConfiguracoes = document.getElementById("menuConfiguracoes");
const botaoConfiguracoes = menuConfiguracoes
    ? menuConfiguracoes.querySelector(".menu-button-item")
    : null;

function aplicarModoInicial() {
    if (!sidebar) return;

    if (window.innerWidth <= 768) {
        sidebar.classList.remove("closed");
        sidebar.classList.remove("open");
    } else {
        sidebar.classList.remove("open");
        sidebar.classList.remove("closed");
    }
}

function alternarSidebar() {
    if (!sidebar) return;

    if (window.innerWidth <= 768) {
        sidebar.classList.toggle("open");
    } else {
        sidebar.classList.toggle("closed");
    }
}

function limparAtivo() {
    menuItems.forEach(function (item) {
        item.classList.remove("active");
    });
}

function atualizarItemAtivoPorPagina() {
    const paginaAtual = window.location.pathname.split("/").pop() || "index.php";

    limparAtivo();

    menuLinks.forEach(function (link) {
        const hrefLink = link.getAttribute("href");

        if (hrefLink === paginaAtual) {
            const itemPai = link.closest(".menu-item");
            if (itemPai) {
                itemPai.classList.add("active");
            }
        }
    });
}

function controlarSubmenu() {
    if (!botaoConfiguracoes || !menuConfiguracoes || !sidebar) {
        return;
    }

    botaoConfiguracoes.addEventListener("click", function () {
        if (sidebar.classList.contains("closed")) {
            sidebar.classList.remove("closed");
            return;
        }

        if (window.innerWidth <= 768 && !sidebar.classList.contains("open")) {
            sidebar.classList.add("open");
            return;
        }

        menuConfiguracoes.classList.toggle("open");
    });
}

function fecharSubmenuQuandoFecharSidebar() {
    if (!menuConfiguracoes || !sidebar) {
        return;
    }

    if (sidebar.classList.contains("closed")) {
        menuConfiguracoes.classList.remove("open");
    }

    if (window.innerWidth <= 768 && !sidebar.classList.contains("open")) {
        menuConfiguracoes.classList.remove("open");
    }
}

if (btnSidebar && sidebar) {
    btnSidebar.addEventListener("click", function () {
        alternarSidebar();
        fecharSubmenuQuandoFecharSidebar();
    });
}

window.addEventListener("resize", function () {
    aplicarModoInicial();
    fecharSubmenuQuandoFecharSidebar();
});

controlarSubmenu();
aplicarModoInicial();
atualizarItemAtivoPorPagina();

function criarGraficoLinha(canvasId, dadosGrafico) {
    const canvas = document.getElementById(canvasId);
    if (!canvas || !dadosGrafico || !window.Chart) {
        return;
    }

    new Chart(canvas, {
        type: "line",
        data: {
            labels: dadosGrafico.labels,
            datasets: [{
                label: dadosGrafico.label || "Leitura",
                data: dadosGrafico.dados,
                borderColor: dadosGrafico.cor || "#a3ff12",
                backgroundColor: (dadosGrafico.cor || "#a3ff12") + "33",
                fill: true,
                tension: 0.25,
                pointRadius: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: "#f2f5f7"
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: "#9aa5b1"
                    },
                    grid: {
                        color: "rgba(255,255,255,0.06)"
                    }
                },
                y: {
                    ticks: {
                        color: "#9aa5b1"
                    },
                    grid: {
                        color: "rgba(255,255,255,0.06)"
                    }
                }
            }
        }
    });
}

// Criar gráficos se existirem
if (window.graficoTemperatura) criarGraficoLinha("tempChart", window.graficoTemperatura);
if (window.graficoTemperaturaA) criarGraficoLinha("tempChartA", window.graficoTemperaturaA);
if (window.graficoTemperaturaB) criarGraficoLinha("tempChartB", window.graficoTemperaturaB);
if (window.graficoUmidadeA) criarGraficoLinha("umidadeChartA", window.graficoUmidadeA);
if (window.graficoUmidadeB) criarGraficoLinha("umidadeChartB", window.graficoUmidadeB);

const autoRefresh = Number(document.body?.dataset?.autoRefresh || 0);

window.addEventListener("load", function () {
    const scrollSalvo = sessionStorage.getItem("scrollWorkerSafety");

    if (scrollSalvo !== null) {
        window.scrollTo(0, Number(scrollSalvo));
        sessionStorage.removeItem("scrollWorkerSafety");
    }
});

if (autoRefresh > 0) {
    setInterval(() => {
        sessionStorage.setItem("scrollWorkerSafety", window.scrollY);
        window.location.reload();
    }, autoRefresh * 1000);
}