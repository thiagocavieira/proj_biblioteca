<?php

class Emprestimo extends TRecord
{
    const TABLENAME  = 'emprestimo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $exemplar;
    private $leitor;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('exemplar_id');
        parent::addAttribute('leitor_id');
        parent::addAttribute('dt_emprestimo');
        parent::addAttribute('dt_previsao');
        parent::addAttribute('dt_devolucao');
        parent::addAttribute('valor_multa');
            
    }

    /**
     * Method set_exemplar
     * Sample of usage: $var->exemplar = $object;
     * @param $object Instance of Exemplar
     */
    public function set_exemplar(Exemplar $object)
    {
        $this->exemplar = $object;
        $this->exemplar_id = $object->id;
    }

    /**
     * Method get_exemplar
     * Sample of usage: $var->exemplar->attribute;
     * @returns Exemplar instance
     */
    public function get_exemplar()
    {
    
        // loads the associated object
        if (empty($this->exemplar))
            $this->exemplar = new Exemplar($this->exemplar_id);
    
        // returns the associated object
        return $this->exemplar;
    }
    /**
     * Method set_leitor
     * Sample of usage: $var->leitor = $object;
     * @param $object Instance of Leitor
     */
    public function set_leitor(Leitor $object)
    {
        $this->leitor = $object;
        $this->leitor_id = $object->id;
    }

    /**
     * Method get_leitor
     * Sample of usage: $var->leitor->attribute;
     * @returns Leitor instance
     */
    public function get_leitor()
    {
    
        // loads the associated object
        if (empty($this->leitor))
            $this->leitor = new Leitor($this->leitor_id);
    
        // returns the associated object
        return $this->leitor;
    }

    
}

