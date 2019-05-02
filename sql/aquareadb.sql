/*IN QUESTO FOGLIO SONO CONTENUTI TUTTI I DATI DELLE PISCINE, DELLO STAFF, ISCRITTI E DEGLI UTENTI 
ISCRITTI AL SITO WEB I QUALI POSSONO INTERAGIRE PER VEDERE I CORSI A CUI SONO ISCRITTI
O A CUI VOGLIONO ISCRIVERSI */


DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS requests;

CREATE TABLE IF NOT EXISTS users(
idu INT (10) NOT NULL UNIQUE  AUTO_INCREMENT,
email VARCHAR (100) PRIMARY KEY,
username VARCHAR (100) NOT NULL UNIQUE,
password VARCHAR (100) NOT NULL,
user_type ENUM('Admin','Mod') NOT NULL
);

CREATE TABLE IF NOT EXISTS news(
idn INT (10) NOT NULL UNIQUE AUTO_INCREMENT,
title VARCHAR(20) NOT NULL UNIQUE,
subtitle VARCHAR(60) NOT NULL,
content TEXT NOT NULL,
author VARCHAR(100) NOT NULL,
Data DATE,
URL VARCHAR(100) NOT NULL UNIQUE,			/*VARCHAR(2083)*/
FOREIGN KEY (author) REFERENCES users(username)
);

CREATE TABLE IF NOT EXISTS requests(
idr INT (10) NOT NULL UNIQUE AUTO_INCREMENT,
name VARCHAR(100) NOT NULL,
surname VARCHAR(100) NOT NULL,
date DATE NOT NULL,
email VARCHAR (100) PRIMARY KEY,
Sesso ENUM('Maschio','Femmina') NOT NULL,
note TEXT,
cv VARCHAR (100) NOT NULL UNIQUE
);


DROP TRIGGER IF EXISTS CHK_Inserimento_Utente;
DELIMITER //
CREATE TRIGGER CHK_Inserimento_Utente BEFORE INSERT ON users
FOR EACH ROW
BEGIN 
	DECLARE msg VARCHAR(200);
	IF EXISTS(SELECT * FROM users WHERE ((email LIKE NEW.email) OR (Username LIKE NEW.Username))) THEN
		IF EXISTS (SELECT * FROM users WHERE (email LIKE NEW.email)) THEN
				SET msg='La mail utilizzata ha già un profilo collegato';
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
			ELSE 
				SET msg='Username già utilizzato';
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
			END IF;
	ELSE
		SET NEW.password= MD5(NEW.password);
	END IF;
END
//
DELIMITER ;

DROP TRIGGER IF EXISTS CHK_Inserimento_news;
DELIMITER //
CREATE TRIGGER CHK_Inserimento_news BEFORE INSERT ON news
FOR EACH ROW
BEGIN 
	DECLARE msg VARCHAR(200);
	IF NOT EXISTS(SELECT * FROM users WHERE (Username LIKE NEW.author)) THEN
				SET msg='L`autore non esiste';
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
	END IF;
END
//
DELIMITER ;

DROP PROCEDURE IF EXISTS Ingresso;
DELIMITER //
CREATE PROCEDURE Ingresso (usern VARCHAR(50), pass VARCHAR(50))
BEGIN
	DECLARE msg VARCHAR(200);
	IF (SELECT COUNT(*) FROM  users WHERE ((email LIKE usern) OR (Username LIKE usern)) AND (Password LIKE pass))>0 THEN
		IF (SELECT Abbonamento FROM  users WHERE ((email LIKE usern) OR (Username LIKE usern)) AND (Password LIKE pass)) = 'NO' THEN
			SELECT M.Messaggi FROM  users U INNER JOIN Messaggi M ON U.email=M.email WHERE ((U.email LIKE usern) OR (U.Username LIKE usern)) AND (U.Password LIKE pass);
		ELSE
			SELECT P.Nome AS NomeP, P.Cognome AS CognomeP, P.Telefono, I.Inizio, I.Fine, C.NomeCorso, C.Costo
			FROM users U, Persona P, Iscritti I INNER JOIN Corso C ON I.CodCorso=C.CodiceC
			WHERE ((email LIKE usern) OR (Username LIKE usern)) AND (Password LIKE pass);
		END IF;
	ELSE
    SET msg='User o Password errati';
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END
//
DELIMITER ;

DROP PROCEDURE IF EXISTS Inserimento_Utente;
DELIMITER //
CREATE PROCEDURE Inserimento_Utente(IN email VARCHAR (50), IN username VARCHAR (20), IN password VARCHAR (50), IN user_type ENUM('Admin','Mod'))
BEGIN 
		INSERT INTO users (email, username, password, user_type) 
        VALUES (email, username, password, user_type);
END
//
DELIMITER ;

DROP PROCEDURE IF EXISTS Inserimento_news;
DELIMITER //
CREATE PROCEDURE Inserimento_news(IN title VARCHAR (20), IN subtitle VARCHAR (60), IN content TEXT, IN author VARCHAR (100), IN Data DATE, IN URL VARCHAR(100))
BEGIN 
		INSERT INTO news ( title, subtitle, content, author, Data, URL)
        VALUES (title, subtitle, content, author, Data, URL);
END
//
DELIMITER ;







