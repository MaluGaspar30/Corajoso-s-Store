<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Erro de Acesso - Corajoso’s Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="navbar">
        <a href="index.php" class="logo">
            <img src="img/logo.png" alt="Logo">
            <span>Corajoso’s Store</span>
        </a>
        <nav>
            <div class="dropdown-categorias" id="dropdownCategorias">
    <button type="button" class="btn-dropdown-categorias" id="btnCategorias">
        Categorias 
    </button>
    <div class="menu-categorias">
        <a href="index.php#natal">Coleção de Natal</a>
        <a href="index.php#tercos">Terços</a>
        <a href="index.php#biblias">Bíblias</a>
        <a href="index.php#imagens">Imagens</a>
    </div>
</div>
            <a href="carrinho.php" class="link-carrinho" aria-label="Carrinho">
    <span class="icone-carrinho-wrap">
        <img src="img/carrinho.png" alt="Carrinho">
        <span class="badge-carrinho"><?= $qtd_carrinho ?></span>
    </span>
</a>
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>
    <div class="container">
        <div class="box-form">
            <div class="alerta-erro">
                <h2>Acesso Negado</h2>
                <p>Login ou senha incorretos. Por favor, tente novamente.</p>
            </div>

            <a href="login.php" class="btn btn-centro">Voltar para o Login</a>
        </div>
    </div>
<footer>
    <div class="footer-conteudo">
        <div class="footer-contato">
            <h3>Contatos</h3>
            <p>Telefone: (73) 4002-8922</p>
            <p>Telefone: (73) 4002-8922</p>
            <p><a href="https://www.instagram.com/" target="_blank"><img class="instagram-footer" src="img/instagram.png" alt="Instagram"></a> Instagram</p>
        </div>
        <div class="footer-centro">
            <h3>Corajoso’s Store - 2026</h3>
            <p>Josué 1 : 9</p>
        </div>
    </div>
</footer>
</body>
</html>

