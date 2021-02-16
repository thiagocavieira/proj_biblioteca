CREATE TABLE assunto( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE autor( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
      codigo int   , 
      num_classificacao int   , 
      num int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE categoria( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cidade( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
      estado_id int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE classificacao( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE colecao( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE configuracao( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      chave text   NOT NULL  , 
      valor text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE editora( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE emprestimo( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      exemplar_id int   NOT NULL  , 
      leitor_id int   NOT NULL  , 
      dt_emprestimo date   NOT NULL  , 
      dt_previsao date   NOT NULL  , 
      dt_devolucao date   , 
      valor_multa double   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE estado( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE evento( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      horario_inicial datetime   NOT NULL  , 
      horario_final datetime   NOT NULL  , 
      titulo text   , 
      cor text   , 
      observacao text   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE exemplar( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      livro_id int   NOT NULL  , 
      status_id int   NOT NULL  , 
      codigo_barras text   NOT NULL  , 
      dt_aqusicao date   , 
      preco_custo double   , 
      obs text   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE leitor( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      categoria_id int   NOT NULL  , 
      cidade_id int   NOT NULL  , 
      nome text   NOT NULL  , 
      dt_nascimento date   , 
      dt_cadastro date   , 
      endereco text   , 
      telefone text   , 
      email text   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE livro( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
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
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE livro_assunto( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      assunto_id int   NOT NULL  , 
      livro_id int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE livro_autor( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      autor_id int   NOT NULL  , 
      livro_id int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE status( 
      id  INT  AUTO_INCREMENT    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

 
  
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

  
