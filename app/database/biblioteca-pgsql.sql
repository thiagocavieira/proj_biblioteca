CREATE TABLE assunto( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE autor( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
      codigo integer   , 
      num_classificacao integer   , 
      num integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
      estado_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE classificacao( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE colecao( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE configuracao( 
      id  SERIAL    NOT NULL  , 
      chave text   NOT NULL  , 
      valor text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE editora( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE emprestimo( 
      id  SERIAL    NOT NULL  , 
      exemplar_id integer   NOT NULL  , 
      leitor_id integer   NOT NULL  , 
      dt_emprestimo date   NOT NULL  , 
      dt_previsao date   NOT NULL  , 
      dt_devolucao date   , 
      valor_multa float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE evento( 
      id  SERIAL    NOT NULL  , 
      horario_inicial timestamp   NOT NULL  , 
      horario_final timestamp   NOT NULL  , 
      titulo text   , 
      cor text   , 
      observacao text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE exemplar( 
      id  SERIAL    NOT NULL  , 
      livro_id integer   NOT NULL  , 
      status_id integer   NOT NULL  , 
      codigo_barras text   NOT NULL  , 
      dt_aqusicao date   , 
      preco_custo float   , 
      obs text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE leitor( 
      id  SERIAL    NOT NULL  , 
      categoria_id integer   NOT NULL  , 
      cidade_id integer   NOT NULL  , 
      nome text   NOT NULL  , 
      dt_nascimento date   , 
      dt_cadastro date   , 
      endereco text   , 
      telefone text   , 
      email text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro( 
      id  SERIAL    NOT NULL  , 
      editora_id integer   NOT NULL  , 
      colecao_id integer   NOT NULL  , 
      classificacao_id integer   NOT NULL  , 
      autor_principal_id integer   NOT NULL  , 
      titulo text   NOT NULL  , 
      numero text   NOT NULL  , 
      isbn text   , 
      edicao text   , 
      volume text   , 
      dt_publicacao date   , 
      local_publicacao text   , 
      obs text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro_assunto( 
      id  SERIAL    NOT NULL  , 
      assunto_id integer   NOT NULL  , 
      livro_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro_autor( 
      id  SERIAL    NOT NULL  , 
      autor_id integer   NOT NULL  , 
      livro_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE status( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
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

  
