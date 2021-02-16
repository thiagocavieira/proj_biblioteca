<?php

class EmprestimoLivroForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'biblioteca';
    private static $activeRecord = 'Emprestimo';
    private static $primaryKey = 'id';
    private static $formName = 'form_Emprestimo';

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
        $this->form->setFormTitle("CADASTRO DE EMPRÉSTIMO DE LIVRO");


        $leitor_id = new TDBUniqueSearch('leitor_id', 'biblioteca', 'Leitor', 'id', 'nome','nome asc'  );
        $exemplar_id = new TDBUniqueSearch('exemplar_id', 'biblioteca', 'Exemplar', 'id', 'codigo_barras','id asc'  );
        $dt_emprestimo = new TDate('dt_emprestimo');

        $leitor_id->addValidation("Leitor", new TRequiredValidator()); 
        $exemplar_id->addValidation("Exemplar", new TRequiredValidator()); 
        $dt_emprestimo->addValidation("Data de empréstimo", new TRequiredValidator()); 

        $dt_emprestimo->setValue(date('d/m/Y'));
        $dt_emprestimo->setDatabaseMask('yyyy-mm-dd');

        $leitor_id->setMinLength(1);
        $exemplar_id->setMinLength(1);

        $leitor_id->setSize('100%');
        $exemplar_id->setSize('100%');
        $dt_emprestimo->setSize('100%');

        $leitor_id->setMask('{nome}');
        $dt_emprestimo->setMask('dd/mm/yyyy');
        $exemplar_id->setMask('{livro->titulo}');

        $row1 = $this->form->addContent([new TFormSeparator("Leitor", '#333333', '18', '#eeeeee')]);
        $row2 = $this->form->addFields([new TLabel("Leitor:", '#ff0000', '14px', null)],[$leitor_id]);
        $row3 = $this->form->addContent([new TFormSeparator("Exemplar", '#333333', '18', '#eeeeee')]);
        $row4 = $this->form->addFields([new TLabel("Exemplar:", '#ff0000', '14px', null)],[$exemplar_id],[new TLabel("Data de empréstimo", '#ff0000', '14px', null)],[$dt_emprestimo]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'far:save #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');

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

            $object = new Emprestimo(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

             $date = new DateTime($detailObject->dt_emprestimo);
             $date->add(new DateInterval('P7D'));
             $object->dt_previsao = $date->format('Y-m-d');

            $object->store(); // save the object 

            $messageAction = new TAction(['EmprestimoLivroForm', 'onShow']);   

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

                $object = new Emprestimo($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

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

}

