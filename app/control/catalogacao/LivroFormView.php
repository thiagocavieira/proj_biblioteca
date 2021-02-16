<?php

class LivroFormView extends TPage
{
    protected $form; // form
    private static $database = 'biblioteca';
    private static $activeRecord = 'Livro';
    private static $primaryKey = 'id';
    private static $formName = 'formView_Livro';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        $livro = new Livro($param['key']);
        // define the form title
        $this->form->setFormTitle("Dados do livro");

        $label1 = new TLabel("Id:", '#333333', '12px', '');
        $text1 = new TTextDisplay($livro->id, '#333333', '12px', '');
        $label2 = new TLabel("Editora:", '#333333', '12px', '');
        $text2 = new TTextDisplay($livro->editora->nome, '#333333', '12px', '');
        $label3 = new TLabel("Coleção:", '#333333', '12px', '');
        $text3 = new TTextDisplay($livro->colecao->nome, '#333333', '12px', '');
        $label10 = new TLabel("Classificação:", '#333333', '12px', '');
        $text4 = new TTextDisplay($livro->classificacao->nome, '#333333', '12px', '');
        $label5 = new TLabel("Autor principal:", '#333333', '12px', '');
        $text5 = new TTextDisplay($livro->autor_principal->nome, '#333333', '12px', '');
        $label22 = new TLabel("Título:", '#333333', '12px', '');
        $text6 = new TTextDisplay($livro->titulo, '#333333', '12px', '');
        $label7 = new TLabel("Número de chamada:", '#333333', '12px', '');
        $text7 = new TTextDisplay($livro->numero, '#333333', '12px', '');
        $label4 = new TLabel("ISBN:", '#333333', '12px', '');
        $text8 = new TTextDisplay($livro->isbn, '#333333', '12px', '');
        $label9 = new TLabel("Edição:", '#333333', '12px', '');
        $text9 = new TTextDisplay($livro->edicao, '#333333', '12px', '');
        $label6 = new TLabel("Volume:", '#333333', '12px', '');
        $text10 = new TTextDisplay($livro->volume, '#333333', '12px', '');
        $label11 = new TLabel("Data de publicação:", '#333333', '12px', '');
        $text11 = new TTextDisplay(TDate::convertToMask($livro->dt_publicacao, 'yyyy-mm-dd', 'dd/mm/yyyy'), '#333333', '12px', '');
        $label8 = new TLabel("Local de publicação:", '#333333', '12px', '');
        $text12 = new TTextDisplay($livro->local_publicacao, '#333333', '12px', '');
        $label13 = new TLabel("Observação:", '#333333', '12px', '');
        $text13 = new TTextDisplay($livro->obs, '#333333', '12px', '');


        $row1 = $this->form->addFields([$label1],[$text1]);
        $row2 = $this->form->addFields([$label2],[$text2]);
        $row3 = $this->form->addFields([$label3],[$text3],[$label10],[$text4]);
        $row4 = $this->form->addFields([$label5],[$text5],[$label22],[$text6]);
        $row5 = $this->form->addFields([$label7],[$text7],[$label4],[$text8]);
        $row6 = $this->form->addFields([$label9],[$text9],[$label6],[$text10]);
        $row7 = $this->form->addFields([$label11],[$text11],[$label8],[$text12]);
        $row8 = $this->form->addFields([$label13],[$text13]);

        // create the form actions
        $btnLabel = new TLabel("Editar");
        $btnLabel->setFontSize('12px'); 
        $btnLabel->setFontColor('#333333'); 

        $btn = $this->form->addHeaderAction($btnLabel, new TAction(['LivroForm', 'onEdit'],['key'=>$livro->id]), 'fas:pencil-alt #4183d7'); 

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);

        TTransaction::close();
        parent::add($container);

    }

    public function onShow($param = null)
    {     

    }

}

