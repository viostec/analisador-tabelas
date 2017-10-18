<?php

namespace Vios\Devops\AnalisadorTabelas;

use Vios\Devops\PDO;

class IdentificaColunasSemDados
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function analisa(string $tabela) : SinteseAnalise
    {
        $rows = $this->pdo->query( "describe " . $tabela );
        $sql = $this->montaSQLParaAnalisarColunasDaTabela($rows, $tabela);

        $rows = $this->formata($this->pdo->query($sql));
        
        $sintese = new SinteseAnalise();

        foreach($rows as $row) {
            if($row['registros_com_dado'] === '0' or $row['valores_diferentes'] === '1') {
                $sintese->addColunaSemDados($row);
            } else {
                $sintese->addColunaComDados($row);
            }
        }

        return $sintese;
    }

    private function formata($rows)
    {
        $newRows = [];
        foreach ($rows as $row) {
            $row['coluna'] = utf8_encode($row['coluna']);
            $row['min_value'] = utf8_encode($row['min_value']);
            $row['max_value'] = utf8_encode($row['max_value']);

            unset($row[0]);
            unset($row[1]);
            unset($row[2]);
            unset($row[3]);
            unset($row[4]);

            $newRows[] = $row;
        }

        usort($newRows, function($a, $b) {
            return $a['coluna'] <=> $b['coluna'];
        });

        return $newRows;
    }
    
    private function montaSQLParaAnalisarColunasDaTabela($rows, $table)
    {
        $sqls = [];
        foreach($rows as $row) {
            $column = $row['Field'];
            $sqls[] = "SELECT  
              '{$column}' as 'coluna', 
              sum(if(`{$column}` != '', 1, 0)) as 'registros_com_dado',
              count(distinct `{$column}`) as 'valores_diferentes',
              substring(min(`{$column}`), 1, 10) as 'min_value',
              substring(max(`{$column}`), 1, 10) as 'max_value'
          FROM {$table}";
        }

        return implode(" union ", $sqls);
    }
}