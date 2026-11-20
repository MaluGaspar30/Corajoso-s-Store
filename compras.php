<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit;
}

include_once "app/cons.php";
require_once "app/DLL.php";

$qtd_carrinho = 0;
if (!empty($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $qtd_item) {
        $qtd_carrinho += (int) $qtd_item;
    }
}

$nome_usuario = $_SESSION['dados_usuario']['nome'] ?? ($_SESSION['usuario_nome'] ?? '');
$login_usuario = $_SESSION['usuario_login'] ?? '';

$consulta_vendas = "SELECT * FROM vendas WHERE usuario = '$nome_usuario'";
if ($login_usuario !== '' && $login_usuario !== $nome_usuario) {
    $consulta_vendas .= " OR usuario = '$login_usuario'";
}
$consulta_vendas .= " ORDER BY numero DESC";

$resultado_vendas = banco($server, $user, $password, $db, $consulta_vendas);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minhas Compras - Corajoso’s Store</title>
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
        <h2>Minhas Compras</h2>

        <?php if ($resultado_vendas && $resultado_vendas->num_rows > 0): ?>
            <?php while ($venda = $resultado_vendas->fetch_assoc()): ?>
                <div style="border-bottom: 1px solid #ddd; padding: 12px 0; margin-bottom: 12px;">
                    <p><strong>Pedido Nº:</strong> <?= $venda['numero'] ?? '' ?></p>
                    <p><strong>Itens:</strong> <?= $venda['produto'] ?? '' ?></p>
                    <p><strong>Valor Total:</strong> R$ <?= number_format((float) ($venda['valor'] ?? 0), 2, ',', '.') ?></p>
                    <p><strong>Pagamento:</strong> <?= $venda['forma_pagamento'] ?? '' ?></p>
                    <p><strong>Data:</strong> <?= $venda['data_venda'] ?? '' ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Você ainda não realizou nenhuma compra.</p>
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