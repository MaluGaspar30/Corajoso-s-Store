<?php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit;
}

$produtos = [
    1 => ['nome' => 'Aproveite - Imagem de São Miguel Arcanjo.', 'preco' => 200.00, 'descricao' => 'Imagem em resina.', 'imagem' => 'img/img1.jpeg'],
    2 => ['nome' => 'Bíblia Sagrada - Tradução oficial da CNBB', 'preco' => 82.50, 'descricao' => 'Bíblia Sagrada CNBB.', 'imagem' => 'img/img2.jpeg'],
    3 => ['nome' => 'Terço de Nossa Senhora das Graças', 'preco' => 30.00, 'descricao' => 'Terço azul com detalhes dourados.', 'imagem' => 'img/img3.jpeg'],
    4 => ['nome' => 'Cruz de Madeira - Clássica', 'preco' => 21.90, 'descricao' => 'Cruz de madeira 12 cm.', 'imagem' => 'img/img4.jpeg'],
    5 => ['nome' => 'Bíblia Sagrada - Editora Ave Maria.', 'preco' => 94.90, 'descricao' => 'Bíblia Sagrada Ave Maria.', 'imagem' => 'img/img5.jpeg'],
    6 => ['nome' => 'Bíblia Sagrada - Editora Ave Maria Especial', 'preco' => 129.90, 'descricao' => 'Edição especial capa dura.', 'imagem' => 'img/img6.jpeg']
];

$usuario = $_SESSION['dados_usuario'];
$itens_resumo = [];
$total_compra = 0;

foreach ($_SESSION['carrinho'] as $id => $qtd) {
    if (isset($produtos[$id])) {
        $sub = $produtos[$id]['preco'] * $qtd;
        $total_compra += $sub;
        $itens_resumo[] = $qtd . "x " . $produtos[$id]['nome'];
    }
}

$descricao_produtos = implode(" | ", $itens_resumo);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Compra - Corajoso’s Store</title>
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
        <div class="box-form">
            <h2>Resumo da Compra</h2>

            <h3>Itens do Pedido</h3>
            <?php foreach ($_SESSION['carrinho'] as $id => $qtd): ?>
                <?php if (isset($produtos[$id])): ?>
                    <div class="produto-confirmacao" style="margin-bottom: 10px;">
                        <img src="<?= $produtos[$id]['imagem'] ?>" alt="<?= $produtos[$id]['nome'] ?>" style="max-width: 60px;">
                        <div>
                            <p><strong>Item:</strong> <?= $produtos[$id]['nome'] ?> (x<?= $qtd ?>)</p>
                            <p><strong>Valor Un.:</strong> R$ <?= number_format($produtos[$id]['preco'], 2, ',', '.') ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <h3 style="margin-top: 15px;">Total: R$ <?= number_format($total_compra, 2, ',', '.') ?></h3>

            <div class="aviso-bencao" style="margin-top: 15px;">
                <strong>Aviso:</strong> Depois de receber a peça, você pode levá-la a um sacerdote para pedir que ele a abençoe.
            </div>

            <hr>

            <h3>Dados de Entrega</h3>
            <p><strong>Nome:</strong> <?= $usuario['nome'] ?? '' ?></p>
            <p><strong>CPF:</strong> <?= $usuario['cpf'] ?? '' ?></p>
            <p><strong>Endereço:</strong> <?= $usuario['endereco'] ?? '' ?>, <?= $usuario['bairro'] ?? '' ?> - <?= $usuario['cidade'] ?? '' ?>/<?= $usuario['estado'] ?? '' ?></p>
            <p><strong>CEP:</strong> <?= $usuario['cep'] ?? '' ?></p>

            <hr>

            <form action="salvar_venda.php" method="POST">
                <input type="hidden" name="produto_nome" value="<?= htmlspecialchars($descricao_produtos) ?>">
                <input type="hidden" name="produto_valor" value="<?= $total_compra ?>">

                <div class="form-group">
                    <label for="forma_pagamento">Forma de Pagamento:</label>
                    <select name="forma_pagamento" id="forma_pagamento" required>
                        <option value="Pix">PIX</option>
                    </select>
                </div>

                <button type="submit" class="btn">Confirmar e Finalizar Pedido</button>
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