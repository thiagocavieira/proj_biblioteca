<?php

class LeitorBatchDocument extends TPage
{
    private static $database = 'biblioteca';
    private static $activeRecord = 'Leitor';
    private static $primaryKey = 'id';
    private static $htmlFile = 'app/documents/LeitorDocumentTemplate.html';
    private static $formName = 'formDocument_Leitor';

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
        $this->form->setFormTitle("Ficha do leitor em lote");

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $categoria_id = new TDBCombo('categoria_id', 'biblioteca', 'Categoria', 'id', '{nome}','nome asc'  );
        $cidade_estado_id = new TDBCombo('cidade_estado_id', 'biblioteca', 'Estado', 'id', '{nome}','nome asc'  );
        $cidade_id = new TCombo('cidade_id');

        $cidade_estado_id->setChangeAction(new TAction([$this,'onChangecidade_estado_id']));

        $id->setSize(100);
        $nome->setSize('100%');
        $cidade_id->setSize('100%');
        $categoria_id->setSize('100%');
        $cidade_estado_id->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null)],[$id]);
        $row2 = $this->form->addFields([new TLabel("Nome:", null, '14px', null)],[$nome],[new TLabel("Categoria:", null, '14px', null)],[$categoria_id]);
        $row3 = $this->form->addFields([new TLabel("Estado:", null, '14px', null)],[$cidade_estado_id],[new TLabel("Cidade:", null, '14px', null)],[$cidade_id]);

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        $this->fireEvents( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_ongenerate = $this->form->addAction("Gerar", new TAction([$this, 'onGenerate']), 'fas:cog #ffffff');
        $btn_ongenerate->addStyleClass('btn-primary'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
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
            if (isset($data->nome) AND ( (is_scalar($data->nome) AND $data->nome !== '') OR (is_array($data->nome) AND (!empty($data->nome)) )) ) 
            {

                $criteria->add(new TFilter('nome', 'like', "%{$data->nome}%"));
            }
            if (isset($data->categoria_id) AND ( (is_scalar($data->categoria_id) AND $data->categoria_id !== '') OR (is_array($data->categoria_id) AND (!empty($data->categoria_id)) )) ) 
            {

                $criteria->add(new TFilter('categoria_id', '=', $data->categoria_id));
            }
            if (isset($data->cidade_estado_id) AND ( (is_scalar($data->cidade_estado_id) AND $data->cidade_estado_id !== '') OR (is_array($data->cidade_estado_id) AND (!empty($data->cidade_estado_id)) )) ) 
            {

                $criteria->add(new TFilter('cidade_id', 'in', "(SELECT id FROM cidade WHERE estado_id = '{$data->cidade_estado_id}')"));
            }
            if (isset($data->cidade_id) AND ( (is_scalar($data->cidade_id) AND $data->cidade_id !== '') OR (is_array($data->cidade_id) AND (!empty($data->cidade_id)) )) ) 
            {

                $criteria->add(new TFilter('cidade_id', '=', $data->cidade_id));
            }

            $objects = Leitor::getObjects($criteria, FALSE);
            if ($objects)
            {
                $output = '';

                $count = 1;
                $count_records = count($objects);

                foreach ($objects as $object)
                {

                    $html = new AdiantiHTMLDocumentParser(self::$htmlFile);
                    $html->setMaster($object);

                    $objectsEmprestimo_leitor_id = Emprestimo::where('leitor_id', '=', $object->id)->load();

                    $html->setDetail('Emprestimo.leitor_id', $objectsEmprestimo_leitor_id);

                    $html->process();

                    if ($count < $count_records)
                    {
                        $html->addPageBreak();
                    }

                    $content = $html->getContents();
                    $dom = pQuery::parseStr($content);
                    $body = $dom->query('body');

                    if($body->count() > 0)
                    {
                        $output .= $body->html();    
                    }
                    else 
                    {
                        $output .= $content;    
                    }

                    $count ++;
                }

                $dom = pQuery::parseStr(file_get_contents(self::$htmlFile));
                $body = $dom->query('body');
                if($body->count() > 0)
                {
                    $body->html('<div>{$body}</div>');
                    $html = $dom->html();

                    $output = str_replace('<div>{$body}</div>', $output, $html);
                }

                $document = 'tmp/'.uniqid().'.pdf'; 
                $html = AdiantiHTMLDocumentParser::newFromString($output);
                $html->saveAsPDF($document, 'A4', 'portrait');

                parent::openFile($document);
                new TMessage('info', _t('Document successfully generated'));
            }
            else
            {
                new TMessage('info', _t('No records found'));   
            }

            TTransaction::close();

            TSession::setValue(__CLASS__.'_filter_data', $data);

            $this->form->setData($data);
            $this->fireEvents($data);
        } 
        catch (Exception $e) 
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }
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

    public function onShow($param = null)
    {

    }

}

