<?php

class LivroAssunto extends TRecord
{
    const TABLENAME  = 'livro_assunto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $assunto;
    private $livro;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('assunto_id');
        parent::addAttribute('livro_id');
            
    }

    /**
     * Method set_assunto
     * Sample of usage: $var->assunto = $object;
     * @param $object Instance of Assunto
     */
    public function set_assunto(Assunto $object)
    {
        $this->assunto = $object;
        $this->assunto_id = $object->id;
    }

    /**
     * Method get_assunto
     * Sample of usage: $var->assunto->attribute;
     * @returns Assunto instance
     */
    public function get_assunto()
    {
    
        // loads the associated object
        if (empty($this->assunto))
            $this->assunto = new Assunto($this->assunto_id);
    
        // returns the associated object
        return $this->assunto;
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

    
}

