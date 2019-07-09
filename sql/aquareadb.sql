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
content VARCHAR(150) NOT NULL,
author VARCHAR(100) NOT NULL,
Data DATE,
img VARCHAR(100) NOT NULL UNIQUE,
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

INSERT INTO users (email, username, password, user_type) 
        VALUES ('admin@stuendi.unipd.it','admin', MD5('admin'),'Admin'),
			   ('user@stuendi.unipd.it','user', MD5('user'),'Mod'),
			   ('franco@gmail.it','franco', MD5('franco'),'Mod'),
			   ('beppi@gmail.it','beppi', MD5('beppi'),'Mod'),
			   ('carlo@gmail.it','carlo', MD5('carlo'),'Mod');

INSERT INTO news (title, content, author, data, img)
		VALUES ('Benvenuti sul sito ', 'di Aquarea Vicenza', 'admin', '19/07/03', 'news1.jpg'),
			   ('il nostro nuovo sito', 'potete trovare tutte le informazioni riguardanti le strutture, orari dei corsi, 
			   inviare curriculum per entrare a far parte del nostro staff', 'user', '19/07/03', 'news2.jpg'),
			   ('Seuguici su Facebook', 'per non perdierti nuove offerte di corsi e tanto altro', 'user', '19/07/06', 'img3.png');
			   
INSERT INTO requests (name, surname, date, email, sesso, note, cv)
		VALUES ('Andrea', 'Schiavo', '98/03/16', 'andreaschiavo@gmail.com', 'Maschio', '', 'cv1.pdf'),
		       ('Marco', 'Dal Toso', '95/09/21', 'daltosomarco@gmail.com', 'Maschio', '', 'cv2.pdf'),
			   ('Silvia', 'Marcon', '97/05/03', 'silviamarcon@gmail.com', 'Femmina', '', 'cluedo.pdf');