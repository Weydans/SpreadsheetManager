<?php

/**
 * SpreadsheetManagerFactory
 * 
 * Responsável por criar instâncias de classes que implementem 
 * a interface SpreadsheetManagerInterface utilizando padrão Factory Method
 * @author Weydans Barros
 * Data de criação: 28/11/2019
 */

class SpreadsheetManagerFactory
{
    /**
     * getInstance()
     * 
     * Identificaa intância de classe que deve ser criada e retorna objeto do tipo SpreadsheetManagerInterface
     * @param string $extension Extensão do arquivo a ser manipulado
     * @return SpreadsheetManagerInterface Objeto que manipula planilhas eletrônicas
     */
    public static function getInstance(string $extension) : SpreadsheetManagerInterface
    {
        if ($extension == '.csv') {
            return new CSVManager;
        // } elseif ($extension == '.xlsx') {
        //     return new XLSXManager;
        } else {
            throw new Exception('A extensão <b>' . $extension . '</b> é inválida.');
        }
    }
}