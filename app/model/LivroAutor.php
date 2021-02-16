<?php

class LivroAutor extends TRecord
{
    const TABLENAME  = 'livro_autor';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $autor;
    private $livro;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('autor_id');
        parent::addAttribute('livro_id');
            
    }

    /**
     * Method set_autor
     * Sample of usage: $var->autor = $object;
     * @param $object Instance of Autor
     */
    public function set_autor(Autor $object)
    {
        $this->autor = $object;
        $this->autor_id = $object->id;
    }

    /**
     * Method get_autor
     * Sample of usage: $var->autor->attribute;
     * @returns Autor instance
     */
    public function get_autor()
    {
    
        // loads the associated object
        if (empty($this->autor))
            $this->autor = new Autor($this->autor_id);
    
        // returns the associated object
        return $this->autor;
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

