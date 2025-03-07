<?php 
require_once 'globals.php';

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'estrutura') {
        
        echo exportExtrutura();
    }else  if ($_POST['action'] === 'completo') {
        
        echo exportCompleto();
    }
   
} else {
    echo 0;
}

function exportExtrutura() {
    global $usuario, $senha, $host; 

    $nome_db = $_POST['db_name'];

    $mysqldump = 'C:\laragon\bin\mysql\mariadb-10.4.27-winx64\bin\mysqldump.exe';

    $arquivo_saida = "C:/testeback-up/backup_{$nome_db}_estrutura.sql";

    $comando = "{$mysqldump} -u {$usuario} " . ($senha ? "-p{$senha} " : "") . "--host={$host} --no-data {$nome_db} > {$arquivo_saida}";


    exec($comando, $output, $return_var);

    // echo "Código de retorno: $return_var<br>";
    // echo "Saída do comando:<br>";
    // print_r($output);

    if ($return_var !== 0) {
        $erro = error_get_last();
        echo "Erro do sistema: " . print_r($erro, true);
    } else {
       
        return $arquivo_saida; 
    }
}

function exportCompleto() {
    global $usuario, $senha, $host; 

    $nome_db = $_POST['db_name'];

    $mysqldump = 'C:\laragon\bin\mysql\mariadb-10.4.27-winx64\bin\mysqldump.exe';

    // Nome do arquivo com data e hora para evitar sobreposição
    $dataHora = date("Y-m-d_H-i-s");
    $arquivo_saida = "C:/testeback-up/backup_{$nome_db}_{$dataHora}.sql";

    // Comando para exportar toda a estrutura + dados, incluindo rotinas e eventos
    $comando = "{$mysqldump} -u {$usuario} " . ($senha ? "-p{$senha} " : "") . 
               "--host={$host} --routines --events --single-transaction {$nome_db} > \"{$arquivo_saida}\"";

    exec($comando, $output, $return_var);

    if ($return_var !== 0) {
        $erro = error_get_last();
        echo "Erro ao exportar o banco de dados: " . print_r($erro, true);
        return false;
    } else {
        return $arquivo_saida;
    }
}