<?php

class Autor extends TRecord
{
    const TABLENAME  = 'autor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('codigo');
        parent::addAttribute('num_classificacao');
        parent::addAttribute('num');
            
    }

    /**
     * Method getLivros
     */
    public function getLivros()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('autor_principal_id', '=', $this->id));
        return Livro::getObjects( $criteria );
    }
    /**
     * Method getLivroAutors
     */
    public function getLivroAutors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('autor_id', '=', $this->id));
        return LivroAutor::getObjects( $criteria );
    }

    
}

