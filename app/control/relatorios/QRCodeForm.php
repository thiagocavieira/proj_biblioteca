<?php

class QRCodeForm extends TPage
{
    private static $database = 'biblioteca';
    private static $activeRecord = 'Exemplar';
    private static $primaryKey = 'id';
    private static $formName = 'formQRCode_Exemplar';

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
        $this->form->setFormTitle("Etiquetas QR code");

        $livro_id = new TDBUniqueSearch('livro_id', 'biblioteca', 'Livro', 'id', 'titulo','titulo asc'  );
        $status_id = new TDBCombo('status_id', 'biblioteca', 'Status', 'id', '{nome}','nome asc'  );
        $codigo_barras = new TEntry('codigo_barras');
        $codigo_barras_final = new TEntry('codigo_barras_final');
        $dt_aqusicao = new TDate('dt_aqusicao');
        $dt_aquisicao_final = new TDate('dt_aquisicao_final');

        $livro_id->setMinLength(1);

        $dt_aqusicao->setDatabaseMask('yyyy-mm-dd');
        $dt_aquisicao_final->setDatabaseMask('yyyy-mm-dd');

        $livro_id->setMask('{titulo}');
        $dt_aqusicao->setMask('dd/mm/yyyy');
        $dt_aquisicao_final->setMask('dd/mm/yyyy');

        $livro_id->setSize('100%');
        $dt_aqusicao->setSize(100);
        $status_id->setSize('100%');
        $codigo_barras->setSize('100%');
        $dt_aquisicao_final->setSize(100);
        $codigo_barras_final->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Livro:", null, '14px', null)],[$livro_id],[new TLabel("Status:", null, '14px', null)],[$status_id]);
        $row2 = $this->form->addFields([new TLabel("Código de barras inicial:", null, '14px', null)],[$codigo_barras],[new TLabel("Código de barras final:", null, '14px', null)],[$codigo_barras_final]);
        $row3 = $this->form->addFields([new TLabel("Data de aquisição inicial:", null, '14px', null)],[$dt_aqusicao],[new TLabel("Data de aquisição final:", null, '14px', null)],[$dt_aquisicao_final]);

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

            if (isset($data->livro_id) AND ( (is_scalar($data->livro_id) AND $data->livro_id !== '') OR (is_array($data->livro_id) AND (!empty($data->livro_id)) )) )
            {

                $criteria->add(new TFilter('livro_id', '=', $data->livro_id));
            }
            if (isset($data->status_id) AND ( (is_scalar($data->status_id) AND $data->status_id !== '') OR (is_array($data->status_id) AND (!empty($data->status_id)) )) )
            {

                $criteria->add(new TFilter('status_id', '=', $data->status_id));
            }
            if (isset($data->codigo_barras) AND ( (is_scalar($data->codigo_barras) AND $data->codigo_barras !== '') OR (is_array($data->codigo_barras) AND (!empty($data->codigo_barras)) )) )
            {

                $criteria->add(new TFilter('codigo_barras', '>=', $data->codigo_barras));
            }
            if (isset($data->codigo_barras_final) AND ( (is_scalar($data->codigo_barras_final) AND $data->codigo_barras_final !== '') OR (is_array($data->codigo_barras_final) AND (!empty($data->codigo_barras_final)) )) )
            {

                $criteria->add(new TFilter('codigo_barras', '<=', $data->codigo_barras_final));
            }
            if (isset($data->dt_aqusicao) AND ( (is_scalar($data->dt_aqusicao) AND $data->dt_aqusicao !== '') OR (is_array($data->dt_aqusicao) AND (!empty($data->dt_aqusicao)) )) )
            {

                $criteria->add(new TFilter('dt_aqusicao', '>=', $data->dt_aqusicao));
            }
            if (isset($data->dt_aquisicao_final) AND ( (is_scalar($data->dt_aquisicao_final) AND $data->dt_aquisicao_final !== '') OR (is_array($data->dt_aquisicao_final) AND (!empty($data->dt_aquisicao_final)) )) )
            {

                $criteria->add(new TFilter('dt_aqusicao', '<=', $data->dt_aquisicao_final));
            }

            TSession::setValue(__CLASS__.'_filter_data', $data);

            $properties = [];

            $properties['leftMargin']    = 10; // Left margin
            $properties['topMargin']     = 10; // Top margin
            $properties['labelWidth']    = 64; // Label width in mm
            $properties['labelHeight']   = 28; // Label height in mm
            $properties['spaceBetween']  = 4;  // Space between labels
            $properties['rowsPerPage']   = 10;  // Label rows per page
            $properties['colsPerPage']   = 3;  // Label cols per page
            $properties['fontSize']      = 12; // Text font size
            $properties['barcodeHeight'] = 14; // Barcode Height
            $properties['imageMargin']   = 0;

            $label  = "<b>{livro->titulo}</b>
#qrcode# 
 {codigo_barras} ";

            $bcgen = new AdiantiBarcodeDocumentGenerator('p', 'A4');
            $bcgen->setProperties($properties);
            $bcgen->setLabelTemplate($label);

            $class = self::$activeRecord;

            $objects = $class::getObjects($criteria);

            if ($objects)
            {
                foreach ($objects as $object)
                {

                    $bcgen->addObject($object);
                }

                $filename = 'tmp/barcode_'.uniqid().'.pdf';

                $bcgen->setBarcodeContent(' {codigo_barras} ');
                $bcgen->generate();
                $bcgen->save($filename);

                parent::openFile($filename);
                new TMessage('info', _t('QR Codes successfully generated'));
            }
            else
            {
                new TMessage('info', _t('No records found'));   
            }

            TTransaction::close();

            $this->form->setData($data);

        } 
        catch (Exception $e) 
        {
            $this->form->setData($data);

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

