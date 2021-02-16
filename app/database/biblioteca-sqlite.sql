PRAGMA foreign_keys=OFF; 

CREATE TABLE assunto( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE autor( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
      codigo int   , 
      num_classificacao int   , 
      num int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
      estado_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(estado_id) REFERENCES estado(id)) ; 

CREATE TABLE classificacao( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE colecao( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE configuracao( 
      id  INTEGER    NOT NULL  , 
      chave text   NOT NULL  , 
      valor text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE editora( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE emprestimo( 
      id  INTEGER    NOT NULL  , 
      exemplar_id int   NOT NULL  , 
      leitor_id int   NOT NULL  , 
      dt_emprestimo date   NOT NULL  , 
      dt_previsao date   NOT NULL  , 
      dt_devolucao date   , 
      valor_multa double   , 
 PRIMARY KEY (id),
FOREIGN KEY(exemplar_id) REFERENCES exemplar(id),
FOREIGN KEY(leitor_id) REFERENCES leitor(id)) ; 

CREATE TABLE estado( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE evento( 
      id  INTEGER    NOT NULL  , 
      horario_inicial datetime   NOT NULL  , 
      horario_final datetime   NOT NULL  , 
      titulo text   , 
      cor text   , 
      observacao text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE exemplar( 
      id  INTEGER    NOT NULL  , 
      livro_id int   NOT NULL  , 
      status_id int   NOT NULL  , 
      codigo_barras text   NOT NULL  , 
      dt_aqusicao date   , 
      preco_custo double   , 
      obs text   , 
 PRIMARY KEY (id),
FOREIGN KEY(livro_id) REFERENCES livro(id),
FOREIGN KEY(status_id) REFERENCES status(id)) ; 

CREATE TABLE leitor( 
      id  INTEGER    NOT NULL  , 
      categoria_id int   NOT NULL  , 
      cidade_id int   NOT NULL  , 
      nome text   NOT NULL  , 
      dt_nascimento date   , 
      dt_cadastro date   , 
      endereco text   , 
      telefone text   , 
      email text   , 
 PRIMARY KEY (id),
FOREIGN KEY(categoria_id) REFERENCES categoria(id),
FOREIGN KEY(cidade_id) REFERENCES cidade(id)) ; 

CREATE TABLE livro( 
      id  INTEGER    NOT NULL  , 
      editora_id int   NOT NULL  , 
      colecao_id int   NOT NULL  , 
      classificacao_id int   NOT NULL  , 
      autor_principal_id int   NOT NULL  , 
      titulo text   NOT NULL  , 
      numero text   NOT NULL  , 
      isbn text   , 
      edicao text   , 
      volume text   , 
      dt_publicacao date   , 
      local_publicacao text   , 
      obs text   , 
 PRIMARY KEY (id),
FOREIGN KEY(autor_principal_id) REFERENCES autor(id),
FOREIGN KEY(editora_id) REFERENCES editora(id),
FOREIGN KEY(colecao_id) REFERENCES colecao(id),
FOREIGN KEY(classificacao_id) REFERENCES classificacao(id)) ; 

CREATE TABLE livro_assunto( 
      id  INTEGER    NOT NULL  , 
      assunto_id int   NOT NULL  , 
      livro_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(assunto_id) REFERENCES assunto(id),
FOREIGN KEY(livro_id) REFERENCES livro(id)) ; 

CREATE TABLE livro_autor( 
      id  INTEGER    NOT NULL  , 
      autor_id int   NOT NULL  , 
      livro_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(autor_id) REFERENCES autor(id),
FOREIGN KEY(livro_id) REFERENCES livro(id)) ; 

CREATE TABLE status( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
 
  
