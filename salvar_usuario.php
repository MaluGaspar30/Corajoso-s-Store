<?php
session_start();
include "app/cons.php";
require_once "app/DLL.php";

extract($_POST);

if (isset($produto_id) && !empty($produto_id)) {
    $_SESSION['produto_id_temp'] = $produto_id;
}

$nome  = trim($nome ?? '');
$email = trim($email ?? '');
$cpf   = trim($cpf ?? '');

if ($cpf == "" || $nome == "" || $email == "") { 
    $_SESSION['erro_cadastro'] = "Por favor, preencha todos os campos obrigatórios.";
    header('Location: cadastro1.php'); 
    exit; 
}

$cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);

$consulta = "SELECT * FROM usuarios WHERE cpf = '$cpf_limpo'";
$resultado = banco($server, $user, $password, $db, $consulta);

if ($resultado && $resultado->num_rows > 0) { 
    $_SESSION['erro_cadastro'] = "Já existe um cadastro com este CPF. Faça login ou utilize outro CPF.";
    header('Location: cadastro1.php'); 
    exit; 
}

$consulta = "INSERT INTO usuarios (cpf, nome, email, endereco, bairro, cidade, estado, cep) VALUES ('$cpf_limpo', '$nome', '$email', '$endereco', '$bairro', '$cidade', '$estado', '$cep')";
banco($server, $user, $password, $db, $consulta);

$_SESSION['cpf_temp'] = $cpf_limpo;

header('Location: cadastro2.php'); 
exit;