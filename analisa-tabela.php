<?php

require_once __DIR__ . "/vendor/autoload.php";


$cli = new League\CLImate\CLImate();

$cli->arguments->add([
    "table" => [
        'prefix'      => 't',
        'longPrefix'  => 'table',
        'description' => 'table',
        'required'    => true,
    ]
]);
$cli->arguments->parse();
$table = $cli->arguments->get('table');

$config = require_once(__DIR__ . "/config.php");

$pdo = new \Vios\Devops\PDO($config['database'], $config['password'], $config['user'], $config['host']);
$analisador = new \Vios\Devops\AnalisadorTabelas\IdentificaColunasSemDados($pdo);
$sintese = $analisador->analisa($table);

$cli->flank("Colunas sem dados significativos");
$cli->columns($sintese->getColunasSemDadosSomenteNome(), 2);

$cli->flank("Colunas para Analisar");
$cli->table($sintese->getColunasComDados());