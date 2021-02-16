<?php

class LivroBatchDocument extends TPage
{
    private static $database = 'biblioteca';
    private static $activeRecord = 'Livro';
    private static $primaryKey = 'id';
    private static $htmlFile = 'app/documents/LivroDocumentTemplate.html';
    private static $formName = 'formDocument_Livro';

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
        $this->form->setFormTitle("Documento do livro em lote");

        $editora_id = new TDBUniqueSearch('editora_id', 'biblioteca', 'Editora', 'id', 'nome','nome asc'  );
        $colecao_id = new TDBCombo('colecao_id', 'biblioteca', 'Colecao', 'id', '{nome}','nome asc'  );
        $classificacao_id = new TDBCombo('classificacao_id', 'biblioteca', 'Classificacao', 'id', '{nome}','nome asc'  );
        $numero = new TEntry('numero');
        $autor_principal_id = new TDBUniqueSearch('autor_principal_id', 'biblioteca', 'Autor', 'id', 'nome','nome asc'  );
        $titulo = new TEntry('titulo');

        $editora_id->setMask('{nome}');
        $autor_principal_id->setMask('{nome}');

        $editora_id->setMinLength(2);
        $autor_principal_id->setMinLength(2);

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

            if (isset($data->editora_id) AND ( (is_scalar($data->editora_id) AND $data->editora_id !== '') OR (is_array($data->editora_id) AND (!empty($data->editora_id)) )) ) 
            {

                $criteria->add(new TFilter('editora_id', '=', $data->editora_id));
            }
            if (isset($data->colecao_id) AND ( (is_scalar($data->colecao_id) AND $data->colecao_id !== '') OR (is_array($data->colecao_id) AND (!empty($data->colecao_id)) )) ) 
            {

                $criteria->add(new TFilter('colecao_id', '=', $data->colecao_id));
            }
            if (isset($data->classificacao_id) AND ( (is_scalar($data->classificacao_id) AND $data->classificacao_id !== '') OR (is_array($data->classificacao_id) AND (!empty($data->classificacao_id)) )) ) 
            {

                $criteria->add(new TFilter('classificacao_id', '=', $data->classificacao_id));
            }
            if (isset($data->numero) AND ( (is_scalar($data->numero) AND $data->numero !== '') OR (is_array($data->numero) AND (!empty($data->numero)) )) ) 
            {

                $criteria->add(new TFilter('numero', 'like', "%{$data->numero}%"));
            }
            if (isset($data->autor_principal_id) AND ( (is_scalar($data->autor_principal_id) AND $data->autor_principal_id !== '') OR (is_array($data->autor_principal_id) AND (!empty($data->autor_principal_id)) )) ) 
            {

                $criteria->add(new TFilter('autor_principal_id', '=', $data->autor_principal_id));
            }
            if (isset($data->titulo) AND ( (is_scalar($data->titulo) AND $data->titulo !== '') OR (is_array($data->titulo) AND (!empty($data->titulo)) )) ) 
            {

                $criteria->add(new TFilter('titulo', 'like', "%{$data->titulo}%"));
            }

            $objects = Livro::getObjects($criteria, FALSE);
            if ($objects)
            {
                $output = '';

                $count = 1;
                $count_records = count($objects);

                foreach ($objects as $object)
                {

                    $html = new AdiantiHTMLDocumentParser(self::$htmlFile);
                    $html->setMaster($object);

                    $objectsExemplar_livro_id = Exemplar::where('livro_id', '=', $object->id)->load();

                    $html->setDetail('Exemplar.livro_id', $objectsExemplar_livro_id);

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

