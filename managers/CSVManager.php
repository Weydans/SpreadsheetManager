<?php

/**
 * CSVManager
 *
 * Classe responsável pela gestão de arquivos CSV
 * @author Weydans Barros
 * Data criação: 27/11/2019
 */

 class CSVManager implements SpreadsheetManagerInterface
 { 
    /**
    * Erros ocorridos durante o processamento
    */
    private $error = [
        'error'   => false,
        'message' => ''
    ];


    /**
     * Separador utilizado 
     */
    private $separator = null;
     

    /**
     * Numero de linhas do arquivo que serão utilizadas para validar o separador
    */
    private $numValidationSeparator = 10;


    /**
     * Separadores permitidos
     */
    private $defaultSeparators = [';', ',', '\t', ' '];


    /**
     * Cabecalho do arquivo
     */
   private $header = [];


   /**
    * Conteúdo do arquivo 
    */
   private $content = [];


    /**
     * Arquivo enviado
     */
    private $file;
 
 
    /**
     * processFile()
     *
     * Processa arquivos CSV decompondo-o em partes para posterior manipulação
     * @param array $filePath Local do arquivo 
     */
    public function processFile(string $filePath) : SpreadsheetManagerInterface
    {
        // Se arquivo estiver sendo recebido via formulário
        if (file_exists($filePath) && !is_dir($filePath))  {
            $this->file = file($filePath);
        }
        
        return $this;
    }


    /**
     * getHeader()
     *
     * Obtem o header do arquivo através de processamento interno
     */
    public function getHeader() : array
    {       
        $this->setSeparator($this->separator);
        $this->setHeader(); 

        return $this->header;
    }


    /**
     * getcontent()
     *
     * Obtem o conteúdo do arquivo através de processamento interno
     */
    public function get() : array
    {        
        $this->setSeparator($this->separator);        
        $this->setHeader();
        $this->setContent();

        return $this->content;
    }
        

    /**
     * getObject()
     *
     * Transforma os dados em um array de objetos
     * @return array Dados
     */
    public function getObject() : array
    {        
        $this->setSeparator($this->separator);
        $this->setHeader();
        $this->setContent(true);
        
        return $this->content;
    }
    

    /**
     * getSeparator()
     *
     * Identifica o separador utilizado no arquivo
     * @return string Separador 
     */
    public function getSeparator() : string
    {
        $this->setSeparator($this->separator);
        $this->setHeader();

        return $this->separator;
    }
    

    /**
     * getError()
     * 
     * Retorna relatório de erros durante processamento
     * @return array Erros do processamento
     */
    public function getError() : array
    {
        return $this->error;
    }


    /**
     * addSeparator()
     *
     * Adiciona separador à lista de separadores padrão
     * @param string $separator Separador
     */
    public function addSeparator(string $separator) : SpreadsheetManagerInterface
    {
        $this->defaultSeparators[] = $separator;

        return $this;
    }


    /**
     * setSeparator()
     * 
     * Configura o separador padrão como obrigatório
     * @param string $separator Separador padrão
     */
    public function setSeparator(string $separator = null) : SpreadsheetManagerInterface
    {
        if (!empty($separator)) {
            $this->separator = $separator;
            
        } else {
            $this->findSeparator();
        }

        return $this;
    }


    /**
     * setNumValidationSeparator()
     * 
     * Configura o número mínimo de linhas utilizadas para validar o separador
     * @param int $numValidations núero de validações
     */
    public function setNumValidationSeparator(int $numValidations) : SpreadsheetManagerInterface
    {
        $this->numValidationSeparator = $numValidations;

        return $this;
    }


    /**
     * findSeparator()
     * 
     * Encontra o separador padrão dentre os 
     * separadores presentes no atributo $defaultSeparators
     */
    private function findSeparator()
    {
        if (count($this->file) > 1) {
            
            foreach ($this->defaultSeparators as $value) {                
                $found         = true;
                $numOccurrence = substr_count($this->file[0], $value);
                
                for ($i = 0; ($i < $this->numValidationSeparator) && ($i < count($this->file)); $i++) {                    
                    if (substr_count($this->file[$i], $value) == 0 || substr_count($this->file[$i], $value) != $numOccurrence) {
                        $found = false;
                    }
                }
                
                if ($found) {
                    $this->separator = $value;
                    return;

                } else {
                    $this->error['error'] = true;
                    $this->error['message'] = 'Separador não encontrado ou inválido.';
                }
            }
        } else {
            $this->error['error'] = true;
            $this->error['message'] = 'Arquivo contém menos de duas linhas.';
        }
    }
    

    /**
     * setHeader()
     * 
     * Obtem o cabeçalho do arquivo formato de array
     */
    private function setHeader()
    {
        if (count($this->file) > 0 && !empty($this->separator)) {
            $this->header = str_getcsv($this->file[0], $this->separator);
        }
    }


    /**
     * setContent()
     * 
     * Obtem o conteúdo do arquivo em formato de matriz de arrays associativos
     */
    private function setContent(bool $asObject = false)
    {
        $aux = [];

        if (count($this->file) > 1) {

            // Obtem conteúdo do arquivo
            for($i = 0; $i < count($this->file); $i++) {
                if ($i > 0) {
                    $aux[] = str_getcsv($this->file[$i], $this->separator);
                }
            }

            // Monta conteúdo com as chaves sendo os dados do cabeçalho
            for ($i = 0; $i < count($aux); $i++) {
                for ($j = 0; $j < count($this->header); $j++) {
                    if (isset($aux[$i][$j])) {
                        $this->content[$i][$this->header[$j]] = $aux[$i][$j];
                    }
                    unset($aux[$i][$j]);
                }

                // Obtem array de objetos
                if ($asObject) {
                    if (isset($this->content[$i])) {
                        $this->content[$i] = (object)$this->content[$i];
                    }
                }                 
            }

            unset($aux);
        }
    }

} 
