<?php

class LivroBatchDrawing extends TPage
{
    private static $database = 'biblioteca';
    private static $activeRecord = 'Livro';
    private static $primaryKey = 'id';
    private static $svgFile = 'app/drawings/LivroDrawingTemplate.svg';
    private static $formName = 'formDrawing_Livro';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Ficha do livro em lote");

        $id = new TEntry('id');
        $editora_id = new TDBUniqueSearch('editora_id', 'biblioteca', 'Editora', 'id', 'nome','nome asc'  );
        $titulo = new TEntry('titulo');
        $autor_principal_id = new TDBUniqueSearch('autor_principal_id', 'biblioteca', 'Autor', 'id', 'nome','nome asc'  );
        $colecao_id = new TDBCombo('colecao_id', 'biblioteca', 'Colecao', 'id', '{nome}','nome asc'  );
        $classificacao_id = new TDBCombo('classificacao_id', 'biblioteca', 'Classificacao', 'id', '{nome}','nome asc'  );

        $editora_id->setMask('{nome}');
        $autor_principal_id->setMask('{nome}');

        $editora_id->setMinLength(1);
        $autor_principal_id->setMinLength(1);

        $id->setSize(100);
        $titulo->setSize('100%');
        $editora_id->setSize('100%');
        $colecao_id->setSize('100%');
        $classificacao_id->setSize('100%');
        $autor_principal_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Editora:", null, '14px', null)],[$editora_id]);
        $row2 = $this->form->addFields([new TLabel("Título:", null, '14px', null)],[$titulo],[new TLabel("Autor principal:", null, '14px', null)],[$autor_principal_id]);
        $row3 = $this->form->addFields([new TLabel("Coleção:", null, '14px', null)],[$colecao_id],[new TLabel("Classificação:", null, '14px', null)],[$classificacao_id]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar", new TAction([$this, 'onGenerate']), 'fas:cog #ffffff');
        $btn_ongenerate->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);

    }

    public function onGenerate($param)
    {
        try 
        {
            TTransaction::open(self::$database);

            $data = $this->form->getData();
            $criteria = new TCriteria();

            if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) ) 
            {

                $criteria->add(new TFilter('id', '=', $data->id));
            }
            if (isset($data->editora_id) AND ( (is_scalar($data->editora_id) AND $data->editora_id !== '') OR (is_array($data->editora_id) AND (!empty($data->editora_id)) )) ) 
            {

                $criteria->add(new TFilter('editora_id', '=', $data->editora_id));
            }
            if (isset($data->titulo) AND ( (is_scalar($data->titulo) AND $data->titulo !== '') OR (is_array($data->titulo) AND (!empty($data->titulo)) )) ) 
            {

                $criteria->add(new TFilter('titulo', 'like', "%{$data->titulo}%"));
            }
            if (isset($data->autor_principal_id) AND ( (is_scalar($data->autor_principal_id) AND $data->autor_principal_id !== '') OR (is_array($data->autor_principal_id) AND (!empty($data->autor_principal_id)) )) ) 
            {

                $criteria->add(new TFilter('autor_principal_id', '=', $data->autor_principal_id));
            }
            if (isset($data->colecao_id) AND ( (is_scalar($data->colecao_id) AND $data->colecao_id !== '') OR (is_array($data->colecao_id) AND (!empty($data->colecao_id)) )) ) 
            {

                $criteria->add(new TFilter('colecao_id', '=', $data->colecao_id));
            }
            if (isset($data->classificacao_id) AND ( (is_scalar($data->classificacao_id) AND $data->classificacao_id !== '') OR (is_array($data->classificacao_id) AND (!empty($data->classificacao_id)) )) ) 
            {

                $criteria->add(new TFilter('classificacao_id', '=', $data->classificacao_id));
            }

            $objects = Livro::getObjects($criteria, FALSE);
            if ($objects)
            {
                $output = '';

                $count = 1;
                $count_records = count($objects);

                foreach ($objects as $object)
                {

                    $html = new AdiantiHTMLDocumentParser();
                    $html->setMaster($object);
                    $html->parseImage(self::$svgFile);
                    $html->process();

                    if ($count < $count_records)
                    {
                        $html->addPageBreak();
                    }

                    $output .= $html->getContents();

                    $count++;
                }

                $document = 'tmp/'.uniqid().'.pdf'; 
                $html = AdiantiHTMLDocumentParser::newFromString($output);
                $html->saveAsPDF($document, [595, 841]);

                parent::openFile($document);
                new TMessage('info', _t('Drawing successfully generated'));
            }
            else
            {
                new TMessage('info', _t('No records found'));   
            }

            TTransaction::close();

            TSession::setValue(__CLASS__.'_filter_data', $data);

            $this->form->setData($data);

        } 
        catch (Exception $e) 
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }
    } 

    public function onShow($param = null)
    {

    }

}

