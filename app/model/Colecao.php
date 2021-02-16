<?php

class Colecao extends TRecord
{
    const TABLENAME  = 'colecao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getLivros
     */
    public function getLivros()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('colecao_id', '=', $this->id));
        return Livro::getObjects( $criteria );
    }

    
}

