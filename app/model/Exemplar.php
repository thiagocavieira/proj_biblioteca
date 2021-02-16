<?php

class Exemplar extends TRecord
{
    const TABLENAME  = 'exemplar';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $livro;
    private $status;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('livro_id');
        parent::addAttribute('status_id');
        parent::addAttribute('codigo_barras');
        parent::addAttribute('dt_aqusicao');
        parent::addAttribute('preco_custo');
        parent::addAttribute('obs');
            
    }

    /**
     * Method set_livro
     * Sample of usage: $var->livro = $object;
     * @param $object Instance of Livro
     */
    public function set_livro(Livro $object)
    {
        $this->livro = $object;
        $this->livro_id = $object->id;
    }

    /**
     * Method get_livro
     * Sample of usage: $var->livro->attribute;
     * @returns Livro instance
     */
    public function get_livro()
    {
    
        // loads the associated object
        if (empty($this->livro))
            $this->livro = new Livro($this->livro_id);
    
        // returns the associated object
        return $this->livro;
    }
    /**
     * Method set_status
     * Sample of usage: $var->status = $object;
     * @param $object Instance of Status
     */
    public function set_status(Status $object)
    {
        $this->status = $object;
        $this->status_id = $object->id;
    }

    /**
     * Method get_status
     * Sample of usage: $var->status->attribute;
     * @returns Status instance
     */
    public function get_status()
    {
    
        // loads the associated object
        if (empty($this->status))
            $this->status = new Status($this->status_id);
    
        // returns the associated object
        return $this->status;
    }

    /**
     * Method getEmprestimos
     */
    public function getEmprestimos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('exemplar_id', '=', $this->id));
        return Emprestimo::getObjects( $criteria );
    }

    
}

