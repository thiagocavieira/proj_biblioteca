<?php

class EmprestimoClassificacaoChart extends TPage
{
    private $form; // form
    private $loaded;
    private static $database = 'biblioteca';
    private static $activeRecord = 'Emprestimo';
    private static $primaryKey = 'id';
    private static $formName = 'formChart_Emprestimo';

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
        $this->form->setFormTitle("Empréstimos por classificação");

        $leitor_id = new TDBUniqueSearch('leitor_id', 'biblioteca', 'Leitor', 'id', 'nome','nome asc'  );
        $dt_emprestimo = new TDate('dt_emprestimo');
        $dt_emprestimo_final = new TDate('dt_emprestimo_final');
        $dt_previsao = new TDate('dt_previsao');
        $dt_prevista_devolucao_final = new TDate('dt_prevista_devolucao_final');
        $dt_devolucao = new TDate('dt_devolucao');
        $dt_devolucao_final = new TDate('dt_devolucao_final');

        $leitor_id->setMinLength(1);

        $dt_previsao->setDatabaseMask('yyyy-mm-dd');
        $dt_devolucao->setDatabaseMask('yyyy-mm-dd');
        $dt_emprestimo->setDatabaseMask('yyyy-mm-dd');
        $dt_devolucao_final->setDatabaseMask('yyyy-mm-dd');
        $dt_emprestimo_final->setDatabaseMask('yyyy-mm-dd');
        $dt_prevista_devolucao_final->setDatabaseMask('yyyy-mm-dd');

        $leitor_id->setSize('70%');
        $dt_previsao->setSize(100);
        $dt_devolucao->setSize(100);
        $dt_emprestimo->setSize(100);
        $dt_devolucao_final->setSize(100);
        $dt_emprestimo_final->setSize(100);
        $dt_prevista_devolucao_final->setSize(100);

        $leitor_id->setMask('{nome}');
        $dt_previsao->setMask('dd/mm/yyyy');
        $dt_devolucao->setMask('dd/mm/yyyy');
        $dt_emprestimo->setMask('dd/mm/yyyy');
        $dt_devolucao_final->setMask('dd/mm/yyyy');
        $dt_emprestimo_final->setMask('dd/mm/yyyy');
        $dt_prevista_devolucao_final->setMask('dd/mm/yyyy');

        $row1 = $this->form->addFields([new TLabel("Leitor:", null, '14px', null)],[$leitor_id]);
        $row2 = $this->form->addFields([new TLabel("Data de empréstimo inicial:", null, '14px', null)],[$dt_emprestimo],[new TLabel("Data de empréstimo final", null, '14px', null)],[$dt_emprestimo_final]);
        $row3 = $this->form->addFields([new TLabel("Data prevista de devolução:", null, '14px', null)],[$dt_previsao],[new TLabel("Data prevista de devolução final", null, '14px', null)],[$dt_prevista_devolucao_final]);
        $row4 = $this->form->addFields([new TLabel("Data de devolução inicial:", null, '14px', null)],[$dt_devolucao],[new TLabel("Data de devolução final:", null, '14px', null)],[$dt_devolucao_final]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar", new TAction([$this, 'onGenerate']), 'fas:search #ffffff');
        $btn_ongenerate->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);

    }

    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

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
        if (isset($data->dt_previsao) AND ( (is_scalar($data->dt_previsao) AND $data->dt_previsao !== '') OR (is_array($data->dt_previsao) AND (!empty($data->dt_previsao)) )) )
        {

            $filters[] = new TFilter('dt_previsao', '=', $data->dt_previsao);// create the filter 
        }
        if (isset($data->dt_prevista_devolucao_final) AND ( (is_scalar($data->dt_prevista_devolucao_final) AND $data->dt_prevista_devolucao_final !== '') OR (is_array($data->dt_prevista_devolucao_final) AND (!empty($data->dt_prevista_devolucao_final)) )) )
        {

            $filters[] = new TFilter('dt_devolucao', '<=', $data->dt_prevista_devolucao_final);// create the filter 
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
        TSession::setValue(__CLASS__.'_filters', $filters);
    }

    /**
     * Load the datagrid with data
     */
    public function onGenerate()
    {
        try
        {
            $this->onSearch();
            // open a transaction with database 'biblioteca'
            TTransaction::open(self::$database);
            $param = [];
            // creates a repository for Emprestimo
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;

            if ($filters = TSession::getValue(__CLASS__.'_filters'))
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

                $dataTotals = [];
                $groups = [];
                $data = [];
                foreach ($objects as $obj)
                {
                    $group1 = $obj->exemplar->livro->classificacao->nome;

                    $groups[$group1] = true;
                    $numericField = $obj->id;

                    $dataTotals[$group1]['count'] = isset($dataTotals[$group1]['count']) ? $dataTotals[$group1]['count'] + 1 : 1;
                    $dataTotals[$group1]['sum'] = isset($dataTotals[$group1]['sum']) ? $dataTotals[$group1]['sum'] + $numericField  : $numericField;

                }

                $groups = ['x'=>true]+$groups;

                foreach ($dataTotals as $group1 => $totals) 
                {    

                    array_push($data, [$group1, $totals['count']]);

                }

                $chart = new THtmlRenderer('app/resources/c3_pizza_chart.html');
                $chart->enableSection('main', [
                    'data'=> json_encode($data),
                    'height' => 300,
                    'precision' => 0,
                    'decimalSeparator' => ',',
                    'thousandSeparator' => '.',
                    'prefix' => '',
                    'sufix' => '',
                    'width' => 100,
                    'widthType' => '%',
                    'title' => 'Empréstimos por classificação',
                    'showLegend' => 'false',
                    'showPercentage' => 'false',
                    'barDirection' => 'false'
                ]);

                parent::add($chart);
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

