<?php
include_once "cons.php";
require_once "DLL.php";

$categorias_loja = [
    'Coleção de Natal',
    'Terços',
    'Bíblias',
    'Imagens'
];

$produtos = [];

$res_p = banco($server, $user, $password, $db, "SELECT * FROM produtos");
if ($res_p && $res_p->num_rows > 0) {
    while ($p = $res_p->fetch_assoc()) {
        $id_prod = (int) ($p['id'] ?? 0);
        if ($id_prod > 0) {
            $produtos[$id_prod] = [
                'nome'      => $p['nome'] ?? '',
                'descricao' => $p['descricao'] ?? '',
                'preco'     => (float) ($p['preco'] ?? 0),
                'imagem'    => $p['imagem'] ?? '',
                'categoria' => $p['categoria'] ?? ''
            ];
        }
    }
}