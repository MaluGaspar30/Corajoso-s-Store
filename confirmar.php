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

include_once "app/cons.php";
require_once "app/DLL.php";
require_once "app/produtos.php";

$login_atual = $_SESSION['usuario_login'] ?? '';
$usuario = $_SESSION['dados_usuario'] ?? [];

if ($login_atual !== '') {
    $res_u = banco($server, $user, $password, $db, "SELECT * FROM usuarios WHERE login = '$login_atual' LIMIT 1");
    if ($res_u && $res_u->num_rows > 0) {
        $u = $res_u->fetch_assoc();
        $usuario = [
            'nome'     => $u['nome'] ?? ($usuario['nome'] ?? ''),
            'cpf'      => $u['cpf'] ?? ($usuario['cpf'] ?? ''),
            'endereco' => $u['endereco'] ?? ($usuario['endereco'] ?? ''),
            'bairro'   => $u['bairro'] ?? ($usuario['bairro'] ?? ''),
            'cidade'   => $u['cidade'] ?? ($usuario['cidade'] ?? ''),
            'estado'   => $u['estado'] ?? ($usuario['estado'] ?? ''),
            'cep'      => $u['cep'] ?? ($usuario['cep'] ?? '')
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_entrega']) && $login_atual !== '') {
    $novo_endereco = trim($_POST['endereco'] ?? '');
    $novo_bairro   = trim($_POST['bairro'] ?? '');
    $nova_cidade   = trim($_POST['cidade'] ?? '');
    $novo_estado   = strtoupper(trim($_POST['estado'] ?? ''));
    $novo_cep      = trim($_POST['cep'] ?? '');

    banco(
        $server,
        $user,
        $password,
        $db,
        "UPDATE usuarios SET endereco = '$novo_endereco', bairro = '$novo_bairro', cidade = '$nova_cidade', estado = '$novo_estado', cep = '$novo_cep' WHERE login = '$login_atual'"
    );

    $usuario['endereco'] = $novo_endereco;
    $usuario['bairro']   = $novo_bairro;
    $usuario['cidade']   = $nova_cidade;
    $usuario['estado']   = $novo_estado;
    $usuario['cep']      = $novo_cep;

    if (!isset($_SESSION['dados_usuario']) || !is_array($_SESSION['dados_usuario'])) {
        $_SESSION['dados_usuario'] = [];
    }
    $_SESSION['dados_usuario']['endereco'] = $novo_endereco;
    $_SESSION['dados_usuario']['bairro']   = $novo_bairro;
    $_SESSION['dados_usuario']['cidade']   = $nova_cidade;
    $_SESSION['dados_usuario']['estado']   = $novo_estado;
    $_SESSION['dados_usuario']['cep']      = $novo_cep;
}

$qtd_carrinho = 0;
foreach ($_SESSION['carrinho'] as $qtd_item) {
    $qtd_carrinho += (int) $qtd_item;
}

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

        <button type="button" class="btn" onclick="abrirModalEntrega()" style="margin-top: 10px; background-color: #555;">Editar Dados de Entrega</button>

        <hr>

        <form action="salvar_venda.php" method="POST">
            <input type="hidden" name="produto_nome" value="<?= $descricao_produtos ?>">
            <input type="hidden" name="produto_valor" value="<?= $total_compra ?>">

            <div class="form-group">
                <label for="forma_pagamento">Forma de Pagamento:</label>
                <select name="forma_pagamento" id="forma_pagamento" required>
                    <option value="Pix">PIX</option>
                </select>
            </div>

            <div id="area-pix" style="display: none; margin: 15px 0; padding: 15px; border: 1px solid #ccc; border-radius: 6px; text-align: center; background-color: #fafafa;">
                <p><strong>Pagamento via PIX</strong></p>
                <p>Escaneie o QR Code abaixo para realizar o pagamento:</p>
                <img src="img/qrcodemalu.jpeg" alt="QR Code PIX - Corajoso’s Store" style="max-width: 220px; width: 100%; height: auto; margin: 10px 0;">
            </div>

            <button type="submit" class="btn">Confirmar e Finalizar Pedido</button>
        </form>
    </div>
</div>

<div id="modalEntrega" class="modal-fundo">
    <div class="modal-caixa">
        <button type="button" class="modal-fechar" onclick="fecharModalEntrega()" title="Cancelar">&times;</button>
        <h3 style="color: var(--vermelho-escuro); margin-bottom: 15px;">Editar Dados de Entrega</h3>

        <form action="confirmar.php" method="POST">
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?= $usuario['endereco'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" value="<?= $usuario['bairro'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?= $usuario['cidade'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado (UF):</label>
                <input type="text" id="estado" name="estado" maxlength="2" value="<?= $usuario['estado'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" value="<?= $usuario['cep'] ?? '' ?>" required>
            </div>

            <div class="modal-acoes">
                <button type="submit" name="salvar_entrega" value="1" class="btn">Salvar Alterações</button>
                <button type="button" class="btn btn-cancelar" onclick="fecharModalEntrega()">Cancelar</button>
            </div>
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

<script>
function abrirModalEntrega() {
    document.getElementById('modalEntrega').classList.add('ativo');
}

function fecharModalEntrega() {
    document.getElementById('modalEntrega').classList.remove('ativo');
}

document.getElementById('modalEntrega').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalEntrega();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var selectPagamento = document.getElementById('forma_pagamento');
    var areaPix = document.getElementById('area-pix');

    function atualizarAreaPix() {
        if (!selectPagamento || !areaPix) return;
        areaPix.style.display = (selectPagamento.value.toLowerCase() === 'pix') ? 'block' : 'none';
    }

    if (selectPagamento) {
        selectPagamento.addEventListener('change', atualizarAreaPix);
        atualizarAreaPix();
    }
});
</script>
</body>
</html>