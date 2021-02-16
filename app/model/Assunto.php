<?php

class Assunto extends TRecord
{
    const TABLENAME  = 'assunto';
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
     * Method getLivroAssuntos
     */
    public function getLivroAssuntos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('assunto_id', '=', $this->id));
        return LivroAssunto::getObjects( $criteria );
    }

    
}

