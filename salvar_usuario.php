<?php
session_start();
include "app/cons.php";
require_once "app/DLL.php";

extract($_POST);

if (isset($produto_id) && !empty($produto_id)) {
    $_SESSION['produto_id_temp'] = $produto_id;
}

if ($cpf == "" || $nome == "") { 
    header('Location: cadastro1.php'); 
    exit; 
}

$cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);

$consulta = "SELECT * FROM usuarios WHERE cpf = '$cpf_limpo'";
$resultado = banco($server, $user, $password, $db, $consulta);

if ($resultado->num_rows > 0) { 
    header('Location: cadastro1.php'); 
    exit; 
}

$consulta = "INSERT INTO usuarios (cpf, nome, endereco, bairro, cidade, estado, cep) VALUES ('$cpf_limpo', '$nome', '$endereco', '$bairro', '$cidade', '$estado', '$cep')";
banco($server, $user, $password, $db, $consulta);

$_SESSION['cpf_temp'] = $cpf_limpo;

header('Location: cadastro2.php'); 
exit;