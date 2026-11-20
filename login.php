<?php
session_start();

extract($_POST);
$produto_id = $produto_id ?? null;

$qtd_carrinho = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $qtd_item) {
        $qtd_carrinho += (int) $qtd_item;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Corajoso’s Store</title>
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
        <h2>Entrar na Conta</h2>

        <?php if (isset($_SESSION['erro_login'])): ?>
            <p style="color: red;"><?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?></p>
        <?php endif; ?>

        <form action="processa_login.php" method="POST">
            <?php if ($produto_id): ?>
                <input type="hidden" name="produto_id" value="<?= $produto_id ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" name="login" id="login" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>

            <button type="submit" class="btn">Entrar</button>
        </form>

        <form action="cadastro1.php" method="POST" style="margin-top: 15px;">
            <?php if ($produto_id): ?>
                <input type="hidden" name="produto_id" value="<?= $produto_id ?>">
            <?php endif; ?>
            <p>Ainda não tem conta? <button type="submit" style="background:none; border:none; color:#007bff; text-decoration:underline; cursor:pointer; font-size:inherit; padding:0;">Cadastre-se</button></p>
        </form>
    </div>
</div>

<footer>
    <div class="footer-conteudo">
        <div class="footer-contato">
            <h3>Contatos</h3>
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