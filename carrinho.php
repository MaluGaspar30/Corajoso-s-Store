<?php
session_start();

$produtos = [
    1 => [
        'nome' => 'Aproveite - Imagem de São Miguel Arcanjo.',
        'preco' => 200.00,
        'descricao' => 'Imagem em resina. Em promoção para quaresma de São Miguel.',
        'imagem' => 'img/img1.jpeg'
    ],
    2 => [
        'nome' => 'Bíblia Sagrada - Tradução oficial da CNBB',
        'preco' => 82.50,
        'descricao' => 'Bíblia Sagrada - Edição Santuário do Pai das Misericórdias.',
        'imagem' => 'img/img2.jpeg'
    ],
    3 => [
        'nome' => 'Terço de Nossa Senhora das Graças',
        'preco' => 30.00,
        'descricao' => 'Terço com contas em azul e detalhes em dourado.',
        'imagem' => 'img/img3.jpeg'
    ],
    4 => [
        'nome' => 'Cruz de Madeira - Clássica',
        'preco' => 21.90,
        'descricao' => 'Cruz de madeira de mesa. Tamanho: 12 cm.',
        'imagem' => 'img/img4.jpeg'
    ],
    5 => [
        'nome' => 'Bíblia Sagrada - Editora Ave Maria. ',
        'preco' => 94.90,
        'descricao' => 'Bíblia Sagrada para leitura e momentos de oração.',
        'imagem' => 'img/img5.jpeg'
    ],
    6 => [
        'nome' => ' Bíblia Sagrada - Editora Ave Maria. ',
        'preco' => 129.90,
        'descricao' => 'Edição especial de capa dura para mulheres.',
        'imagem' => 'img/img6.jpeg'
    ]
];

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

extract($_POST);

if (isset($adicionar) && isset($produto_id)) {
    $id = (int)$produto_id;
    if (isset($produtos[$id])) {
        if (isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id]++;
        } else {
            $_SESSION['carrinho'][$id] = 1;
        }
    }
    header('Location: carrinho.php');
    exit;
}

if (isset($remover) && isset($produto_id)) {
    $id = (int)$produto_id;
    unset($_SESSION['carrinho'][$id]);
    header('Location: carrinho.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - Corajoso’s Store</title>
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

<div class="container carrinho-pagina">
    <div class="box-form">
        <h2>Meu Carrinho</h2>

        <?php if (empty($_SESSION['carrinho'])): ?>
            <p>Seu carrinho está vazio.</p>
            <br>
            <a href="index.php" class="btn link-btn">Ver Produtos</a>
        <?php else: ?>

            <?php $total = 0; ?>

            <?php foreach ($_SESSION['carrinho'] as $id => $quantidade): ?>
                <?php 
                    if (!isset($produtos[$id])) continue;
                    $subtotal = $produtos[$id]['preco'] * $quantidade; 
                    $total += $subtotal; 
                ?>

                <div class="item-carrinho" style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <img src="<?= $produtos[$id]['imagem'] ?>" alt="<?= $produtos[$id]['nome'] ?>" style="max-width: 80px; height: auto;">
                    <div>
                        <h3><?= $produtos[$id]['nome'] ?></h3>
                        <p>Quantidade: <?= $quantidade ?></p>
                        <p>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>
                        
                        <form action="carrinho.php" method="POST" style="margin-top: 5px;">
                            <input type="hidden" name="produto_id" value="<?= $id ?>">
                            <button type="submit" name="remover" value="1" style="background: none; border: none; color: red; cursor: pointer; text-decoration: underline; padding: 0;">Remover</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <h3 class="total-carrinho" style="margin: 20px 0;">Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

            <?php if (isset($_SESSION['usuario_logado'])): ?>
                <form action="confirmar.php" method="POST">
                    <button type="submit" class="btn">Finalizar Compra</button>
                </form>
            <?php else: ?>
                <form action="login.php" method="POST">
                    <button type="submit" class="btn">Fazer Login para Finalizar</button>
                </form>
            <?php endif; ?>

        <?php endif; ?>
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