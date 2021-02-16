<?php

class MultaReport extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'biblioteca';
    private static $activeRecord = 'Emprestimo';
    private static $primaryKey = 'id';
    private static $formName = 'formReport_Emprestimo';

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
        $this->form->setFormTitle("Relatório de multas");

        $exemplar_id = new TDBUniqueSearch('exemplar_id', 'biblioteca', 'Exemplar', 'id', 'codigo_barras','id asc'  );
        $leitor_id = new TDBUniqueSearch('leitor_id', 'biblioteca', 'Leitor', 'id', 'nome','nome asc'  );
        $dt_emprestimo = new TDate('dt_emprestimo');
        $dt_emprestimo_final = new TDate('dt_emprestimo_final');
        $dt_devolucao = new TDate('dt_devolucao');
        $dt_devolucao_final = new TDate('dt_devolucao_final');

        $leitor_id->setMinLength(2);
        $exemplar_id->setMinLength(1);

        $dt_devolucao->setDatabaseMask('yyyy-mm-dd');
        $dt_emprestimo->setDatabaseMask('yyyy-mm-dd');
        $dt_devolucao_final->setDatabaseMask('yyyy-mm-dd');
        $dt_emprestimo_final->setDatabaseMask('yyyy-mm-dd');

        $leitor_id->setSize('70%');
        $dt_devolucao->setSize(100);
        $exemplar_id->setSize('71%');
        $dt_emprestimo->setSize(100);
        $dt_devolucao_final->setSize(100);
        $dt_emprestimo_final->setSize(100);

        $leitor_id->setMask('{nome}');
        $dt_devolucao->setMask('dd/mm/yyyy');
        $dt_emprestimo->setMask('dd/mm/yyyy');
        $exemplar_id->setMask('{codigo_barras}');
        $dt_devolucao_final->setMask('dd/mm/yyyy');
        $dt_emprestimo_final->setMask('dd/mm/yyyy');

        $row1 = $this->form->addFields([new TLabel("Exemplar:", null, '14px', null)],[$exemplar_id]);
        $row2 = $this->form->addFields([new TLabel("Leitor:", null, '14px', null)],[$leitor_id]);
        $row3 = $this->form->addFields([new TLabel("Data de empréstimo inicial:", null, '14px', null)],[$dt_emprestimo],[new TLabel("Data de empréstimo final:", null, '14px', null)],[$dt_emprestimo_final]);
        $row4 = $this->form->addFields([new TLabel("Data de devolução inicial:", null, '14px', null)],[$dt_devolucao],[new TLabel("Data de devolução final:", null, '14px', null)],[$dt_devolucao_final]);

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

        if (isset($data->exemplar_id) AND ( (is_scalar($data->exemplar_id) AND $data->exemplar_id !== '') OR (is_array($data->exemplar_id) AND (!empty($data->exemplar_id)) )) )
        {

            $filters[] = new TFilter('exemplar_id', '=', $data->exemplar_id);// create the filter 
        }
        if (isset($data->leitor_id) AND ( (is_scalar($data->leitor_id) AND $data->leitor_id !== '') OR (is_array($data->leitor_id) AND (!empty($data->leitor_id)) )) )
        {

            $filters[] = new TFilter('leitor_id', '=', $data->leitor_id);// create the filter 
        }
        if (isset($data->dt_emprestimo) AND ( (is_scalar($data->dt_emprestimo) AND $data->dt_emprestimo !== '') OR (is_array($data->dt_emprestimo) AND (!empty($data->dt_emprestimo)) )) )
        {

            $filters[] = new TFilter('dt_emprestimo', '>=', $data->dt_emprestimo);// create the filter 
        }
        if (isset($data->dt_emprestimo_final) AND ( (is_scalar($data->dt_emprestimo_final) AND $data->dt_emprestimo_final !== '') OR (is_array($data->dt_emprestimo_final) AND (!empty($data->dt_emprestimo_final)) )) )
        {

            $filters[] = new TFilter('dt_emprestimo', '<=', $data->dt_emprestimo_final);// create the filter 
        }
        if (isset($data->dt_devolucao) AND ( (is_scalar($data->dt_devolucao) AND $data->dt_devolucao !== '') OR (is_array($data->dt_devolucao) AND (!empty($data->dt_devolucao)) )) )
        {

            $filters[] = new TFilter('dt_devolucao', '>=', $data->dt_devolucao);// create the filter 
        }
        if (isset($data->dt_devolucao_final) AND ( (is_scalar($data->dt_devolucao_final) AND $data->dt_devolucao_final !== '') OR (is_array($data->dt_devolucao_final) AND (!empty($data->dt_devolucao_final)) )) )
        {

            $filters[] = new TFilter('dt_devolucao', '<=', $data->dt_devolucao_final);// create the filter 
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
            // creates a repository for Emprestimo
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            $filterVar = "0.0";
            $criteria->add(new TFilter('valor_multa', '>', $filterVar));

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
                $widths = array(200,200,200,200,200,200);

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
                    $tr->addCell("Exemplar", 'left', 'title');
                    $tr->addCell("Leitor", 'left', 'title');
                    $tr->addCell("Empréstimo", 'left', 'title');
                    $tr->addCell("Devolução", 'left', 'title');
                    $tr->addCell("Valor multa", 'left', 'title');

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;                
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        $grandTotal['valor_multa'][] = $object->valor_multa;
                        $breakTotal['valor_multa'][] = $object->valor_multa;

                        $firstRow = false;

                        $object->dt_emprestimo = call_user_func(function($value, $object, $row) 
                        {
                            if(!empty(trim($value)))
                            {
                                try
                                {
                                    $date = new DateTime($value);
                                    return $date->format('d/m/Y');
                                }
                                catch (Exception $e)
                                {
                                    return $value;
                                }
                            }
                        }, $object->dt_emprestimo, $object, null);

                        $object->dt_devolucao = call_user_func(function($value, $object, $row) 
                        {
                            if(!empty(trim($value)))
                            {
                                try
                                {
                                    $date = new DateTime($value);
                                    return $date->format('d/m/Y');
                                }
                                catch (Exception $e)
                                {
                                    return $value;
                                }
                            }
                        }, $object->dt_devolucao, $object, null);

                        $object->valor_multa = call_user_func(function($value, $object, $row) 
                        {
                            if(!$value)
                            {
                                $value = 0;
                            }

                            if(is_numeric($value))
                            {
                                return "R$ " . number_format($value, 2, ",", ".");
                            }
                            else
                            {
                                return $value;
                            }
                        }, $object->valor_multa, $object, null);

                        $tr->addRow();

                        $tr->addCell($object->id, 'left', $style);
                        $tr->addCell($object->exemplar->livro->titulo, 'left', $style);
                        $tr->addCell($object->leitor->nome, 'left', $style);
                        $tr->addCell($object->dt_emprestimo, 'left', $style);
                        $tr->addCell($object->dt_devolucao, 'left', $style);
                        $tr->addCell($object->valor_multa, 'left', $style);

                        $colour = !$colour;
                    }

                    $tr->addRow();

                    $grandTotal_valor_multa = array_sum($grandTotal['valor_multa']);

                    $grandTotal_valor_multa = call_user_func(function($value)
                    {
                        if(!$value)
                        {
                            $value = 0;
                        }

                        if(is_numeric($value))
                        {
                            return "R$ " . number_format($value, 2, ",", ".");
                        }
                        else
                        {
                            return $value;
                        }
                    }, $grandTotal_valor_multa); 

                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell('', 'center', 'total');
                    $tr->addCell($grandTotal_valor_multa, 'left', 'total');

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

