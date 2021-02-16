CREATE TABLE assunto( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE autor( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
      codigo number(10)   , 
      num_classificacao number(10)   , 
      num number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
      estado_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE classificacao( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE colecao( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE configuracao( 
      id number(10)    NOT NULL , 
      chave CLOB    NOT NULL , 
      valor CLOB    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE editora( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE emprestimo( 
      id number(10)    NOT NULL , 
      exemplar_id number(10)    NOT NULL , 
      leitor_id number(10)    NOT NULL , 
      dt_emprestimo date    NOT NULL , 
      dt_previsao date    NOT NULL , 
      dt_devolucao date   , 
      valor_multa binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE evento( 
      id number(10)    NOT NULL , 
      horario_inicial timestamp(0)    NOT NULL , 
      horario_final timestamp(0)    NOT NULL , 
      titulo CLOB   , 
      cor CLOB   , 
      observacao CLOB   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE exemplar( 
      id number(10)    NOT NULL , 
      livro_id number(10)    NOT NULL , 
      status_id number(10)    NOT NULL , 
      codigo_barras CLOB    NOT NULL , 
      dt_aqusicao date   , 
      preco_custo binary_double   , 
      obs CLOB   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE leitor( 
      id number(10)    NOT NULL , 
      categoria_id number(10)    NOT NULL , 
      cidade_id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
      dt_nascimento date   , 
      dt_cadastro date   , 
      endereco CLOB   , 
      telefone CLOB   , 
      email CLOB   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro( 
      id number(10)    NOT NULL , 
      editora_id number(10)    NOT NULL , 
      colecao_id number(10)    NOT NULL , 
      classificacao_id number(10)    NOT NULL , 
      autor_principal_id number(10)    NOT NULL , 
      titulo CLOB    NOT NULL , 
      numero CLOB    NOT NULL , 
      isbn CLOB   , 
      edicao CLOB   , 
      volume CLOB   , 
      dt_publicacao date   , 
      local_publicacao CLOB   , 
      obs CLOB   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro_assunto( 
      id number(10)    NOT NULL , 
      assunto_id number(10)    NOT NULL , 
      livro_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE livro_autor( 
      id number(10)    NOT NULL , 
      autor_id number(10)    NOT NULL , 
      livro_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE status( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
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
 CREATE SEQUENCE assunto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER assunto_id_seq_tr 

BEFORE INSERT ON assunto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT assunto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE autor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER autor_id_seq_tr 

BEFORE INSERT ON autor FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT autor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE categoria_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER categoria_id_seq_tr 

BEFORE INSERT ON categoria FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT categoria_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cidade_id_seq_tr 

BEFORE INSERT ON cidade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT cidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE classificacao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER classificacao_id_seq_tr 

BEFORE INSERT ON classificacao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT classificacao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE colecao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER colecao_id_seq_tr 

BEFORE INSERT ON colecao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT colecao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE configuracao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER configuracao_id_seq_tr 

BEFORE INSERT ON configuracao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT configuracao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE editora_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER editora_id_seq_tr 

BEFORE INSERT ON editora FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT editora_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE emprestimo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER emprestimo_id_seq_tr 

BEFORE INSERT ON emprestimo FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT emprestimo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estado_id_seq_tr 

BEFORE INSERT ON estado FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT estado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE evento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER evento_id_seq_tr 

BEFORE INSERT ON evento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT evento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE exemplar_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER exemplar_id_seq_tr 

BEFORE INSERT ON exemplar FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT exemplar_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE leitor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER leitor_id_seq_tr 

BEFORE INSERT ON leitor FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT leitor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE livro_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER livro_id_seq_tr 

BEFORE INSERT ON livro FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT livro_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE livro_assunto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER livro_assunto_id_seq_tr 

BEFORE INSERT ON livro_assunto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT livro_assunto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE livro_autor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER livro_autor_id_seq_tr 

BEFORE INSERT ON livro_autor FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT livro_autor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE status_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER status_id_seq_tr 

BEFORE INSERT ON status FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT status_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
