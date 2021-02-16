<?php

class LeitorForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'biblioteca';
    private static $activeRecord = 'Leitor';
    private static $primaryKey = 'id';
    private static $formName = 'form_Leitor';

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
        $this->form->setFormTitle("Cadastro de leitor");


        $id = new TEntry('id');
        $dt_cadastro = new TDate('dt_cadastro');
        $categoria_id = new TDBCombo('categoria_id', 'biblioteca', 'Categoria', 'id', '{nome}','nome asc'  );
        $nome = new TEntry('nome');
        $dt_nascimento = new TDate('dt_nascimento');
        $email = new TEntry('email');
        $telefone = new TEntry('telefone');
        $cidade_estado_id = new TDBCombo('cidade_estado_id', 'biblioteca', 'Estado', 'id', '{nome}','nome asc'  );
        $cidade_id = new TCombo('cidade_id');
        $endereco = new TEntry('endereco');

        $cidade_estado_id->setChangeAction(new TAction([$this,'onChangecidade_estado_id']));

        $categoria_id->addValidation("Categoria", new TRequiredValidator()); 
        $nome->addValidation("Nome", new TRequiredValidator()); 
        $cidade_id->addValidation("Cidade", new TRequiredValidator()); 

        $dt_cadastro->setValue(date('d/n/Y'));

        $id->setEditable(false);
        $dt_cadastro->setEditable(false);

        $dt_cadastro->setMask('dd/mm/yyyy');
        $dt_nascimento->setMask('dd/mm/yyyy');

        $dt_cadastro->setDatabaseMask('yyyy-mm-dd');
        $dt_nascimento->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(100);
        $nome->setSize('100%');
        $email->setSize('100%');
        $dt_cadastro->setSize(100);
        $telefone->setSize('100%');
        $endereco->setSize('100%');
        $cidade_id->setSize('100%');
        $dt_nascimento->setSize(100);
        $categoria_id->setSize('100%');
        $cidade_estado_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id],[new TLabel("Data de cadastro:", null, '14px', null)],[$dt_cadastro]);
        $row2 = $this->form->addFields([new TLabel("Categoria:", '#ff0000', '14px', null)],[$categoria_id],[],[]);
        $row3 = $this->form->addContent([new TFormSeparator("Dados pessoais", '#333333', '18', '#eeeeee')]);
        $row4 = $this->form->addFields([new TLabel("Nome:", '#ff0000', '14px', null)],[$nome],[new TLabel("Data de nascimento:", null, '14px', null)],[$dt_nascimento]);
        $row5 = $this->form->addFields([new TLabel("Email", null, '14px', null)],[$email],[new TLabel("Telefone:", null, '14px', null)],[$telefone]);
        $row6 = $this->form->addContent([new TFormSeparator("Endereço", '#333333', '18', '#eeeeee')]);
        $row7 = $this->form->addFields([new TLabel("Estado:", '#ff0000', '14px', null)],[$cidade_estado_id],[new TLabel("Cidade:", '#ff0000', '14px', null)],[$cidade_id]);
        $row8 = $this->form->addFields([new TLabel("Endereço:", null, '14px', null)],[$endereco]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['LeitorList', 'onShow']), 'far:arrow-alt-circle-left #3c8dbc');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        parent::add($container);

    }

    public static function onChangecidade_estado_id($param)
    {
        try
        {

            if (isset($param['cidade_estado_id']) && $param['cidade_estado_id'])
            { 
                $criteria = TCriteria::create(['estado_id' => (int) $param['cidade_estado_id']]);
                TDBCombo::reloadFromModel(self::$formName, 'cidade_id', 'biblioteca', 'Cidade', 'id', '{nome}', 'nome asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'cidade_id'); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
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

            $object = new Leitor(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $this->fireEvents($object);

            $messageAction = new TAction(['LeitorList', 'onShow']);   

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

                $object = new Leitor($key); // instantiates the Active Record 

                                $object->cidade_estado_id = $object->cidade->estado_id;

                $this->form->setData($object); // fill the form 

                    $this->fireEvents($object);

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

    }

    public function onShow($param = null)
    {

    } 

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->cidade_estado_id))
            {
                $value = $object->cidade_estado_id;

                $obj->cidade_estado_id = $value;
            }
            if(isset($object->cidade_id))
            {
                $value = $object->cidade_id;

                $obj->cidade_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->cidade->estado_id))
            {
                $value = $object->cidade->estado_id;

                $obj->cidade_estado_id = $value;
            }
            if(isset($object->cidade_id))
            {
                $value = $object->cidade_id;

                $obj->cidade_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

}

