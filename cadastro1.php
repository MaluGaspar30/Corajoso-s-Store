<?php
session_start();

extract($_POST);
$produto_id = $produto_id ?? null;

$qtd_carrinho = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $qtd_item) {
        $qtd_carrinho += (int) $qtd_item;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Etapa 1 - Corajoso’s Store</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function validarCPF(cpf) {
            cpf = cpf.replace(/[^\d]+/g,'');
            if(cpf == '' || cpf.length != 11) return false;
            return true;
        }

        function validarFormulario() {
            var cpf = document.getElementById('cpf').value;
            if(!validarCPF(cpf)) {
                alert('Por favor, informe um CPF válido com 11 dígitos.');
                return false;
            }
            return true;
        }
    </script>
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
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="box-form">
        <h2>Etapa 1: Dados Pessoais</h2>

        <?php if (isset($_SESSION['erro_cadastro'])): ?>
            <p style="color: red;"><?= $_SESSION['erro_cadastro']; unset($_SESSION['erro_cadastro']); ?></p>
        <?php endif; ?>

        <form action="salvar_usuario.php" method="POST" onsubmit="return validarFormulario()">
            <?php if ($produto_id): ?>
                <input type="hidden" name="produto_id" value="<?= $produto_id ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="cpf">CPF (Somente Números):</label>
                <input type="text" id="cpf" name="cpf" maxlength="11" required>
            </div>

            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" id="endereco" name="endereco" required>
            </div>

            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" maxlength="2" placeholder="UF" required>
            </div>

            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" required>
            </div>

            <button type="submit" class="btn">Avançar para Acesso</button>
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