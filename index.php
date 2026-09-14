<?php
session_start();

if (isset($_POST['acao']) && $_POST['acao'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Corajoso’s Store - Artigos Religiosos Católicos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="navbar">
        <a href="index.php" class="logo">
            <img src="img/logo.png" alt="Logo">
            <span>Corajoso’s Store </span>
        </a>
        <nav>
            <a href="index.php#produtos">Produtos</a>
            <a href="carrinho.php"><img src="img/carrinho.png" alt="Carrinho"> Carrinho</a>
            
            <?php if (isset($_SESSION['usuario_logado'])): ?>
                <span class="usuario-nav"> <?= $_SESSION['usuario_nome'] ?? 'Usuário' ?></span>
                <form action="index.php" method="POST" style="display: inline;">
                    <input type="hidden" name="acao" value="logout">
                    <button type="submit" class="btn-logout">Sair</button>
                </form>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<div class="slider">
    <div class="slides-track">
        <div class="slide"><img src="img/bannes/banner1amor.jpg" alt="Banner 1"></div>
        <div class="slide"><img src="img/bannes/banner2.jpg" alt="Banner 2"></div>
        <div class="slide"><img src="img/bannes/banner3.jpg" alt="Banner 3"></div>
    </div>

    <div class="navigation-manual">
        <button type="button" class="manual-btn active" data-slide="0"></button>
        <button type="button" class="manual-btn" data-slide="1"></button>
        <button type="button" class="manual-btn" data-slide="2"></button>
    </div>
</div>

<div class="container">
    <h2 id="produtos">Produtos</h2>

    <div class="grid-produtos">
        <?php foreach ($produtos as $id => $prod): ?>
            <div class="card-produto">
                <div>
                    <img src="<?= $prod['imagem'] ?>" alt="<?= $prod['nome'] ?>">
                    <h3><?= $prod['nome'] ?></h3>
                    <p><?= $prod['descricao'] ?></p>
                </div>
                <div>
                    <div class="preco">R$ <?= number_format($prod['preco'], 2, ',', '.') ?></div>
                    
                    <?php if (isset($_SESSION['usuario_logado'])): ?>
                        <form action="carrinho.php" method="POST">
                            <input type="hidden" name="produto_id" value="<?= $id ?>">
                            <button type="submit" name="adicionar" value="1" class="btn">Comprar</button>
                        </form>
                    <?php else: ?>
                        <form action="login.php" method="POST">
                            <input type="hidden" name="produto_id" value="<?= $id ?>">
                            <button type="submit" class="btn">Comprar</button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>
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

<script>
var slideAtual = 0;
var slides = document.querySelectorAll('.slide');
var botoes = document.querySelectorAll('.manual-btn');
var track = document.querySelector('.slides-track');

function mostrarSlide(numero) {
    slideAtual = numero;
    track.style.transform = 'translateX(-' + (numero * 33.333333) + '%)';

    for (var i = 0; i < botoes.length; i++) {
        botoes[i].classList.remove('active');
    }

    if (botoes[numero]) {
        botoes[numero].classList.add('active');
    }
}

for (var i = 0; i < botoes.length; i++) {
    botoes[i].onclick = function() {
        mostrarSlide(parseInt(this.getAttribute('data-slide')));
    };
}

setInterval(function() {
    slideAtual++;
    if (slideAtual >= slides.length) {
        slideAtual = 0;
    }
    mostrarSlide(slideAtual);
}, 5000);
</script>

</body>
</html>