/*IN QUESTO FOGLIO SONO CONTENUTI TUTTI I DATI DELLE PISCINE, DELLO STAFF, ISCRITTI E DEGLI UTENTI 
ISCRITTI AL SITO WEB I QUALI POSSONO INTERAGIRE PER VEDERE I CORSI A CUI SONO ISCRITTI
O A CUI VOGLIONO ISCRIVERSI */

DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS News;

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
URL VARCHAR(1000) NOT NULL UNIQUE,			/*VARCHAR(2083)*/
FOREIGN KEY (author) REFERENCES Users(Username)
);



DROP TRIGGER IF EXISTS CHK_Inserimento_Utente;
DELIMITER //
CREATE TRIGGER CHK_Inserimento_Utente BEFORE INSERT ON Users
FOR EACH ROW
BEGIN 
	DECLARE msg VARCHAR(200);
	IF EXISTS(SELECT * FROM Users WHERE ((email LIKE NEW.email) OR (Username LIKE NEW.Username))) THEN
		IF EXISTS (SELECT * FROM Users WHERE (email LIKE NEW.email)) THEN
				SET msg='La mail utilizzata ha già un profilo collegato';
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
			ELSE 
				SET msg='Username già utilizzato';
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
			END IF;
	END IF;
END
//
DELIMITER ;

DROP TRIGGER IF EXISTS CHK_Inserimento_News;
DELIMITER //
CREATE TRIGGER CHK_Inserimento_News BEFORE INSERT ON News
FOR EACH ROW
BEGIN 
	DECLARE msg VARCHAR(200);
	IF NOT EXISTS(SELECT * FROM Users WHERE (Username LIKE NEW.author)) THEN
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
	IF (SELECT COUNT(*) FROM  Users WHERE ((email LIKE usern) OR (Username LIKE usern)) AND (Password LIKE pass))>0 THEN
		IF (SELECT Abbonamento FROM  Users WHERE ((email LIKE usern) OR (Username LIKE usern)) AND (Password LIKE pass)) = 'NO' THEN
			SELECT M.Messaggi FROM  Users U INNER JOIN Messaggi M ON U.email=M.email WHERE ((U.email LIKE usern) OR (U.Username LIKE usern)) AND (U.Password LIKE pass);
		ELSE
			SELECT P.Nome AS NomeP, P.Cognome AS CognomeP, P.Telefono, I.Inizio, I.Fine, C.NomeCorso, C.Costo
			FROM Users U, Persona P, Iscritti I INNER JOIN Corso C ON I.CodCorso=C.CodiceC
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
		INSERT INTO Users (email, username, password, user_type) 
        VALUES (email, username, password, user_type);
END
//
DELIMITER ;

DROP PROCEDURE IF EXISTS Inserimento_News;
DELIMITER //
CREATE PROCEDURE Inserimento_News(IN title VARCHAR (20), IN subtitle VARCHAR (60), IN content TEXT, IN author VARCHAR (100), IN Data DATE, IN URL VARCHAR(1000))
BEGIN 
		INSERT INTO News ( title, subtitle, content, author, Data, URL)
        VALUES (title, subtitle, content, author, Data, URL);
END
//
DELIMITER ;









