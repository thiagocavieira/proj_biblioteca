<?php

class LivroList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'biblioteca';
    private static $activeRecord = 'Livro';
    private static $primaryKey = 'id';
    private static $formName = 'formList_Livro';
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
        $this->form->setFormTitle("Listagem de livros");

        $id = new TEntry('id');
        $editora_id = new TDBUniqueSearch('editora_id', 'biblioteca', 'Editora', 'id', 'nome','nome asc'  );
        $titulo = new TEntry('titulo');
        $autor_principal_id = new TDBUniqueSearch('autor_principal_id', 'biblioteca', 'Autor', 'id', 'nome','nome asc'  );
        $colecao_id = new TDBCombo('colecao_id', 'biblioteca', 'Colecao', 'id', '{nome}','nome asc'  );
        $classificacao_id = new TDBCombo('classificacao_id', 'biblioteca', 'Classificacao', 'id', '{nome}','nome asc'  );

        $editora_id->setMask('{nome}');
        $autor_principal_id->setMask('{nome}');

        $editora_id->setMinLength(2);
        $autor_principal_id->setMinLength(2);

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

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $btn_onsearch->addStyleClass('btn-primary'); 

        $btn_onexportcsv = $this->form->addAction("Exportar como CSV", new TAction([$this, 'onExportCsv']), 'far:file-alt #000000');

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['LivroForm', 'onShow']), 'fas:plus #69aa46');

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->enablePopover("Informações", "Classificação: {classificacao->nome} 
Coleção: {colecao->nome} 
ISBN: {isbn}
Data de publicação: {dt_publicacao}
Editora:  {editora->nome} 
Número: {numero}  
OBS: {obs}");

        $column_id = new TDataGridColumn('id', "Id", 'left' , '69.8px');
        $column_autor_principal_nome = new TDataGridColumn('autor_principal->nome', "Autor principal", 'left');
        $column_titulo = new TDataGridColumn('titulo', "Título", 'left');
        $column_edicao = new TDataGridColumn('edicao', "Edição", 'left');
        $column_volume = new TDataGridColumn('volume', "Volume", 'left');

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        $order_titulo = new TAction(array($this, 'onReload'));
        $order_titulo->setParameter('order', 'titulo');
        $column_titulo->setAction($order_titulo);
        $order_edicao = new TAction(array($this, 'onReload'));
        $order_edicao->setParameter('order', 'edicao');
        $column_edicao->setAction($order_edicao);
        $order_volume = new TAction(array($this, 'onReload'));
        $order_volume->setParameter('order', 'volume');
        $column_volume->setAction($order_volume);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_autor_principal_nome);
        $this->datagrid->addColumn($column_titulo);
        $this->datagrid->addColumn($column_edicao);
        $this->datagrid->addColumn($column_volume);

        $action_onShow = new TDataGridAction(array('LivroFormView', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Editar");
        $action_onShow->setImage('far:edit #478fca');
        $action_onShow->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onShow);

        $action_onDelete = new TDataGridAction(array('LivroList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluír");
        $action_onDelete->setImage('far:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

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

    public function onDelete($param = null) 
    { 
        if(isset($params['delete']) && $params['delete'] == 1)
        {
            try
            {
                // get the paramseter $key
                $key=$params['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                $class = self::$activeRecord;

                // instantiates object
                $object = new $class($key, FALSE);

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload( $params );
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
            }
            catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        }
        else
        {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($params); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);   
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

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->editora_id) AND ( (is_scalar($data->editora_id) AND $data->editora_id !== '') OR (is_array($data->editora_id) AND (!empty($data->editora_id)) )) )
        {

            $filters[] = new TFilter('editora_id', '=', $data->editora_id);// create the filter 
        }

        if (isset($data->titulo) AND ( (is_scalar($data->titulo) AND $data->titulo !== '') OR (is_array($data->titulo) AND (!empty($data->titulo)) )) )
        {

            $filters[] = new TFilter('titulo', 'like', "%{$data->titulo}%");// create the filter 
        }

        if (isset($data->autor_principal_id) AND ( (is_scalar($data->autor_principal_id) AND $data->autor_principal_id !== '') OR (is_array($data->autor_principal_id) AND (!empty($data->autor_principal_id)) )) )
        {

            $filters[] = new TFilter('autor_principal_id', '=', $data->autor_principal_id);// create the filter 
        }

        if (isset($data->colecao_id) AND ( (is_scalar($data->colecao_id) AND $data->colecao_id !== '') OR (is_array($data->colecao_id) AND (!empty($data->colecao_id)) )) )
        {

            $filters[] = new TFilter('colecao_id', '=', $data->colecao_id);// create the filter 
        }

        if (isset($data->classificacao_id) AND ( (is_scalar($data->classificacao_id) AND $data->classificacao_id !== '') OR (is_array($data->classificacao_id) AND (!empty($data->classificacao_id)) )) )
        {

            $filters[] = new TFilter('classificacao_id', '=', $data->classificacao_id);// create the filter 
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

            // creates a repository for Livro
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

