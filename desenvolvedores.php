<?php require_once __DIR__ . "/verificar_login.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-layout">
            <div class="sidebar-header">
                <div class="user-box">
                    <div class="user-avatar"><img src="img/logo.png" alt="Usuário"></div>
                    <div class="user-info">
                        <h1>Worker Safety</h1>
                        <p>Painel de Controle</p>
                    </div>
                </div><button id="btnSidebar" class="btn-sidebar" type="button">☰</button>
            </div>
            <div class="sidebar-content">
                <nav class="menu">
                    <ul>
                        <li class="menu-header"><span>DASHBOARDS</span></li>
                        <li class="menu-item"><a href="index.php"><span class="menu-icon"><img src="img/Dashboard.png" alt="Dashboard"></span><span class="menu-title">Overview</span></a></li>
                        <li class="menu-item"><a href="temperatura.php"><span class="menu-icon"><img src="img/Temperatura.png" alt="Temperatura"></span><span class="menu-title">Temperatura</span></a></li>
                        <li class="menu-item"><a href="umidade.php"><span class="menu-icon"><img src="img/Umidade.png" alt="Umidade"></span><span class="menu-title">Umidade</span></a></li>
                        <li class="menu-item"><a href="alertas.php"><span class="menu-icon"><img src="img/Alertas.png" alt="Alertas"></span><span class="menu-title">Alertas</span></a></li>
                        <li class="menu-item"><a href="setores.php"><span class="menu-icon"><img src="img/Setores.png" alt="Setores"></span><span class="menu-title">Setores</span></a></li>
                        <li class="menu-item"><a href="desenvolvedores.php"><span class="menu-icon"> <img src="img/Desenvolvedores.png" alt="Desenvolvedores"></span> <span class="menu-title"> Desenvolvedores</span></a></li>
                        <li class="menu-item"><a href="logout.php"><span class="menu-icon">↪</span><span class="menu-title">Sair</span></a></li>
                    </ul>
                </nav>
            </div>
            <div class="sidebar-footer">
                <div class="footer-logo"><span class="footer-dot"></span><span class="footer-text">Worker Safety</span></div>
            </div>
        </div>
    </aside>
    <main class="main-content" id="mainContent">
        <div class="page-header">
            <h1>Desenvolvedores</h1>
</div>
        <div class="config-grid">
            <section class="config-card" id="Evelyn">
                <div>
                    <img class="photo-card" src="img/minha-foto.jpg" alt="Evelyn">
                </div>
                <div>
                    <h2 class="text-title">Evelyn Evaristo</h2>
                    <h3 class="text-subtitle">Função: Dev</h3>
                    <p class="text-one"><strong>Curso/Turma:</strong> 3ºInfo</p>
                    <p class="text-one"><strong>Instagram: </strong> <a href="https://www.instagram.com/evelyn_evaristo_/">@evelyn_evaristo_</a></p>
                    <p class="text-one"><strong>GitHub: </strong> <a href="https://github.com/Evelyn-evaristo">Evelyn-evaristo</a>
                    <p class="text-one"><strong>Email: </strong>evelyn.l.evaristo@gmail.com</a>
                </div>
            </section>
            <section class="config-card" id="Marcos">
                <div>
                    <img class="photo-card" src="img/marcos.jpg" alt="Marcos">
                </div>
                <div>
                    <h2 class="text-title">Marcos Gerard</h2>
                    <h3 class="text-subtitle">Função: Parte Escrita</h3>
                    <p class="text-one"><strong>Curso/Turma:</strong> 3ºInfo</p>
                    <p class="text-one"><strong>Instagram: </strong> <a href="https://www.instagram.com/mg__gera/">@mg__gera</a></p>
                    <p class="text-one"><strong>GitHub: </strong> <a href="https://github.com/gera14057">gera14057</a>
                    <p class="text-one"><strong>Email: </strong>marcosgerard7@gmail.com</a>
                </div>
            </section>
            <section class="config-card" id="Mateus">
                <div>
                    <img class="photo-card" src="img/mateus.jpg" alt="Mateus">
                </div>
                <div>
                    <h2 class="text-title">Mateus Muriel</h2>
                    <h3 class="text-subtitle">Função: Maquete</h3>
                    <p class="text-one"><strong>Curso/Turma:</strong> 3ºInfo</p>
                    <p class="text-one"><strong>Instagram: </strong> <a href="https://www.instagram.com/mateusmuriel66/">@mateusmuriel66</a></p>
                    <p class="text-one"><strong>GitHub: </strong>NULL</a>
                    <p class="text-one"><strong>Email: </strong>Mateusmuriel66@gmail.com</a>
                </div>
            </section>
            </section>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>

</html>
