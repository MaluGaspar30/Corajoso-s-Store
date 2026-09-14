<?php
session_start();
include "app/cons.php";
require_once "app/DLL.php";

if (!isset($_SESSION['cpf_temp'])) {
    header('Location: cadastro1.php');
    exit;
}

extract($_POST);

$login = trim($login ?? '');
$senha = trim($senha ?? '');

$cpf_temp = preg_replace('/[^0-9]/', '', $_SESSION['cpf_temp']);

if ($login == "" || $senha == "") {
    header('Location: cadastro2.php');
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$consulta = "UPDATE usuarios SET login = '$login', senha = '$senha_hash' WHERE cpf = '$cpf_temp'";
banco($server, $user, $password, $db, $consulta);

$consulta_user = "SELECT * FROM usuarios WHERE cpf = '$cpf_temp'";
$resultado_user = banco($server, $user, $password, $db, $consulta_user);

if ($resultado_user && $resultado_user->num_rows > 0) {
    $usuario = $resultado_user->fetch_assoc();

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
}


$p_id = $produto_id ?? $_SESSION['produto_id_temp'] ?? null;

unset($_SESSION['cpf_temp']);
unset($_SESSION['produto_id_temp']);

if ($p_id) {
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }
    if (isset($_SESSION['carrinho'][$p_id])) {
        $_SESSION['carrinho'][$p_id]++;
    } else {
        $_SESSION['carrinho'][$p_id] = 1;
    }

    header('Location: carrinho.php');
    exit;
}

$_SESSION['sucesso_login'] = "Cadastro finalizado com sucesso!";
header('Location: index.php');
exit;
?>