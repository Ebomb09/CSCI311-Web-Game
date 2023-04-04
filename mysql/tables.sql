DROP DATABASE IF EXISTS ${db_name};
CREATE DATABASE ${db_name};
USE ${db_name};

CREATE TABLE users(
	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	name VARCHAR(16) NOT NULL,
	icon VARCHAR(512),
	password VARCHAR(32),
	PRIMARY KEY (id)
);

CREATE TABLE scores(
	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	user_id INT,
	score INT,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE sessions(
	id VARCHAR(16) NOT NULL UNIQUE,
	user_id INT UNIQUE,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES users (id)
);

INSERT INTO users 
	(name, password, icon) 
VALUES 
	("jeff", "test123", "images/icons/face1.png"),
	("matt", "asdf", "images/icons/face2.png"),
	("jimjon", "iou", "images/icons/face3.png"),
	("bimathon", "lolol", "images/icons/face4.png"),
	("clark", "kent", "images/icons/face5.png"),
	("williamsonmanson", "password32", "images/icons/face6.png"),
	("tahvo_teboho", "thisissecure_not", "images/icons/face2.png"),
	("ma'toes", "2389u4891", "images/icons/face6.png"),
	("sabinea", "1290854389", "images/icons/face3.png"),
	("oissine", "aiurhgsghs832", "images/icons/face1.png"),
	("daquan", "29u8wrg89", "images/icons/face5.png")
;

INSERT INTO scores
	(user_id, score)
VALUES
	(1, 200),
	(2, 1000),
	(3, 500),
	(4, 10),
	(5, 1005),
	(6, 69),
	(7, 15),
	(8, 908),
	(9, 1001),
	(10, 535),
	(11, 1)
;
