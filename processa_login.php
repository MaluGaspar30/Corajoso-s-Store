<?php
session_start();
include "app/cons.php";
require_once "app/DLL.php";

extract($_POST);

$login = trim($login ?? '');
$senha = trim($senha ?? '');
$produto_id = isset($produto_id) && !empty($produto_id) ? (int)$produto_id : null;

if ($login == "" || $senha == "") {
    $_SESSION['erro_login'] = "Preencha todos os campos!";
    if ($produto_id) {
        $_SESSION['produto_id_temp'] = $produto_id;
    }
    header('Location: login.php');
    exit;
}

$consulta = "SELECT * FROM usuarios WHERE login = '$login'";
$resultado = banco($server, $user, $password, $db, $consulta);

if ($resultado && $resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    
    if (password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario_id']     = $usuario['id_usuario'] ?? $usuario['id'];
        $_SESSION['usuario_nome']   = $usuario['nome'];
        $_SESSION['usuario_login']  = $usuario['login'];

        $_SESSION['dados_usuario'] = [
            'nome'     => $usuario['nome'],
            'cpf'      => $usuario['cpf'],
            'endereco' => $usuario['endereco'],
            'bairro'   => $usuario['bairro'],
            'cidade'   => $usuario['cidade'],
            'estado'   => $usuario['estado'],
            'cep'      => $usuario['cep']
        ];

        if ($produto_id > 0) {
            if (!isset($_SESSION['carrinho'])) {
                $_SESSION['carrinho'] = [];
            }
            if (isset($_SESSION['carrinho'][$produto_id])) {
                $_SESSION['carrinho'][$produto_id]++;
            } else {
                $_SESSION['carrinho'][$produto_id] = 1;
            }
            header('Location: carrinho.php');
            exit;
        }

        header('Location: index.php');
        exit;
    }
}

$_SESSION['erro_login'] = "Login ou senha incorretos!";

if ($produto_id) {
    $_SESSION['produto_id_temp'] = $produto_id;
}

header('Location: login.php');
exit;
?>