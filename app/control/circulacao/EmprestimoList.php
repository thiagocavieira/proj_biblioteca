<?php

class EmprestimoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'biblioteca';
    private static $activeRecord = 'Emprestimo';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Emprestimo';
    private $showMethods = ['onReload', 'onSearch'];

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
        $this->form->setFormTitle("Listagem de emprestimos");

        $exemplar_id = new TDBUniqueSearch('exemplar_id', 'biblioteca', 'Exemplar', 'id', 'codigo_barras','id asc'  );
        $leitor_id = new TDBUniqueSearch('leitor_id', 'biblioteca', 'Leitor', 'id', 'nome','nome asc'  );
        $dt_emprestimo = new TDate('dt_emprestimo');
        $dt_emprestimo_final = new TDate('dt_emprestimo_final');
        $dt_previsao = new TDate('dt_previsao');
        $dt_prevista_final = new TDate('dt_prevista_final');
        $dt_devolucao = new TDate('dt_devolucao');
        $dt_devolucao_final = new TDate('dt_devolucao_final');

        $leitor_id->setMinLength(2);
        $exemplar_id->setMinLength(1);

        $dt_previsao->setDatabaseMask('yyyy-mm-dd');
        $dt_devolucao->setDatabaseMask('yyyy-mm-dd');
        $dt_emprestimo->setDatabaseMask('yyyy-mm-dd');
        $dt_prevista_final->setDatabaseMask('yyyy-mm-dd');
        $dt_devolucao_final->setDatabaseMask('yyyy-mm-dd');
        $dt_emprestimo_final->setDatabaseMask('yyyy-mm-dd');

        $leitor_id->setSize('70%');
        $dt_previsao->setSize(100);
        $dt_devolucao->setSize(100);
        $exemplar_id->setSize('70%');
        $dt_emprestimo->setSize(100);
        $dt_prevista_final->setSize(100);
        $dt_devolucao_final->setSize(100);
        $dt_emprestimo_final->setSize(100);

        $leitor_id->setMask('{nome}');
        $dt_previsao->setMask('dd/mm/yyyy');
        $dt_devolucao->setMask('dd/mm/yyyy');
        $dt_emprestimo->setMask('dd/mm/yyyy');
        $exemplar_id->setMask('{livro->titulo}');
        $dt_prevista_final->setMask('dd/mm/yyyy');
        $dt_devolucao_final->setMask('dd/mm/yyyy');
        $dt_emprestimo_final->setMask('dd/mm/yyyy');

        $row1 = $this->form->addFields([new TLabel("Exemplar:", null, '14px', null)],[$exemplar_id]);
        $row2 = $this->form->addFields([new TLabel("Leitor:", null, '14px', null)],[$leitor_id]);
        $row3 = $this->form->addFields([new TLabel("Data de empréstimo inicial:", null, '14px', null)],[$dt_emprestimo],[new TLabel("Data de empréstimo final:", null, '14px', null)],[$dt_emprestimo_final]);
        $row4 = $this->form->addFields([new TLabel("Data prevista de devolução inicial:", null, '14px', null)],[$dt_previsao],[new TLabel("Data prevista de devolução final:", null, '14px', null)],[$dt_prevista_final]);
        $row5 = $this->form->addFields([new TLabel("Data de devolução inicial:", null, '14px', null)],[$dt_devolucao],[new TLabel("Data de devolução final:", null, '14px', null)],[$dt_devolucao_final]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'far:file-alt #000000');

        $btn_onshow = $this->form->addAction("Novo emprestimo", new TAction(['EmprestimoLivroForm', 'onShow']), 'fas:plus #69aa46');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_id = new TDataGridColumn('id', "Id", 'left' , '69px');
        $column_exemplar_livro_titulo = new TDataGridColumn('exemplar->livro->titulo', "Exemplar", 'left');
        $column_leitor_nome = new TDataGridColumn('leitor->nome', "Leitor", 'left');
        $column_dt_emprestimo_transformed = new TDataGridColumn('dt_emprestimo', "Emprestado em", 'left');
        $column_dt_previsao_transformed = new TDataGridColumn('dt_previsao', "Previsão de devolução", 'left');
        $column_dt_devolucao_transformed = new TDataGridColumn('dt_devolucao', "Devolvido em", 'left');

        $column_dt_emprestimo_transformed->setTransformer(function($value, $object, $row) 
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
        });

        $column_dt_previsao_transformed->setTransformer(function($value, $object, $row) 
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
        });

        $column_dt_devolucao_transformed->setTransformer(function($value, $object, $row) 
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
        });        

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        $order_dt_emprestimo_transformed = new TAction(array($this, 'onReload'));
        $order_dt_emprestimo_transformed->setParameter('order', 'dt_emprestimo');
        $column_dt_emprestimo_transformed->setAction($order_dt_emprestimo_transformed);
        $order_dt_previsao_transformed = new TAction(array($this, 'onReload'));
        $order_dt_previsao_transformed->setParameter('order', 'dt_previsao');
        $column_dt_previsao_transformed->setAction($order_dt_previsao_transformed);
        $order_dt_devolucao_transformed = new TAction(array($this, 'onReload'));
        $order_dt_devolucao_transformed->setParameter('order', 'dt_devolucao');
        $column_dt_devolucao_transformed->setAction($order_dt_devolucao_transformed);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_exemplar_livro_titulo);
        $this->datagrid->addColumn($column_leitor_nome);
        $this->datagrid->addColumn($column_dt_emprestimo_transformed);
        $this->datagrid->addColumn($column_dt_previsao_transformed);
        $this->datagrid->addColumn($column_dt_devolucao_transformed);

        $action_onDevolver = new TDataGridAction(array('EmprestimoList', 'onDevolver'));
        $action_onDevolver->setUseButton(true);
        $action_onDevolver->setButtonClass('btn btn-default btn-sm');
        $action_onDevolver->setLabel("Devolver");
        $action_onDevolver->setImage('fa:arrow-up #000000');
        $action_onDevolver->setField(self::$primaryKey);
        $action_onDevolver->setDisplayCondition('EmprestimoList::onExibirDevolver');

        $this->datagrid->addAction($action_onDevolver);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->datagrid);

        $panel->addFooter($this->pageNavigation);

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);

        parent::add($container);

    }

    public function onDevolver($param = null) 
    {
        try 
        {
            // Código gerado pelo snippet: "Questionamento"
            new TQuestion("Registrar devolução?", new TAction([$this, 'onDevolverOnYes'], $param));
            // -----

            //code here

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onDevolverOnYes($param = null)
    {
        // Código gerado pelo snippet: "Questionamento"
        try
        {
            TTransaction::open('biblioteca');

            $emprestimo = new Emprestimo( $param['key'] );
            $emprestimo->dt_devolucao = date('Y-m-d');
            $emprestimo->store();

            TTransaction::close();

            new TMessage('info', 'Devolução registrada com sucesso!', new TAction( [ 'EmprestimoList', 'onReload' ] ) );
        }
        catch(Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    public static function onExibirDevolver($object)
    {
        try 
        {
            if( empty($object->dt_devolucao))
            {
                return true;
            }

            return false;
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onExportCsv($param = null) 
    {
        try
        {
            $this->onSearch();

            TTransaction::open(self::$database); // open a transaction
            $repository = new TRepository(self::$activeRecord); // creates a repository for Customer
            $criteria = new TCriteria; // creates a criteria

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            $records = $repository->load($criteria); // load the objects according to criteria
            if ($records)
            {
                $file = 'tmp/'.uniqid().'.csv';
                $handle = fopen($file, 'w');
                $columns = $this->datagrid->getColumns();

                $csvColumns = [];
                foreach($columns as $column)
                {
                    $csvColumns[] = $column->getLabel();
                }
                fputcsv($handle, $csvColumns, ';');

                foreach ($records as $record)
                {
                    $csvColumns = [];
                    foreach($columns as $column)
                    {
                        $name = $column->getName();
                        $csvColumns[] = $record->{$name};
                    }
                    fputcsv($handle, $csvColumns, ';');
                }
                fclose($handle);

                TPage::openFile($file);
            }
            else
            {
                new TMessage('info', _t('No records found'));
            }

            TTransaction::close(); // close the transaction
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
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

        if (isset($data->dt_previsao) AND ( (is_scalar($data->dt_previsao) AND $data->dt_previsao !== '') OR (is_array($data->dt_previsao) AND (!empty($data->dt_previsao)) )) )
        {

            $filters[] = new TFilter('dt_previsao', '>=', $data->dt_previsao);// create the filter 
        }

        if (isset($data->dt_prevista_final) AND ( (is_scalar($data->dt_prevista_final) AND $data->dt_prevista_final !== '') OR (is_array($data->dt_prevista_final) AND (!empty($data->dt_prevista_final)) )) )
        {

            $filters[] = new TFilter('dt_previsao', '<=', $data->dt_prevista_final);// create the filter 
        }

        if (isset($data->dt_devolucao) AND ( (is_scalar($data->dt_devolucao) AND $data->dt_devolucao !== '') OR (is_array($data->dt_devolucao) AND (!empty($data->dt_devolucao)) )) )
        {

            $filters[] = new TFilter('dt_devolucao', '>=', $data->dt_devolucao);// create the filter 
        }

        if (isset($data->dt_devolucao_final) AND ( (is_scalar($data->dt_devolucao_final) AND $data->dt_devolucao_final !== '') OR (is_array($data->dt_devolucao_final) AND (!empty($data->dt_devolucao_final)) )) )
        {

            $filters[] = new TFilter('dt_devolucao', '<=', $data->dt_devolucao_final);// create the filter 
        }

        $param = array();
        $param['offset']     = 0;
        $param['first_page'] = 1;

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload($param);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'biblioteca'
            TTransaction::open(self::$database);

            // creates a repository for Emprestimo
            $repository = new TRepository(self::$activeRecord);
            $limit = 20;

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid

                    $this->datagrid->addItem($object);

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;
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

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

}

