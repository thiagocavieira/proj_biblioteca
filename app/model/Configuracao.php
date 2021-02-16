<?php

class Configuracao extends TRecord
{
    const TABLENAME  = 'configuracao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const multa = '1';
    const dias_emprestimo = '2';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('chave');
        parent::addAttribute('valor');
            
    }

    
}

