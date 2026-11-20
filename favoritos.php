<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit;
}

require_once "app/produtos.php";

$login_usuario = $_SESSION['usuario_login'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_favorito']) && isset($_POST['produto_id'])) {
    $id_rem = (int) $_POST['produto_id'];
    if ($login_usuario !== '') {
        banco($server, $user, $password, $db, "DELETE FROM favoritos WHERE usuario_login = '$login_usuario' AND produto_id = $id_rem");
    }
    header("Location: favoritos.php");
    exit;
}

$qtd_carrinho = 0;
if (!empty($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $qtd_item) {
        $qtd_carrinho += (int) $qtd_item;
    }
}

$ids_favoritos = [];
if ($login_usuario !== '') {
    $res_fav = banco($server, $user, $password, $db, "SELECT produto_id FROM favoritos WHERE usuario_login = '$login_usuario'");
    if ($res_fav && $res_fav->num_rows > 0) {
        while ($row = $res_fav->fetch_assoc()) {
            $ids_favoritos[] = (int) $row['produto_id'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Favoritos - Corajoso’s Store</title>
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
            <a href="conta.php" class="usuario-nav"><?= $_SESSION['usuario_nome'] ?? 'Usuário' ?></a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="box-form">
        <h2>Meus Favoritos</h2>

        <?php if (empty($ids_favoritos)): ?>
            <p>Você ainda não adicionou nenhum produto aos favoritos.</p>
        <?php else: ?>
            <?php foreach ($ids_favoritos as $id_prod): ?>
                <?php if (!isset($produtos[$id_prod])) continue; ?>
                <?php $prod = $produtos[$id_prod]; ?>

                <div class="item-carrinho" style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <img src="<?= $prod['imagem'] ?>" alt="<?= $prod['nome'] ?>" style="max-width: 80px; height: auto;">
                    <div>
                        <h3><?= $prod['nome'] ?></h3>
                        <p>R$ <?= number_format($prod['preco'], 2, ',', '.') ?></p>

                        <form action="carrinho.php" method="POST" style="display: inline-block; margin-top: 6px;">
                            <input type="hidden" name="produto_id" value="<?= $id_prod ?>">
                            <button type="submit" name="acao_compra" value="comprar_agora" class="btn" style="padding: 8px 14px; font-size: 0.9em;">Comprar Agora</button>
                        </form>

                        <form action="favoritos.php" method="POST" style="display: inline-block; margin-left: 8px;">
                            <input type="hidden" name="produto_id" value="<?= $id_prod ?>">
                            <button type="submit" name="remover_favorito" value="1" style="background: none; border: none; color: red; cursor: pointer; text-decoration: underline;">Remover</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <br>
        <a href="conta.php" class="btn link-btn">Voltar para Minha Conta</a>
        <a href="index.php#produtos" class="btn link-btn" style="margin-top: 10px; background-color: #555;">Ver Produtos</a>
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