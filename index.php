<?php
require_once './class/retornaBancos.php';


$bancos = new retornaBancos();
$listaBancos = $bancos->getBancos();

?>

<div>
    <h1>Back up de banco de dados</h1>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>Banco de dados</th>
                <th>Tamanho</th>
                <th>Exportacao</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaBancos as $banco) { ?>
                <tr>
                    <td>
                        <?= $banco['Database'] ?>
                    </td>
                    <td>
                        <?= $banco['size'] ?>
                    </td>
                    <td>
                        <button onclick="exportarEstrutura('<?= $banco['Database'] ?>')"> estrutura</button>
                        <button onclick="exportarCompleto('<?= $banco['Database'] ?>')"> completo</button>
                        <button class="d-none" id="path-<?= $banco['Database'] ?>"> </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div> <button onclick="exportarAll()"> Todos</button></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>