<?php

class LeitorDocument extends TPage
{
    private static $database = 'biblioteca';
    private static $activeRecord = 'Leitor';
    private static $primaryKey = 'id';
    private static $htmlFile = 'app/documents/LeitorDocumentTemplate.html';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {

    }

    public static function onGenerate($param)
    {
        try 
        {
            TTransaction::open(self::$database);

            $class = self::$activeRecord;
            $object = new $class($param['key']);

            $html = new AdiantiHTMLDocumentParser(self::$htmlFile);
            $html->setMaster($object);

            $objectsEmprestimo_leitor_id = Emprestimo::where('leitor_id', '=', $param['key'])->load();
            $html->setDetail('Emprestimo.leitor_id', $objectsEmprestimo_leitor_id);

            $html->process();

            $document = 'tmp/'.uniqid().'.pdf'; 
            $html->saveAsPDF($document, 'A4', 'portrait');

            TTransaction::close();

            if(empty($param['returnFile']))
            {
                parent::openFile($document);

                new TMessage('info', _t('Document successfully generated'));    
            }
            else
            {
                return $document;
            }
        } 
        catch (Exception $e) 
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());

            // undo all pending operations
            TTransaction::rollback();
        }
    }

}

