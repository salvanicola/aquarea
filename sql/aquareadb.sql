/*IN QUESTO FOGLIO SONO CONTENUTI TUTTI I DATI DELLE PISCINE, DELLO STAFF, ISCRITTI E DEGLI UTENTI 
ISCRITTI AL SITO WEB I QUALI POSSONO INTERAGIRE PER VEDERE I CORSI A CUI SONO ISCRITTI
O A CUI VOGLIONO ISCRIVERSI */
CREATE TABLE IF NOT EXISTS Persona(
CF VARCHAR(16) PRIMARY KEY,
Nome CHAR(20) NOT NULL,
Cognome CHAR(20) NOT NULL,
Nazione CHAR(50) NOT NULL,
CAP INT(5) NOT NULL,
Via VARCHAR(50) NOT NULL,
Telefono VARCHAR(10) NOT NULL,
Mail VARCHAR(50) UNIQUE, 
FOREIGN KEY (Mail) REFERENCES Utente(Email)
);

CREATE TABLE IF NOT EXISTS Piscina(
CodiceP VARCHAR(4) PRIMARY KEY,
PIVA VARCHAR (11) NOT NULL UNIQUE,
Nome VARCHAR (50) NOT NULL,
Proprietario VARCHAR (16) NOT NULL REFERENCES Persona(CF),
Luogo INT(5) NOT NULL REFERENCES Luogo(Cap),
Telefono INT(10) NOT NULL UNIQUE,
Mail VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Utente(
Email VARCHAR (50) PRIMARY KEY,
Username VARCHAR (20) NOT NULL UNIQUE,
Password VARCHAR (50) NOT NULL,
Abbonamento ENUM('Si','No') NOT NULL
);

CREATE TABLE IF NOT EXISTS Messaggi(
Email VARCHAR (50) NOT NULL,
Messaggi VARCHAR (200),
FOREIGN KEY (Email) REFERENCES Utente(Email)
);

CREATE TABLE IF NOT EXISTS Staff(
CodF VARCHAR(16) NOT NULL UNIQUE REFERENCES Persona(CF),
CodP VARCHAR(4) NOT NULL REFERENCES Piscina(CodiceP)
);

CREATE TABLE IF NOT EXISTS Corso(
CodiceC VARCHAR(4) PRIMARY KEY,
CodP VARCHAR(4) NOT NULL REFERENCES Piscina(CodiceP),
Nome CHAR (10) NOT NULL,
Costo INT (100) NOT NULL,
Istruttore VARCHAR (16) REFERENCES Persona(CF),
Orario TIME NOT NULL,
Giorno ENUM('Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica')
);

CREATE TABLE IF NOT EXISTS Luogo(
CAP INT (5) PRIMARY KEY,
Città CHAR (20) NOT NULL,
Via VARCHAR (50) NOT NULL,
Nazione CHAR (50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Iscritti(
CodCorso VARCHAR(4) NOT NULL REFERENCES Corso(CodiceC),
CodIscritto INT (16) NOT NULL REFERENCES Persona(CF),
Inizio DATE NOT NULL,
Fine DATE NOT NULL
);

DROP TRIGGER IF EXISTS CHK_Durata;
DELIMITER //
CREATE TRIGGER CHK_Durata BEFORE INSERT ON Iscritti
FOR EACH ROW
BEGIN 
	DECLARE msg VARCHAR(200);
	IF((NEW.Fine-NEW.Inizio)>1) THEN
		SET msg='La durata del corso non può superare l`anno';
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
		SET NEW.CodCorso=NULL;
		SET NEW.CodIscritto=NULL;
	END IF;
END
//
DELIMITER ;    

DROP TRIGGER IF EXISTS CHK_Durata2;
DELIMITER //
CREATE TRIGGER CHK_Durata2 BEFORE UPDATE ON Iscritti
FOR EACH ROW
BEGIN 
	DECLARE msg VARCHAR(200);
	IF((NEW.Fine-NEW.Inizio)>1) THEN
		SET msg='La durata del corso non può superare l`anno';
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
		SET NEW.CodCorso=NULL;
		SET NEW.CodIscritto=NULL;
	END IF;
END
//
DELIMITER ;  

DROP TRIGGER IF EXISTS CHK_Iscrizioni;
DELIMITER //
CREATE TRIGGER CHK_Iscrizioni BEFORE INSERT ON Iscritti
FOR EACH ROW
BEGIN
	DECLARE msg VARCHAR(200);
    DECLARE t INT(2);
	SET t=(SELECT COUNT(*) FROM Iscrizioni WHERE NEW.CodIscritto=CodIscritto);
    IF (t>0) THEN
		SET msg='L`utente è già iscritto ad un altro corso della palestra';
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
		SET NEW.CodCorso=NULL;
		SET NEW.CodIscritto=NULL;
	END IF;
END
//
DELIMITER ;

 

DROP TRIGGER IF EXISTS CHK_Iscrizioni2;
DELIMITER //
CREATE TRIGGER CHK_Iscrizioni2 BEFORE UPDATE ON Iscritti
FOR EACH ROW
BEGIN
	DECLARE msg VARCHAR(200);
    DECLARE t INT(2);
	SET t=(SELECT COUNT(*) FROM Iscrizioni WHERE NEW.CodIscritto=CodIscritto);
    IF (t>0) THEN
		SET msg='L`utente è già iscritto ad un altro corso della palestra';
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
		SET NEW.CodCorso=NULL;
		SET NEW.CodIscritto=NULL;
	END IF;
END
//
DELIMITER ; 

DROP TRIGGER IF EXISTS CHK_Inserimento_Utente;
DELIMITER //
CREATE TRIGGER CHK_Inserimento_Utente BEFORE INSERT ON Utente
FOR EACH ROW
BEGIN 
	DECLARE msg VARCHAR(200);
	IF EXISTS(SELECT * FROM Utente WHERE ((Email LIKE NEW.Email) OR (Username LIKE NEW.Username))) THEN
		IF EXISTS (SELECT * FROM Utente WHERE (Email LIKE NEW.Email)) THEN
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

DROP TRIGGER IF EXISTS CHK_Inserimento_Persona;
DELIMITER //
CREATE TRIGGER CHK_Inserimento_Persona BEFORE INSERT ON Persona
FOR EACH ROW
BEGIN
	DECLARE msg VARCHAR(200);
    IF EXISTS(SELECT * FROM Persona WHERE CF=NEW.CF) THEN
    SET msg='La persona è già registrata all`interno della struttura';
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    SET NEW.CF=NULL;
    END IF;
END
//
DELIMITER ;

DROP TRIGGER IF EXISTS CHK_Abbonamento;
DELIMITER //
CREATE TRIGGER CHK_Abbonamento AFTER INSERT ON Persona
FOR EACH ROW
BEGIN
	DECLARE T VARCHAR(50);
	IF EXISTS(SELECT * FROM Persona P INNER JOIN Utente U ON P.Mail=U.Email WHERE U.Abbonamento='No') THEN
    SET T=(SELECT Mail FROM Persona P INNER JOIN Utente U ON P.Mail=U.Email WHERE U.Abbonamento='No');
    UPDATE Utente
    SET Abbonamento='Si'
    WHERE T=Email;
    END IF;
END
//
DELIMITER ;

DROP TRIGGER IF EXISTS CHK_Abbonamento2;
DELIMITER //
CREATE TRIGGER CHK_Abbonamento2 AFTER INSERT ON Utente
FOR EACH ROW
BEGIN
	DECLARE T VARCHAR(50);
	IF EXISTS(SELECT * FROM Persona P INNER JOIN Utente U ON P.Mail=U.Email WHERE U.Abbonamento='No') THEN
    SET T=(SELECT Mail FROM Persona P INNER JOIN Utente U ON P.Mail=U.Email WHERE U.Abbonamento='No');
    UPDATE Utente
    SET Abbonamento='Si'
    WHERE T=Email;
    END IF;
END
//
DELIMITER ;

DROP PROCEDURE IF EXISTS Ingresso;
DELIMITER //
CREATE PROCEDURE Ingresso (usern VARCHAR(50), pass VARCHAR(50))
BEGIN
	DECLARE msg VARCHAR(200);
	IF (SELECT COUNT(*) FROM  Utente WHERE ((Email LIKE usern) OR (Username LIKE usern)) AND (Password LIKE pass))>0 THEN
		IF (SELECT Abbonamento FROM  Utente WHERE ((Email LIKE usern) OR (Username LIKE usern)) AND (Password LIKE pass)) = 'NO' THEN
			SELECT M.Messaggi FROM  Utente U INNER JOIN Messaggi M ON U.Email=M.Email WHERE ((U.Email LIKE usern) OR (U.Username LIKE usern)) AND (U.Password LIKE pass);
		ELSE
			SELECT P.Nome AS NomeP, P.Cognome AS CognomeP, P.Telefono, I.Inizio, I.Fine, C.NomeCorso, C.Costo
			FROM Utente U, Persona P, Iscritti I INNER JOIN Corso C ON I.CodCorso=C.CodiceC
			WHERE ((Email LIKE usern) OR (Username LIKE usern)) AND (Password LIKE pass);
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
CREATE PROCEDURE Inserimento_Utente(IN Mail VARCHAR (50), IN username VARCHAR (20), IN password VARCHAR (50))
BEGIN 
		INSERT INTO Utente 
        VALUES (Mail, username, password, 'No');
END
//
DELIMITER ;

DROP PROCEDURE IF EXISTS Inserimento_Persona;
DELIMITER //
CREATE PROCEDURE Inserimento_Persona(CF VARCHAR(16), Nome CHAR(20), Cognome CHAR(20), Nazione CHAR(50), CAP INT(5), Via VARCHAR(50), Telefono VARCHAR(10), Mail VARCHAR(50))
BEGIN
	INSERT INTO Persona
    VALUES	(CF, Nome, Cognome, Nazione, CAP, Via, Telefono, Mail);
END
//
DELIMITER ;

DROP PROCEDURE IF EXISTS Inserimento_Messaggi;
DELIMITER //
CREATE PROCEDURE Inserimento_Messaggi(Mail VARCHAR (50), Messaggio VARCHAR (200))
BEGIN	
	INSERT INTO Messaggi
    VALUES (Mail, Messaggio);
END
//
DELIMITER ;

/*__________________________________________________________________________________________________________________________________________________*/


CREATE TABLE IF NOT EXISTS Vasca(
CodV VARCHAR(4) PRIMARY KEY,
CodP VARCHAR(4) NOT NULL REFERENCES Piscina(CodiceP)
);

CREATE TABLE IF NOT EXISTS Corsia(
CodC VARCHAR(4) PRIMARY KEY,
Numero INT(10) NOT NULL,
Codv VARCHAR(4) NOT NULL REFERENCES Vasca(CodV),
CodC VARCHAR(4) NOT NULL REFERENCES Corso(CodiceC)
);








