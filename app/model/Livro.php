<?php

class Livro extends TRecord
{
    const TABLENAME  = 'livro';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $autor_principal;
    private $editora;
    private $colecao;
    private $classificacao;

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('editora_id');
        parent::addAttribute('colecao_id');
        parent::addAttribute('classificacao_id');
        parent::addAttribute('autor_principal_id');
        parent::addAttribute('titulo');
        parent::addAttribute('numero');
        parent::addAttribute('isbn');
        parent::addAttribute('edicao');
        parent::addAttribute('volume');
        parent::addAttribute('dt_publicacao');
        parent::addAttribute('local_publicacao');
        parent::addAttribute('obs');
            
    }

    /**
     * Method set_autor
     * Sample of usage: $var->autor = $object;
     * @param $object Instance of Autor
     */
    public function set_autor_principal(Autor $object)
    {
        $this->autor_principal = $object;
        $this->autor_principal_id = $object->id;
    }

    /**
     * Method get_autor_principal
     * Sample of usage: $var->autor_principal->attribute;
     * @returns Autor instance
     */
    public function get_autor_principal()
    {
    
        // loads the associated object
        if (empty($this->autor_principal))
            $this->autor_principal = new Autor($this->autor_principal_id);
    
        // returns the associated object
        return $this->autor_principal;
    }
    /**
     * Method set_editora
     * Sample of usage: $var->editora = $object;
     * @param $object Instance of Editora
     */
    public function set_editora(Editora $object)
    {
        $this->editora = $object;
        $this->editora_id = $object->id;
    }

    /**
     * Method get_editora
     * Sample of usage: $var->editora->attribute;
     * @returns Editora instance
     */
    public function get_editora()
    {
    
        // loads the associated object
        if (empty($this->editora))
            $this->editora = new Editora($this->editora_id);
    
        // returns the associated object
        return $this->editora;
    }
    /**
     * Method set_colecao
     * Sample of usage: $var->colecao = $object;
     * @param $object Instance of Colecao
     */
    public function set_colecao(Colecao $object)
    {
        $this->colecao = $object;
        $this->colecao_id = $object->id;
    }

    /**
     * Method get_colecao
     * Sample of usage: $var->colecao->attribute;
     * @returns Colecao instance
     */
    public function get_colecao()
    {
    
        // loads the associated object
        if (empty($this->colecao))
            $this->colecao = new Colecao($this->colecao_id);
    
        // returns the associated object
        return $this->colecao;
    }
    /**
     * Method set_classificacao
     * Sample of usage: $var->classificacao = $object;
     * @param $object Instance of Classificacao
     */
    public function set_classificacao(Classificacao $object)
    {
        $this->classificacao = $object;
        $this->classificacao_id = $object->id;
    }

    /**
     * Method get_classificacao
     * Sample of usage: $var->classificacao->attribute;
     * @returns Classificacao instance
     */
    public function get_classificacao()
    {
    
        // loads the associated object
        if (empty($this->classificacao))
            $this->classificacao = new Classificacao($this->classificacao_id);
    
        // returns the associated object
        return $this->classificacao;
    }

    /**
     * Method getExemplars
     */
    public function getExemplars()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('livro_id', '=', $this->id));
        return Exemplar::getObjects( $criteria );
    }
    /**
     * Method getLivroAssuntos
     */
    public function getLivroAssuntos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('livro_id', '=', $this->id));
        return LivroAssunto::getObjects( $criteria );
    }
    /**
     * Method getLivroAutors
     */
    public function getLivroAutors()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('livro_id', '=', $this->id));
        return LivroAutor::getObjects( $criteria );
    }

    
}

