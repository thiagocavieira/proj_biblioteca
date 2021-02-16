<?php

class Status extends TRecord
{
    const TABLENAME  = 'status';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    const disponivel = '1';
    const emprestado = '2';
    const perdido = '3';

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
            
    }

    /**
     * Method getExemplars
     */
    public function getExemplars()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('status_id', '=', $this->id));
        return Exemplar::getObjects( $criteria );
    }

    
}

