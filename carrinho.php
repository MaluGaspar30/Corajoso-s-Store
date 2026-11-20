<?php
session_start();

require_once "app/produtos.php";

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['produto_id']) ? (int) $_POST['produto_id'] : 0;

    if ((isset($_POST['acao_compra']) || isset($_POST['adicionar'])) && isset($produtos[$id])) {
        if (!isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id] = 0;
        }
        $_SESSION['carrinho'][$id]++;
        header('Location: carrinho.php');
        exit;
    }

    if (isset($_POST['aumentar']) && isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
        header('Location: carrinho.php');
        exit;
    }

    if (isset($_POST['diminuir']) && isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]--;
        if ($_SESSION['carrinho'][$id] <= 0) {
            unset($_SESSION['carrinho'][$id]);
        }
        header('Location: carrinho.php');
        exit;
    }

    if (isset($_POST['remover']) && isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
        header('Location: carrinho.php');
        exit;
    }
}

$qtd_carrinho = 0;
foreach ($_SESSION['carrinho'] as $qtd_item) {
    $qtd_carrinho += (int) $qtd_item;
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
            <?php if (isset($_SESSION['usuario_logado'])): ?>
                <span class="usuario-nav"> <?= $_SESSION['usuario_nome'] ?? 'Usuário' ?></span>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
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
                        <p>Valor unitário: R$ <?= number_format($produtos[$id]['preco'], 2, ',', '.') ?></p>

                        <div style="display: flex; align-items: center; gap: 10px; margin: 8px 0;">
                            <span>Quantidade:</span>
                            <form action="carrinho.php" method="POST" style="display: inline;">
                                <input type="hidden" name="produto_id" value="<?= $id ?>">
                                <button type="submit" name="diminuir" value="1" style="padding: 2px 10px; cursor: pointer;">-</button>
                            </form>

                            <strong><?= $quantidade ?></strong>

                            <form action="carrinho.php" method="POST" style="display: inline;">
                                <input type="hidden" name="produto_id" value="<?= $id ?>">
                                <button type="submit" name="aumentar" value="1" style="padding: 2px 10px; cursor: pointer;">+</button>
                            </form>
                        </div>

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

            <a href="index.php#produtos" class="btn link-btn" style="margin-top: 10px; background-color: #555;">Continuar Comprando</a>

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