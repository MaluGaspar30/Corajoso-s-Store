<?php
session_start();
if (!isset($_SESSION['cpf_temp'])) {
    header('Location: cadastro1.php');
    exit;
}

extract($_POST);
$produto_id = $produto_id ?? $_SESSION['produto_id_temp'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Etapa 2 - Corajoso’s Store</title>
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
            <a href="index.php#produtos">Produtos</a>
            <a href="carrinho.php"><img src="img/carrinho.png" alt="Carrinho"> Carrinho</a>
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>
    <div class="container">
        <div class="box-form">
            <h2>Etapa 2: Escolha um Login e Senha. </h2>

            <form action="salvar_login.php" method="POST">
                <?php if ($produto_id): ?>
                    <input type="hidden" name="produto_id" value="<?= $produto_id ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="login">Escolha um Login:</label>
                    <input type="text" id="login" name="login" required>
                </div>

                <div class="form-group">
                    <label for="senha">Crie uma Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <button type="submit" class="btn">Finalizar Cadastro</button>
            </form>
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