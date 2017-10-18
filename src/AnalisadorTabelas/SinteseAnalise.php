<?php

namespace Vios\Devops\AnalisadorTabelas;

class SinteseAnalise
{
    private $colunasSemDados;

    private $colunasComDados;

    /**
     * @param $colunasSemDados
     * @param $colunasComDados
     */
    public function __construct($colunasSemDados = [], $colunasComDados = [])
    {
        $this->colunasSemDados = $colunasSemDados;
        $this->colunasComDados = $colunasComDados;
    }

    public function addColunaComDados(array $row)
    {
        $this->colunasComDados[] = $row;
    }

    public function addColunaSemDados(array $row)
    {
        $this->colunasSemDados[] = $row;
    }

    public function getColunasSemDados()
    {
        return $this->colunasSemDados;
    }

    public function getColunasSemDadosSomenteNome()
    {
        return array_map(function($item) { return $item['coluna']; }, $this->colunasSemDados);
    }

    public function getColunasComDados()
    {
        return $this->colunasComDados;
    }
}