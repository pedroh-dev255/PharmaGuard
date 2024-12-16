CREATE DATABASE u887045081_medicamentos;
USE u887045081_medicamentos;


CREATE TABLE local(
    id      	int AUTO_INCREMENT NOT NULL,
    nome	    varchar(200) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE medicos(
    id          int AUTO_INCREMENT NOT NULL,
	nome	    varchar(200) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE medicamento(
    id Primária	int AUTO_INCREMENT NOT NULL,
    nome        varchar(300) NOT NULL,
    principio	varchar(500) NOT NULL,
    validade	date NOT NULL,	
    adicionado	date NOT NULL,
    retirado	datetime,
    solicitado  int,
	id_arm      int,
	id_user	    int,
    PRIMARY KEY(id),
    FOREIGN KEY(id_arm)     REFERENCES   local(id),
    FOREIGN KEY(solicitado) REFERENCES medicos(id)
);

INSERT INTO medicos(nome) VALUES ('Vencidos');