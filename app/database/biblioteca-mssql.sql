CREATE TABLE assunto( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE autor( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
      codigo int   , 
      num_classificacao int   , 
      num int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
      estado_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE classificacao( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE colecao( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE configuracao( 
      id  INT IDENTITY    NOT NULL  , 
      chave nvarchar(max)   NOT NULL  , 
      valor nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE editora( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE emprestimo( 
      id  INT IDENTITY    NOT NULL  , 
      exemplar_id int   NOT NULL  , 
      leitor_id int   NOT NULL  , 
      dt_emprestimo date   NOT NULL  , 
      dt_previsao date   NOT NULL  , 
      dt_devolucao date   , 
      valor_multa float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE evento( 
      id  INT IDENTITY    NOT NULL  , 
      horario_inicial datetime2   NOT NULL  , 
      horario_final datetime2   NOT NULL  , 
      titulo nvarchar(max)   , 
      cor nvarchar(max)   , 
      observacao nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE exemplar( 
      id  INT IDENTITY    NOT NULL  , 
      livro_id int   NOT NULL  , 
      status_id int   NOT NULL  , 
      codigo_barras nvarchar(max)   NOT NULL  , 
      dt_aqusicao date   , 
      preco_custo float   , 
      obs nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE leitor( 
      id  INT IDENTITY    NOT NULL  , 
      categoria_id int   NOT NULL  , 
      cidade_id int   NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
      dt_nascimento date   , 
      dt_cadastro date   , 
      endereco nvarchar(max)   , 
      telefone nvarchar(max)   , 
      email nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro( 
      id  INT IDENTITY    NOT NULL  , 
      editora_id int   NOT NULL  , 
      colecao_id int   NOT NULL  , 
      classificacao_id int   NOT NULL  , 
      autor_principal_id int   NOT NULL  , 
      titulo nvarchar(max)   NOT NULL  , 
      numero nvarchar(max)   NOT NULL  , 
      isbn nvarchar(max)   , 
      edicao nvarchar(max)   , 
      volume nvarchar(max)   , 
      dt_publicacao date   , 
      local_publicacao nvarchar(max)   , 
      obs nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro_assunto( 
      id  INT IDENTITY    NOT NULL  , 
      assunto_id int   NOT NULL  , 
      livro_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro_autor( 
      id  INT IDENTITY    NOT NULL  , 
      autor_id int   NOT NULL  , 
      livro_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE status( 
      id  INT IDENTITY    NOT NULL  , 
      nome nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE cidade ADD CONSTRAINT fk_cidade_1 FOREIGN KEY (estado_id) references estado(id); 
ALTER TABLE emprestimo ADD CONSTRAINT fk_emprestimo_1 FOREIGN KEY (exemplar_id) references exemplar(id); 
ALTER TABLE emprestimo ADD CONSTRAINT fk_emprestimo_2 FOREIGN KEY (leitor_id) references leitor(id); 
ALTER TABLE exemplar ADD CONSTRAINT fk_exemplar_1 FOREIGN KEY (livro_id) references livro(id); 
ALTER TABLE exemplar ADD CONSTRAINT fk_exemplar_2 FOREIGN KEY (status_id) references status(id); 
ALTER TABLE leitor ADD CONSTRAINT fk_leitor_1 FOREIGN KEY (categoria_id) references categoria(id); 
ALTER TABLE leitor ADD CONSTRAINT fk_leitor_2 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE livro ADD CONSTRAINT fk_livro_1 FOREIGN KEY (autor_principal_id) references autor(id); 
ALTER TABLE livro ADD CONSTRAINT fk_livro_2 FOREIGN KEY (editora_id) references editora(id); 
ALTER TABLE livro ADD CONSTRAINT fk_livro_3 FOREIGN KEY (colecao_id) references colecao(id); 
ALTER TABLE livro ADD CONSTRAINT fk_livro_4 FOREIGN KEY (classificacao_id) references classificacao(id); 
ALTER TABLE livro_assunto ADD CONSTRAINT fk_livro_assunto_1 FOREIGN KEY (assunto_id) references assunto(id); 
ALTER TABLE livro_assunto ADD CONSTRAINT fk_livro_assunto_2 FOREIGN KEY (livro_id) references livro(id); 
ALTER TABLE livro_autor ADD CONSTRAINT fk_livro_autor_1 FOREIGN KEY (autor_id) references autor(id); 
ALTER TABLE livro_autor ADD CONSTRAINT fk_livro_autor_2 FOREIGN KEY (livro_id) references livro(id); 

  
