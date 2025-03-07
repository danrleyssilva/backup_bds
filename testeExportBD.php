<?php 
function execMysqlCommand($command) {
    $output = shell_exec("C:/xampp/mysql/bin/mysql -u root -e \"$command\" 2>&1");

    if ($output === null) {
        echo "Erro ao executar o comando MySQL: $command\n";
        return '';
    }

    return $output;
}

function dumpDatabase($database) {
    $backupDir = "C:/backup/"; 
    $date = date('Y-m-d_H-i');
    $backupFile = $backupDir . $database . "_" . $date . ".sql";
    
    
    $dumpCommand = "C:/xampp/mysql/bin/mysqldump -u root --verbose $database";
    $logFile = $backupDir . $database . "_" . $date . "_dump.log";  

   
    $dumpOutput = shell_exec($dumpCommand . " > $backupFile 2>> $logFile");

    
    if ($dumpOutput === null) {
        echo "Erro ao executar o comando de dump para o banco de dados '$database'.\n";
        echo "Verifique a configuração e permissões.\n";
        return;
    }
    
    echo "Comando de Dump Executado: " . $dumpCommand . "\n";

   
    $logContents = file_get_contents($logFile);
    if (!empty($logContents)) {
        echo "Erros encontrados durante o dump:\n";
        echo $logContents . "\n";
    } else {
        echo "Dump gerado com sucesso para o banco '$database'.\n";
    }

    
    $zipFile = $backupDir . $database . "_" . $date . ".zip";
    $zipCommand = "zip $zipFile $backupFile";
    echo "Comando de Compactação: " . $zipCommand . "\n"; 
    shell_exec($zipCommand);

    if (file_exists($zipFile)) {
        echo "Arquivo compactado com sucesso: $zipFile\n";
    } else {
        echo "Erro ao compactar o arquivo.\n";
    }

    $checksumFile = $backupDir . $database . "_" . $date . ".sha1";
    $sha1Hash = sha1_file($zipFile);
    file_put_contents($checksumFile, $sha1Hash);

    unlink($backupFile);

    echo "Backup do banco de dados '$database' concluído.\n";
}

$databasesRaw = execMysqlCommand('SHOW DATABASES;');
echo "Resposta do comando SHOW DATABASES:\n";
echo $databasesRaw . "\n";  

$databases = explode("\n", $databasesRaw);
array_shift($databases); 
$databases = array_filter($databases, function($db) {
    $db = trim($db); 
    return !empty($db) && !in_array($db, ['information_schema', 'performance_schema', 'sys', 'mysql', 'phpmyadmin']);
});

echo "Bancos de dados encontrados:\n";
print_r($databases);

foreach ($databases as $database) {
    dumpDatabase(trim($database));
    echo "Backup do banco de dados '$database' concluído.\n";
}

echo "Todos os backups foram concluídos com sucesso.\n";
?>
