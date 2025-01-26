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
    
    // Comando para gerar o dump, com a opção --verbose para mais detalhes
    $dumpCommand = "C:/xampp/mysql/bin/mysqldump -u root --verbose $database";
    $logFile = $backupDir . $database . "_" . $date . "_dump.log";  // Log de saída

    // Captura tanto a saída padrão quanto os erros
    $dumpOutput = shell_exec($dumpCommand . " > $backupFile 2>> $logFile");

    // Verifica se houve falha na execução do comando mysqldump
    if ($dumpOutput === null) {
        echo "Erro ao executar o comando de dump para o banco de dados '$database'.\n";
        echo "Verifique a configuração e permissões.\n";
        return;
    }
    
    echo "Comando de Dump Executado: " . $dumpCommand . "\n";

    // Verifica o conteúdo do log
    $logContents = file_get_contents($logFile);
    if (!empty($logContents)) {
        echo "Erros encontrados durante o dump:\n";
        echo $logContents . "\n";
    } else {
        echo "Dump gerado com sucesso para o banco '$database'.\n";
    }

    // Comando para compactar o arquivo .sql em .zip
    $zipFile = $backupDir . $database . "_" . $date . ".zip";
    $zipCommand = "zip $zipFile $backupFile";
    echo "Comando de Compactação: " . $zipCommand . "\n"; // Depuração
    shell_exec($zipCommand);

    if (file_exists($zipFile)) {
        echo "Arquivo compactado com sucesso: $zipFile\n";
    } else {
        echo "Erro ao compactar o arquivo.\n";
    }

    // Gera o checksum SHA-1
    $checksumFile = $backupDir . $database . "_" . $date . ".sha1";
    $sha1Hash = sha1_file($zipFile);
    file_put_contents($checksumFile, $sha1Hash);

    // Exclui o arquivo .sql não compactado
    unlink($backupFile);

    echo "Backup do banco de dados '$database' concluído.\n";
}

// Executa o comando SHOW DATABASES
$databasesRaw = execMysqlCommand('SHOW DATABASES;');
echo "Resposta do comando SHOW DATABASES:\n";
echo $databasesRaw . "\n";  

// Processa os bancos de dados
$databases = explode("\n", $databasesRaw);
array_shift($databases); 
$databases = array_filter($databases, function($db) {
    $db = trim($db); 
    return !empty($db) && !in_array($db, ['information_schema', 'performance_schema', 'sys', 'mysql', 'phpmyadmin']);
});

echo "Bancos de dados encontrados:\n";
print_r($databases);

// Realiza o dump para cada banco de dados encontrado
foreach ($databases as $database) {
    dumpDatabase(trim($database));
    echo "Backup do banco de dados '$database' concluído.\n";
}

echo "Todos os backups foram concluídos com sucesso.\n";
?>
