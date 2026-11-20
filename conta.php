<?php
session_start();

if (isset($_POST['acao']) && $_POST['acao'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

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

$login_atual = $_SESSION['usuario_login'] ?? '';
$dados = $_SESSION['dados_usuario'] ?? [];

if ($login_atual !== '') {
    $res_u = banco($server, $user, $password, $db, "SELECT * FROM usuarios WHERE login = '$login_atual' LIMIT 1");
    if ($res_u && $res_u->num_rows > 0) {
        $u = $res_u->fetch_assoc();
        $dados = [
            'nome'     => $u['nome'] ?? ($dados['nome'] ?? ''),
            'email'    => $u['email'] ?? ($dados['email'] ?? ''),
            'cpf'      => $u['cpf'] ?? ($dados['cpf'] ?? ''),
            'endereco' => $u['endereco'] ?? ($dados['endereco'] ?? ''),
            'bairro'   => $u['bairro'] ?? ($dados['bairro'] ?? ''),
            'cidade'   => $u['cidade'] ?? ($dados['cidade'] ?? ''),
            'estado'   => $u['estado'] ?? ($dados['estado'] ?? ''),
            'cep'      => $u['cep'] ?? ($dados['cep'] ?? '')
        ];
        $_SESSION['dados_usuario'] = $dados;
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

    $dados['endereco'] = $novo_endereco;
    $dados['bairro']   = $novo_bairro;
    $dados['cidade']   = $nova_cidade;
    $dados['estado']   = $novo_estado;
    $dados['cep']      = $novo_cep;

    if (!isset($_SESSION['dados_usuario']) || !is_array($_SESSION['dados_usuario'])) {
        $_SESSION['dados_usuario'] = [];
    }
    $_SESSION['dados_usuario']['endereco'] = $novo_endereco;
    $_SESSION['dados_usuario']['bairro']   = $novo_bairro;
    $_SESSION['dados_usuario']['cidade']   = $nova_cidade;
    $_SESSION['dados_usuario']['estado']   = $novo_estado;
    $_SESSION['dados_usuario']['cep']      = $novo_cep;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minha Conta - Corajoso’s Store</title>
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
            <form action="conta.php" method="POST" style="display: inline;">
                <input type="hidden" name="acao" value="logout">
                <button type="submit" class="btn-logout">Sair</button>
            </form>
        </nav>
    </div>
</header>

<div class="container">
    <div class="box-form">
        <h2>Minha Conta</h2>

        <p><strong>Nome:</strong> <?= $dados['nome'] ?? ($_SESSION['usuario_nome'] ?? '') ?></p>
        <p><strong>E-mail:</strong> <?= $dados['email'] ?? '' ?></p>
        <p><strong>Login:</strong> <?= $login_atual ?></p>
        <p><strong>CPF:</strong> <?= $dados['cpf'] ?? '' ?></p>

        <hr style="margin: 15px 0;">

        <h3>Dados de Entrega</h3>
        <p><strong>Endereço:</strong> <?= $dados['endereco'] ?? '' ?></p>
        <p><strong>Bairro:</strong> <?= $dados['bairro'] ?? '' ?></p>
        <p><strong>Cidade / Estado:</strong> <?= $dados['cidade'] ?? '' ?> / <?= $dados['estado'] ?? '' ?></p>
        <p><strong>CEP:</strong> <?= $dados['cep'] ?? '' ?></p>

        <button type="button" class="btn" onclick="abrirModalEntrega()" style="margin-top: 12px;">Editar Dados de Entrega</button>

        <hr style="margin: 20px 0;">

        <a href="compras.php" class="btn link-btn">Minhas Compras</a>
        <a href="favoritos.php" class="btn link-btn" style="margin-top: 10px;">Meus Favoritos</a>
        <a href="index.php" class="btn link-btn" style="margin-top: 10px;">Voltar à Loja</a>
    </div>
</div>

<div id="modalEntrega" class="modal-fundo">
    <div class="modal-caixa">
        <button type="button" class="modal-fechar" onclick="fecharModalEntrega()" title="Cancelar">&times;</button>
        <h3 style="color: var(--vermelho-escuro); margin-bottom: 15px;">Editar Dados de Entrega</h3>

        <form action="conta.php" method="POST">
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" value="<?= $dados['endereco'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" value="<?= $dados['bairro'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?= $dados['cidade'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado (UF):</label>
                <input type="text" id="estado" name="estado" maxlength="2" value="<?= $dados['estado'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" value="<?= $dados['cep'] ?? '' ?>" required>
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
</script>
</body>
</html>