<?php

class LivroReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'biblioteca';
    private static $activeRecord = 'Livro';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Livro';

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Relatório de livros");

        $editora_id = new TDBUniqueSearch('editora_id', 'biblioteca', 'Editora', 'id', 'nome','nome asc'  );
        $colecao_id = new TDBCombo('colecao_id', 'biblioteca', 'Colecao', 'id', '{nome}','nome asc'  );
        $classificacao_id = new TDBCombo('classificacao_id', 'biblioteca', 'Classificacao', 'id', '{nome}','nome asc'  );
        $numero = new TEntry('numero');
        $autor_principal_id = new TDBUniqueSearch('autor_principal_id', 'biblioteca', 'Autor', 'id', 'nome','nome asc'  );
        $titulo = new TEntry('titulo');

        $editora_id->setMask('{nome}');
        $autor_principal_id->setMask('{nome}');

        $editora_id->setMinLength(1);
        $autor_principal_id->setMinLength(1);

        $numero->setSize('100%');
        $titulo->setSize('100%');
        $editora_id->setSize('100%');
        $colecao_id->setSize('100%');
        $classificacao_id->setSize('100%');
        $autor_principal_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Editora:", null, '14px', null)],[$editora_id],[new TLabel("Coleção:", null, '14px', null)],[$colecao_id]);
        $row2 = $this->form->addFields([new TLabel("Classificação:", null, '14px', null)],[$classificacao_id],[new TLabel("Número de chamada:", null, '14px', null)],[$numero]);
        $row3 = $this->form->addFields([new TLabel("Autor principal:", null, '14px', null)],[$autor_principal_id],[new TLabel("Título:", null, '14px', null)],[$titulo]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongeneratehtml = $this->form->addAction("Gerar HTML", new TAction([$this, 'onGenerateHtml']), 'fas:code #ffffff');
        $btn_ongeneratehtml->addStyleClass('btn-primary'); 

        $btn_ongeneratepdf = $this->form->addAction("Gerar PDF", new TAction([$this, 'onGeneratePdf']), 'far:file-pdf #d44734');

        $btn_ongeneratertf = $this->form->addAction("Gerar RTF", new TAction([$this, 'onGenerateRtf']), 'far:file-alt #324bcc');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);

    }

    public function onGenerateHtml($param = null) 
    {
        $this->onGenerate('html');
    }

    public function onGeneratePdf($param = null) 
    {
        $this->onGenerate('pdf');
    }

    public function onGenerateRtf($param = null) 
    {
        $this->onGenerate('rtf');
    }

    /**
     * Register the filter in the session
     */
    public function getFilters()
    {
        // get the search form data
        $data = $this->form->getData();

        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->editora_id) AND ( (is_scalar($data->editora_id) AND $data->editora_id !== '') OR (is_array($data->editora_id) AND (!empty($data->editora_id)) )) )
        {

            $filters[] = new TFilter('editora_id', '=', $data->editora_id);// create the filter 
        }
        if (isset($data->colecao_id) AND ( (is_scalar($data->colecao_id) AND $data->colecao_id !== '') OR (is_array($data->colecao_id) AND (!empty($data->colecao_id)) )) )
        {

            $filters[] = new TFilter('colecao_id', '=', $data->colecao_id);// create the filter 
        }
        if (isset($data->classificacao_id) AND ( (is_scalar($data->classificacao_id) AND $data->classificacao_id !== '') OR (is_array($data->classificacao_id) AND (!empty($data->classificacao_id)) )) )
        {

            $filters[] = new TFilter('classificacao_id', '=', $data->classificacao_id);// create the filter 
        }
        if (isset($data->numero) AND ( (is_scalar($data->numero) AND $data->numero !== '') OR (is_array($data->numero) AND (!empty($data->numero)) )) )
        {

            $filters[] = new TFilter('numero', '=', $data->numero);// create the filter 
        }
        if (isset($data->autor_principal_id) AND ( (is_scalar($data->autor_principal_id) AND $data->autor_principal_id !== '') OR (is_array($data->autor_principal_id) AND (!empty($data->autor_principal_id)) )) )
        {

            $filters[] = new TFilter('autor_principal_id', '=', $data->autor_principal_id);// create the filter 
        }
        if (isset($data->titulo) AND ( (is_scalar($data->titulo) AND $data->titulo !== '') OR (is_array($data->titulo) AND (!empty($data->titulo)) )) )
        {

            $filters[] = new TFilter('titulo', 'like', "%{$data->titulo}%");// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);

        return $filters;
    }

    public function onGenerate($format)
    {
        try
        {
            $filters = $this->getFilters();
            // open a transaction with database 'biblioteca'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for Livro
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $criteria->setProperties($param);

            if ($filters)
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {
                $widths = array(200,200,200,200,200,200,200,200);

                switch ($format)
                {
                    case 'html':
                        $tr = new TTableWriterHTML($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLS($widths);
                        break;
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths, 'L');
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths, 'L');
                        break;
                }

                if (!empty($tr))
                {
                    // create the document styles
                    $tr->addStyle('title', 'Helvetica', '10', 'B',   '#000000', '#dbdbdb');
                    $tr->addStyle('datap', 'Arial', '10', '',    '#333333', '#f0f0f0');
                    $tr->addStyle('datai', 'Arial', '10', '',    '#333333', '#ffffff');
                    $tr->addStyle('header', 'Helvetica', '16', 'B',   '#5a5a5a', '#6B6B6B');
                    $tr->addStyle('footer', 'Helvetica', '10', 'B',  '#5a5a5a', '#A3A3A3');
                    $tr->addStyle('break', 'Helvetica', '10', 'B',  '#ffffff', '#9a9a9a');
                    $tr->addStyle('total', 'Helvetica', '10', 'I',  '#000000', '#c7c7c7');
                    $tr->addStyle('breakTotal', 'Helvetica', '10', 'I',  '#000000', '#c6c8d0');

                    // add titles row
                    $tr->addRow();
                    $tr->addCell("Id", 'left', 'title');
                    $tr->addCell("Título", 'left', 'title');
                    $tr->addCell("Autor principal", 'left', 'title');
                    $tr->addCell("Editora", 'left', 'title');
                    $tr->addCell("Chamada", 'left', 'title');
                    $tr->addCell("ISBN", 'left', 'title');
                    $tr->addCell("Edição", 'left', 'title');
                    $tr->addCell("Volume", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        $firstRow = false;

                        $tr->addRow();

                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->titulo, 'left', $style);
                        $tr->addCell($object->autor_principal->nome, 'left', $style);
                        $tr->addCell($object->editora->nome, 'left', $style);
                        $tr->addCell($object->numero, 'left', $style);
                        $tr->addCell($object->isbn, 'left', $style);
                        $tr->addCell($object->edicao, 'left', $style);
                        $tr->addCell($object->volume, 'left', $style);

                        $colour = !$colour;
                    }

                    $file = 'report_'.uniqid().".{$format}";
                    // stores the file
                    if (!file_exists("app/output/{$file}") || is_writable("app/output/{$file}"))
                    {
                        $tr->save("app/output/{$file}");
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . "app/output/{$file}");
                    }

                    parent::openFile("app/output/{$file}");

                    // shows the success message
                    new TMessage('info', _t('Report generated. Please, enable popups'));
                }
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }

            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
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

