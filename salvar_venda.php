<?php
session_start();
include "app/cons.php";
require_once "app/DLL.php";

extract($_POST);

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['dados_usuario']['nome'] ?? $_SESSION['usuario_nome'];
$data_venda = date('Y-m-d H:i:s');

$consulta = "INSERT INTO vendas (usuario, produto, valor, forma_pagamento, data_venda) VALUES ('$usuario', '$produto_nome', '$produto_valor', '$forma_pagamento', '$data_venda')";
banco($server, $user, $password, $db, $consulta);

$consulta = "SELECT * FROM vendas ORDER BY numero DESC LIMIT 1";
$resultado = banco($server, $user, $password, $db, $consulta);
$linha = $resultado->fetch_assoc();
$id_venda = $linha['numero'] ?? 1;

unset($_SESSION['carrinho']);
unset($_SESSION['produto_selecionado']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido Concluído - Corajoso’s Store</title>
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
            <a href="index.php">Produtos</a>
            <a href="carrinho.php"><img src="img/carrinho.png" alt="Carrinho"> Carrinho</a>
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>
    <div class="container">
        <div class="box-form" style="text-align: center;">
            <h2 style="color: #28a745;">Pedido Realizado com Sucesso!</h2>
            <p>Agradecemos a preferência. Que Deus abençoe sua casa!</p>
            <p><strong>Nº do Pedido:</strong> <?= $id_venda ?></p>
            <br>
            <a href="index.php" class="btn btn-centro">Voltar às compras</a>
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