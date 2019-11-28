<?php

/**
 * SpreadsheetManagerInterface
 * 
 * Interface responsável por fornecer métodos comuns a 
 * diferentes classes para gerenciamento de planilhas eletrônicas
 * @author Weydans barros
 * Data de criação: 28/11/2019
 */

interface SpreadsheetManagerInterface
{
    public function processFile(string $filePath) : SpreadsheetManagerInterface;

    public function getHeader() : array;

    public function get() : array;

    public function getObject() : array;

    public function getSeparator() : string;

    public function getError() : array;

    public function addSeparator(string $separator) : SpreadsheetManagerInterface;

    public function setSeparator(string $separator = null) : SpreadsheetManagerInterface;

    public function setNumValidationSeparator(int $numValidations) : SpreadsheetManagerInterface;
}
