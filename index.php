<?php
session_start();

if (isset($_POST['acao']) && $_POST['acao'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

require_once "app/produtos.php";

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_POST['acao_compra']) && $_POST['acao_compra'] === 'adicionar' && isset($_POST['produto_id'])) {
    $id = (int) $_POST['produto_id'];
    if (isset($produtos[$id])) {
        if (!isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id] = 0;
        }
        $_SESSION['carrinho'][$id]++;
    }
    header("Location: index.php#produtos");
    exit;
}

$login_usuario = $_SESSION['usuario_login'] ?? '';

if (isset($_SESSION['usuario_logado']) && isset($_POST['acao_favorito']) && isset($_POST['produto_id'])) {
    $id_fav = (int) $_POST['produto_id'];
    if (isset($produtos[$id_fav]) && $login_usuario !== '') {
        $check = banco($server, $user, $password, $db, "SELECT id FROM favoritos WHERE usuario_login = '$login_usuario' AND produto_id = $id_fav");
        if ($check && $check->num_rows > 0) {
            banco($server, $user, $password, $db, "DELETE FROM favoritos WHERE usuario_login = '$login_usuario' AND produto_id = $id_fav");
        } else {
            banco($server, $user, $password, $db, "INSERT INTO favoritos (usuario_login, produto_id) VALUES ('$login_usuario', $id_fav)");
        }
    }
    header("Location: index.php#produtos");
    exit;
}

$favoritos_usuario = [];
if (isset($_SESSION['usuario_logado']) && $login_usuario !== '') {
    $res_fav = banco($server, $user, $password, $db, "SELECT produto_id FROM favoritos WHERE usuario_login = '$login_usuario'");
    if ($res_fav && $res_fav->num_rows > 0) {
        while ($row_fav = $res_fav->fetch_assoc()) {
            $favoritos_usuario[] = (int) $row_fav['produto_id'];
        }
    }
}

$qtd_carrinho = 0;
foreach ($_SESSION['carrinho'] as $qtd_item) {
    $qtd_carrinho += (int) $qtd_item;
}

$categorias_exibir = $categorias_loja ?? ['Coleção de Natal', 'Terços', 'Bíblias', 'Imagens'];

$ids_categorias = [
    'Coleção de Natal' => 'natal',
    'Terços'           => 'tercos',
    'Bíblias'          => 'biblias',
    'Imagens'          => 'imagens'
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
                <a href="conta.php" class="usuario-nav"> <?= $_SESSION['usuario_nome'] ?? 'Usuário' ?></a>
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


    <?php foreach ($categorias_exibir as $categoria_atual): ?>
        <?php
        $produtos_categoria = [];
        foreach ($produtos as $id => $prod) {
            if (($prod['categoria'] ?? '') === $categoria_atual) {
                $produtos_categoria[$id] = $prod;
            }
        }
        ?>

        <?php if (!empty($produtos_categoria)): ?>
            <?php $id_secao = $ids_categorias[$categoria_atual] ?? ''; ?>
            <section <?= $id_secao !== '' ? 'id="' . $id_secao . '"' : '' ?> class="bloco-categoria">
                <h3 class="titulo-categoria"><?= $categoria_atual ?></h3>

                <div class="grid-produtos">
                    <?php foreach ($produtos_categoria as $id => $prod): ?>
                        <?php
                        $eh_favorito = in_array($id, $favoritos_usuario);
                        $img_coracao = $eh_favorito ? 'img/coracao-cheio.png' : 'img/coracao-vazio.png';
                        ?>
                        <div class="card-produto">
                            <div class="btn-favorito-topo">
                                <?php if (isset($_SESSION['usuario_logado'])): ?>
                                    <form action="index.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="produto_id" value="<?= $id ?>">
                                        <button type="submit" name="acao_favorito" value="1" class="btn-coracao" title="Favoritar">
                                            <img src="<?= $img_coracao ?>" alt="Favorito">
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="login.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="produto_id" value="<?= $id ?>">
                                        <button type="submit" class="btn-coracao" title="Faça login para favoritar">
                                            <img src="img/coracao-vazio.png" alt="Favorito">
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <div>
                                <img src="<?= $prod['imagem'] ?>" alt="<?= $prod['nome'] ?>">
                                <h3><?= $prod['nome'] ?></h3>
                                <p><?= $prod['descricao'] ?></p>
                            </div>
                            <div>
                                <div class="preco">R$ <?= number_format($prod['preco'], 2, ',', '.') ?></div>
                                
                                <?php if (isset($_SESSION['usuario_logado'])): ?>
                                    <form action="index.php" method="POST">
                                        <input type="hidden" name="produto_id" value="<?= $id ?>">
                                        <button type="submit" name="acao_compra" value="adicionar" class="btn">Adicionar ao Carrinho</button>
                                    </form>
                                    <form action="carrinho.php" method="POST" style="margin-top: 8px;">
                                        <input type="hidden" name="produto_id" value="<?= $id ?>">
                                        <button type="submit" name="acao_compra" value="comprar_agora" class="btn">Comprar Agora</button>
                                    </form>
                                <?php else: ?>
                                    <form action="login.php" method="POST">
                                        <input type="hidden" name="produto_id" value="<?= $id ?>">
                                        <button type="submit" name="acao_compra" value="adicionar" class="btn">Adicionar ao Carrinho</button>
                                        <button type="submit" name="acao_compra" value="comprar_agora" class="btn" style="margin-top: 8px;">Comprar Agora</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
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

var dropdownCat = document.getElementById('dropdownCategorias');
var btnCat = document.getElementById('btnCategorias');

if (dropdownCat && btnCat) {
    btnCat.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownCat.classList.toggle('aberto');
    });

    document.addEventListener('click', function(e) {
        if (!dropdownCat.contains(e.target)) {
            dropdownCat.classList.remove('aberto');
        }
    });

    var linksCat = dropdownCat.querySelectorAll('.menu-categorias a');
    for (var i = 0; i < linksCat.length; i++) {
        linksCat[i].addEventListener('click', function() {
            dropdownCat.classList.remove('aberto');
        });
    }
}
</script>

</body>
</html>