<?php

class LivroForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'biblioteca';
    private static $activeRecord = 'Livro';
    private static $primaryKey = 'id';
    private static $formName = 'form_Livro';

    use Adianti\Base\AdiantiMasterDetailTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de livro");


        $id = new TEntry('id');
        $titulo = new TEntry('titulo');
        $isbn = new TEntry('isbn');
        $numero = new TEntry('numero');
        $autor_principal_id = new TDBUniqueSearch('autor_principal_id', 'biblioteca', 'Autor', 'id', 'nome','nome asc'  );
        $editora_id = new TDBUniqueSearch('editora_id', 'biblioteca', 'Editora', 'id', 'nome','nome asc'  );
        $colecao_id = new TDBCombo('colecao_id', 'biblioteca', 'Colecao', 'id', '{nome}','nome asc'  );
        $classificacao_id = new TDBCombo('classificacao_id', 'biblioteca', 'Classificacao', 'id', '{nome}','nome asc'  );
        $edicao = new TEntry('edicao');
        $volume = new TEntry('volume');
        $local_publicacao = new TEntry('local_publicacao');
        $dt_publicacao = new TDate('dt_publicacao');
        $obs = new TText('obs');
        $livro_autor_livro_autor_id = new TDBUniqueSearch('livro_autor_livro_autor_id', 'biblioteca', 'Autor', 'id', 'nome','nome asc'  );
        $exemplar_livro_codigo_barras = new TEntry('exemplar_livro_codigo_barras');
        $exemplar_livro_status_id = new TDBCombo('exemplar_livro_status_id', 'biblioteca', 'Status', 'id', '{nome}','nome asc'  );
        $exemplar_livro_preco_custo = new TNumeric('exemplar_livro_preco_custo', '2', ',', '.' );
        $exemplar_livro_dt_aqusicao = new TDate('exemplar_livro_dt_aqusicao');
        $exemplar_livro_obs = new TText('exemplar_livro_obs');
        $livro_assunto_livro_assunto_id = new TDBCombo('livro_assunto_livro_assunto_id', 'biblioteca', 'Assunto', 'id', '{nome}','nome asc'  );
        $livro_autor_livro_id = new THidden('livro_autor_livro_id');
        $exemplar_livro_id = new THidden('exemplar_livro_id');
        $livro_assunto_livro_id = new THidden('livro_assunto_livro_id');

        $titulo->addValidation("Título", new TRequiredValidator()); 
        $numero->addValidation("Número de chamada", new TRequiredValidator()); 
        $autor_principal_id->addValidation("Autor principal", new TRequiredValidator()); 
        $editora_id->addValidation("Editora", new TRequiredValidator()); 
        $colecao_id->addValidation("Coleção", new TRequiredValidator()); 
        $classificacao_id->addValidation("Classificação", new TRequiredValidator()); 

        $id->setEditable(false);

        $dt_publicacao->setDatabaseMask('yyyy-mm-dd');
        $exemplar_livro_dt_aqusicao->setDatabaseMask('yyyy-mm-dd');

        $editora_id->setMinLength(1);
        $autor_principal_id->setMinLength(1);
        $livro_autor_livro_autor_id->setMinLength(1);

        $editora_id->setMask('{nome}');
        $dt_publicacao->setMask('dd/mm/yyyy');
        $autor_principal_id->setMask('{nome}');
        $livro_autor_livro_autor_id->setMask('{nome}');
        $exemplar_livro_dt_aqusicao->setMask('dd/mm/yyyy');

        $id->setSize(100);
        $isbn->setSize('100%');
        $titulo->setSize('100%');
        $numero->setSize('100%');
        $edicao->setSize('100%');
        $volume->setSize('100%');
        $obs->setSize('100%', 80);
        $editora_id->setSize('100%');
        $colecao_id->setSize('100%');
        $dt_publicacao->setSize(100);
        $local_publicacao->setSize('100%');
        $classificacao_id->setSize('100%');
        $autor_principal_id->setSize('100%');
        $exemplar_livro_obs->setSize('70%', 80);
        $exemplar_livro_dt_aqusicao->setSize(100);
        $exemplar_livro_status_id->setSize('100%');
        $livro_autor_livro_autor_id->setSize('70%');
        $exemplar_livro_preco_custo->setSize('100%');
        $exemplar_livro_codigo_barras->setSize('100%');
        $livro_assunto_livro_assunto_id->setSize('70%');

        $this->form->appendPage("Dados básicos");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[],[]);
        $row2 = $this->form->addFields([new TLabel("Título:", '#ff0000', '14px', null)],[$titulo]);
        $row3 = $this->form->addFields([new TLabel("ISBN:", null, '14px', null)],[$isbn],[new TLabel("Número de chamada:", '#ff0000', '14px', null)],[$numero]);
        $row4 = $this->form->addFields([new TLabel("Autor principal:", '#ff0000', '14px', null)],[$autor_principal_id],[new TLabel("Editora:", '#ff0000', '14px', null)],[$editora_id]);
        $row5 = $this->form->addFields([new TLabel("Coleção:", '#ff0000', '14px', null)],[$colecao_id],[new TLabel("Classificação:", '#ff0000', '14px', null)],[$classificacao_id]);
        $row6 = $this->form->addFields([new TLabel("Edição:", null, '14px', null)],[$edicao],[new TLabel("Volume:", null, '14px', null)],[$volume]);
        $row7 = $this->form->addFields([new TLabel("Local de publicação:", null, '14px', null)],[$local_publicacao],[new TLabel("Data de publicação:", null, '14px', null)],[$dt_publicacao]);
        $row8 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$obs]);

        $this->form->appendPage("Autores");
        $row9 = $this->form->addContent([new TFormSeparator("Outros autores", '#333333', '18', '#eeeeee')]);
        $row10 = $this->form->addFields([new TLabel("Autor:", '#ff0000', '14px', null)],[$livro_autor_livro_autor_id]);
        $row11 = $this->form->addFields([$livro_autor_livro_id]);         
        $add_livro_autor_livro = new TButton('add_livro_autor_livro');

        $action_livro_autor_livro = new TAction(array($this, 'onAddLivroAutorLivro'));

        $add_livro_autor_livro->setAction($action_livro_autor_livro, "Adicionar");
        $add_livro_autor_livro->setImage('fas:plus #000000');

        $this->form->addFields([$add_livro_autor_livro]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->livro_autor_livro_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->livro_autor_livro_list->style = 'width:100%';
        $this->livro_autor_livro_list->class .= ' table-bordered';
        $this->livro_autor_livro_list->disableDefaultClick();
        $this->livro_autor_livro_list->addQuickColumn('', 'edit', 'left', 50);
        $this->livro_autor_livro_list->addQuickColumn('', 'delete', 'left', 50);

        $column_livro_autor_livro_autor_id = $this->livro_autor_livro_list->addQuickColumn("Autor", 'livro_autor_livro_autor_id', 'left');

        $this->livro_autor_livro_list->createModel();
        $this->form->addContent([$this->livro_autor_livro_list]);

        $this->form->appendPage("Exemplares");
        $row12 = $this->form->addContent([new TFormSeparator("Exemplares do livro", '#333333', '18', '#eeeeee')]);
        $row13 = $this->form->addFields([new TLabel("Código de barras:", '#ff0000', '14px', null)],[$exemplar_livro_codigo_barras],[new TLabel("Status:", '#ff0000', '14px', null)],[$exemplar_livro_status_id]);
        $row14 = $this->form->addFields([new TLabel("Preço de custo:", null, '14px', null)],[$exemplar_livro_preco_custo],[new TLabel("Data de aquisição:", null, '14px', null)],[$exemplar_livro_dt_aqusicao]);
        $row15 = $this->form->addFields([new TLabel("Observação:", null, '14px', null)],[$exemplar_livro_obs]);
        $row16 = $this->form->addFields([$exemplar_livro_id]);         
        $add_exemplar_livro = new TButton('add_exemplar_livro');

        $action_exemplar_livro = new TAction(array($this, 'onAddExemplarLivro'));

        $add_exemplar_livro->setAction($action_exemplar_livro, "Adicionar");
        $add_exemplar_livro->setImage('fas:plus #000000');

        $this->form->addFields([$add_exemplar_livro]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->exemplar_livro_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->exemplar_livro_list->style = 'width:100%';
        $this->exemplar_livro_list->class .= ' table-bordered';
        $this->exemplar_livro_list->disableDefaultClick();
        $this->exemplar_livro_list->addQuickColumn('', 'edit', 'left', 50);
        $this->exemplar_livro_list->addQuickColumn('', 'delete', 'left', 50);

        $column_exemplar_livro_status_id = $this->exemplar_livro_list->addQuickColumn("Status", 'exemplar_livro_status_id', 'left');
        $column_exemplar_livro_codigo_barras = $this->exemplar_livro_list->addQuickColumn("Código de barras", 'exemplar_livro_codigo_barras', 'left');
        $column_exemplar_livro_dt_aqusicao_transformed = $this->exemplar_livro_list->addQuickColumn("Data de aquisição", 'exemplar_livro_dt_aqusicao', 'left');
        $column_exemplar_livro_preco_custo_transformed = $this->exemplar_livro_list->addQuickColumn("Preço de custo", 'exemplar_livro_preco_custo', 'left');
        $column_exemplar_livro_obs = $this->exemplar_livro_list->addQuickColumn("Observação", 'exemplar_livro_obs', 'left');

        $this->exemplar_livro_list->createModel();
        $this->form->addContent([$this->exemplar_livro_list]);

        $column_exemplar_livro_dt_aqusicao_transformed->setTransformer(function($value, $object, $row) 
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

        $column_exemplar_livro_preco_custo_transformed->setTransformer(function($value, $object, $row) 
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
        });
        $this->form->appendPage("Assuntos");
        $row17 = $this->form->addContent([new TFormSeparator("Assuntos relacionados ao livro", '#333333', '18', '#eeeeee')]);
        $row18 = $this->form->addFields([new TLabel("Assunto:", '#ff0000', '14px', null)],[$livro_assunto_livro_assunto_id]);
        $row19 = $this->form->addFields([$livro_assunto_livro_id]);         
        $add_livro_assunto_livro = new TButton('add_livro_assunto_livro');

        $action_livro_assunto_livro = new TAction(array($this, 'onAddLivroAssuntoLivro'));

        $add_livro_assunto_livro->setAction($action_livro_assunto_livro, "Adicionar");
        $add_livro_assunto_livro->setImage('fas:plus #000000');

        $this->form->addFields([$add_livro_assunto_livro]);

        $detailDatagrid = new TQuickGrid;
        $detailDatagrid->disableHtmlConversion();
        $this->livro_assunto_livro_list = new BootstrapDatagridWrapper($detailDatagrid);
        $this->livro_assunto_livro_list->style = 'width:100%';
        $this->livro_assunto_livro_list->class .= ' table-bordered';
        $this->livro_assunto_livro_list->disableDefaultClick();
        $this->livro_assunto_livro_list->addQuickColumn('', 'edit', 'left', 50);
        $this->livro_assunto_livro_list->addQuickColumn('', 'delete', 'left', 50);

        $column_livro_assunto_livro_assunto_id = $this->livro_assunto_livro_list->addQuickColumn("Assunto", 'livro_assunto_livro_assunto_id', 'left');

        $this->livro_assunto_livro_list->createModel();
        $this->form->addContent([$this->livro_assunto_livro_list]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Novo", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['LivroList', 'onShow']), 'far:arrow-alt-circle-left #3c8dbc');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Livro(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $messageAction = new TAction(['LivroList', 'onShow']);   

            $livro_autor_livro_items = $this->storeItems('LivroAutor', 'livro_id', $object, 'livro_autor_livro', function($masterObject, $detailObject){ 

                //code here

            }); 

            $livro_assunto_livro_items = $this->storeItems('LivroAssunto', 'livro_id', $object, 'livro_assunto_livro', function($masterObject, $detailObject){ 

                //code here

            }); 

            $exemplar_livro_items = $this->storeItems('Exemplar', 'livro_id', $object, 'exemplar_livro', function($masterObject, $detailObject){ 

                //code here

            }); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            /**
            // To define an action to be executed on the message close event:
            $messageAction = new TAction(['className', 'methodName']);
            **/

            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), $messageAction);
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Livro($key); // instantiates the Active Record 

                $livro_autor_livro_items = $this->loadItems('LivroAutor', 'livro_id', $object, 'livro_autor_livro', function($masterObject, $detailObject){ 

                    //code here

                }); 

                $livro_assunto_livro_items = $this->loadItems('LivroAssunto', 'livro_id', $object, 'livro_assunto_livro', function($masterObject, $detailObject){ 

                    //code here

                }); 

                $exemplar_livro_items = $this->loadItems('Exemplar', 'livro_id', $object, 'exemplar_livro', function($masterObject, $detailObject){ 

                    //code here

                }); 

                $this->form->setData($object); // fill the form 

                    $this->onReload();

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        TSession::setValue('livro_autor_livro_items', null);
        TSession::setValue('exemplar_livro_items', null);
        TSession::setValue('livro_assunto_livro_items', null);

        $this->onReload();
    }

    public function onAddLivroAutorLivro( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->livro_autor_livro_autor_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Autor"));
            }             

            $livro_autor_livro_items = TSession::getValue('livro_autor_livro_items');
            $key = isset($data->livro_autor_livro_id) && $data->livro_autor_livro_id ? $data->livro_autor_livro_id : 'b'.uniqid();
            $fields = []; 

            $fields['livro_autor_livro_autor_id'] = $data->livro_autor_livro_autor_id;
            $livro_autor_livro_items[ $key ] = $fields;

            TSession::setValue('livro_autor_livro_items', $livro_autor_livro_items);

            $data->livro_autor_livro_id = '';
            $data->livro_autor_livro_autor_id = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditLivroAutorLivro( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('livro_autor_livro_items');

        // get the session item
        $item = $items[$param['livro_autor_livro_id_row_id']];

        $data->livro_autor_livro_autor_id = $item['livro_autor_livro_autor_id'];

        $data->livro_autor_livro_id = $param['livro_autor_livro_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->onReload( $param );

    }

    public function onDeleteLivroAutorLivro( $param )
    {
        $data = $this->form->getData();

        $data->livro_autor_livro_autor_id = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('livro_autor_livro_items');

        // delete the item from session
        unset($items[$param['livro_autor_livro_id_row_id']]);
        TSession::setValue('livro_autor_livro_items', $items);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadLivroAutorLivro( $param )
    {
        $items = TSession::getValue('livro_autor_livro_items'); 

        $this->livro_autor_livro_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteLivroAutorLivro')); 
                $action_del->setParameter('livro_autor_livro_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditLivroAutorLivro'));  
                $action_edi->setParameter('livro_autor_livro_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_livro_autor_livro'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = '';
                $button_del->setImage('far:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_livro_autor_livro'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = '';
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->livro_autor_livro_autor_id = '';
                if(isset($item['livro_autor_livro_autor_id']) && $item['livro_autor_livro_autor_id'])
                {
                    TTransaction::open('biblioteca');
                    $autor = Autor::find($item['livro_autor_livro_autor_id']);
                    if($autor)
                    {
                        $rowItem->livro_autor_livro_autor_id = $autor->render('{nome}');
                    }
                    TTransaction::close();
                }

                $row = $this->livro_autor_livro_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onAddExemplarLivro( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->exemplar_livro_codigo_barras)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Código de barras"));
            }             
            if(!$data->exemplar_livro_status_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Status id"));
            }             

            $exemplar_livro_items = TSession::getValue('exemplar_livro_items');
            $key = isset($data->exemplar_livro_id) && $data->exemplar_livro_id ? $data->exemplar_livro_id : 'b'.uniqid();
            $fields = []; 

            $fields['exemplar_livro_codigo_barras'] = $data->exemplar_livro_codigo_barras;
            $fields['exemplar_livro_status_id'] = $data->exemplar_livro_status_id;
            $fields['exemplar_livro_preco_custo'] = $data->exemplar_livro_preco_custo;
            $fields['exemplar_livro_dt_aqusicao'] = $data->exemplar_livro_dt_aqusicao;
            $fields['exemplar_livro_obs'] = $data->exemplar_livro_obs;
            $exemplar_livro_items[ $key ] = $fields;

            TSession::setValue('exemplar_livro_items', $exemplar_livro_items);

            $data->exemplar_livro_id = '';
            $data->exemplar_livro_codigo_barras = '';
            $data->exemplar_livro_status_id = '';
            $data->exemplar_livro_preco_custo = '';
            $data->exemplar_livro_dt_aqusicao = '';
            $data->exemplar_livro_obs = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditExemplarLivro( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('exemplar_livro_items');

        // get the session item
        $item = $items[$param['exemplar_livro_id_row_id']];

        $data->exemplar_livro_codigo_barras = $item['exemplar_livro_codigo_barras'];
        $data->exemplar_livro_status_id = $item['exemplar_livro_status_id'];
        $data->exemplar_livro_preco_custo = $item['exemplar_livro_preco_custo'];
        $data->exemplar_livro_dt_aqusicao = $item['exemplar_livro_dt_aqusicao'];
        $data->exemplar_livro_obs = $item['exemplar_livro_obs'];

        $data->exemplar_livro_id = $param['exemplar_livro_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->onReload( $param );

    }

    public function onDeleteExemplarLivro( $param )
    {
        $data = $this->form->getData();

        $data->exemplar_livro_codigo_barras = '';
        $data->exemplar_livro_status_id = '';
        $data->exemplar_livro_preco_custo = '';
        $data->exemplar_livro_dt_aqusicao = '';
        $data->exemplar_livro_obs = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('exemplar_livro_items');

        // delete the item from session
        unset($items[$param['exemplar_livro_id_row_id']]);
        TSession::setValue('exemplar_livro_items', $items);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadExemplarLivro( $param )
    {
        $items = TSession::getValue('exemplar_livro_items'); 

        $this->exemplar_livro_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteExemplarLivro')); 
                $action_del->setParameter('exemplar_livro_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditExemplarLivro'));  
                $action_edi->setParameter('exemplar_livro_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_exemplar_livro'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = '';
                $button_del->setImage('far:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_exemplar_livro'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = '';
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->exemplar_livro_codigo_barras = isset($item['exemplar_livro_codigo_barras']) ? $item['exemplar_livro_codigo_barras'] : '';
                $rowItem->exemplar_livro_status_id = '';
                if(isset($item['exemplar_livro_status_id']) && $item['exemplar_livro_status_id'])
                {
                    TTransaction::open('biblioteca');
                    $status = Status::find($item['exemplar_livro_status_id']);
                    if($status)
                    {
                        $rowItem->exemplar_livro_status_id = $status->render('{nome}');
                    }
                    TTransaction::close();
                }

                $rowItem->exemplar_livro_preco_custo = isset($item['exemplar_livro_preco_custo']) ? $item['exemplar_livro_preco_custo'] : '';
                $rowItem->exemplar_livro_dt_aqusicao = isset($item['exemplar_livro_dt_aqusicao']) ? $item['exemplar_livro_dt_aqusicao'] : '';
                $rowItem->exemplar_livro_obs = isset($item['exemplar_livro_obs']) ? $item['exemplar_livro_obs'] : '';

                $row = $this->exemplar_livro_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onAddLivroAssuntoLivro( $param )
    {
        try
        {
            $data = $this->form->getData();

            if(!$data->livro_assunto_livro_assunto_id)
            {
                throw new Exception(AdiantiCoreTranslator::translate('The field ^1 is required', "Assunto"));
            }             

            $livro_assunto_livro_items = TSession::getValue('livro_assunto_livro_items');
            $key = isset($data->livro_assunto_livro_id) && $data->livro_assunto_livro_id ? $data->livro_assunto_livro_id : 'b'.uniqid();
            $fields = []; 

            $fields['livro_assunto_livro_assunto_id'] = $data->livro_assunto_livro_assunto_id;
            $livro_assunto_livro_items[ $key ] = $fields;

            TSession::setValue('livro_assunto_livro_items', $livro_assunto_livro_items);

            $data->livro_assunto_livro_id = '';
            $data->livro_assunto_livro_assunto_id = '';

            $this->form->setData($data);

            $this->onReload( $param );
        }
        catch (Exception $e)
        {
            $this->form->setData( $this->form->getData());

            new TMessage('error', $e->getMessage());
        }
    }

    public function onEditLivroAssuntoLivro( $param )
    {
        $data = $this->form->getData();

        // read session items
        $items = TSession::getValue('livro_assunto_livro_items');

        // get the session item
        $item = $items[$param['livro_assunto_livro_id_row_id']];

        $data->livro_assunto_livro_assunto_id = $item['livro_assunto_livro_assunto_id'];

        $data->livro_assunto_livro_id = $param['livro_assunto_livro_id_row_id'];

        // fill product fields
        $this->form->setData( $data );

        $this->onReload( $param );

    }

    public function onDeleteLivroAssuntoLivro( $param )
    {
        $data = $this->form->getData();

        $data->livro_assunto_livro_assunto_id = '';

        // clear form data
        $this->form->setData( $data );

        // read session items
        $items = TSession::getValue('livro_assunto_livro_items');

        // delete the item from session
        unset($items[$param['livro_assunto_livro_id_row_id']]);
        TSession::setValue('livro_assunto_livro_items', $items);

        // reload sale items
        $this->onReload( $param );

    }

    public function onReloadLivroAssuntoLivro( $param )
    {
        $items = TSession::getValue('livro_assunto_livro_items'); 

        $this->livro_assunto_livro_list->clear(); 

        if($items) 
        { 
            $cont = 1; 
            foreach ($items as $key => $item) 
            {
                $rowItem = new StdClass;

                $action_del = new TAction(array($this, 'onDeleteLivroAssuntoLivro')); 
                $action_del->setParameter('livro_assunto_livro_id_row_id', $key);
                $action_del->setParameter('row_data', base64_encode(serialize($item)));
                $action_del->setParameter('key', $key);

                $action_edi = new TAction(array($this, 'onEditLivroAssuntoLivro'));  
                $action_edi->setParameter('livro_assunto_livro_id_row_id', $key);  
                $action_edi->setParameter('row_data', base64_encode(serialize($item)));
                $action_edi->setParameter('key', $key);

                $button_del = new TButton('delete_livro_assunto_livro'.$cont);
                $button_del->setAction($action_del, '');
                $button_del->setFormName($this->form->getName());
                $button_del->class = 'btn btn-link btn-sm';
                $button_del->title = '';
                $button_del->setImage('far:trash-alt #dd5a43');

                $rowItem->delete = $button_del;

                $button_edi = new TButton('edit_livro_assunto_livro'.$cont);
                $button_edi->setAction($action_edi, '');
                $button_edi->setFormName($this->form->getName());
                $button_edi->class = 'btn btn-link btn-sm';
                $button_edi->title = '';
                $button_edi->setImage('far:edit #478fca');

                $rowItem->edit = $button_edi;

                $rowItem->livro_assunto_livro_assunto_id = '';
                if(isset($item['livro_assunto_livro_assunto_id']) && $item['livro_assunto_livro_assunto_id'])
                {
                    TTransaction::open('biblioteca');
                    $assunto = Assunto::find($item['livro_assunto_livro_assunto_id']);
                    if($assunto)
                    {
                        $rowItem->livro_assunto_livro_assunto_id = $assunto->render('{nome}');
                    }
                    TTransaction::close();
                }

                $row = $this->livro_assunto_livro_list->addItem($rowItem);

                $cont++;
            } 
        } 
    } 

    public function onShow($param = null)
    {

        TSession::setValue('livro_autor_livro_items', null);
        TSession::setValue('exemplar_livro_items', null);
        TSession::setValue('livro_assunto_livro_items', null);

        $this->onReload();

    } 

    public function onReload($params = null)
    {
        $this->loaded = TRUE;

        $this->onReloadLivroAutorLivro($params);
        $this->onReloadExemplarLivro($params);
        $this->onReloadLivroAssuntoLivro($params);
    }

    public function show() 
    { 
        $param = func_get_arg(0);
        if(!empty($param['current_tab']))
        {
            $this->form->setCurrentPage($param['current_tab']);
        }

        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') ) 
        { 
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }

}

