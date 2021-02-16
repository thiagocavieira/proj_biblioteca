<?php

class LivroDrawing extends TPage
{
    private static $database = 'biblioteca';
    private static $activeRecord = 'Livro';
    private static $primaryKey = 'id';
    private static $svgFile = 'app/drawings/LivroDrawingTemplate.svg';

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

            $html = new AdiantiHTMLDocumentParser();
            $html->setMaster($object);

            $html->parseImage(self::$svgFile);
            $html->process();

            $document = 'tmp/'.uniqid().'.pdf'; 
            $html->saveAsPDF($document, [595, 841]);

            TTransaction::close();

            parent::openFile($document);

            new TMessage('info', _t('Drawing successfully generated'));
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

