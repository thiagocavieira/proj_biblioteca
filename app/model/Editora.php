<?php

class Editora extends TRecord
{
    const TABLENAME  = 'editora';
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
        $criteria->add(new TFilter('editora_id', '=', $this->id));
        return Livro::getObjects( $criteria );
    }

    
}

