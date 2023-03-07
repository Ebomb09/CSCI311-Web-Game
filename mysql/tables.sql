DROP DATABASE csci311c_project;
CREATE DATABASE csci311c_project;
USE csci311c_project;

CREATE TABLE users(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(16) NOT NULL,
	icon VARCHAR(512),
	password VARCHAR(32),
	PRIMARY KEY (id)
);

CREATE TABLE scores(
	id INT NOT NULL AUTO_INCREMENT,
	user_id INT,
	score INT,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users 
	(name, password, icon) 
VALUES 
	("jeff", "test123", "icons/face1.png"),
	("matt", "asdf", "icons/face2.png"),
	("jimjon", "iou", "icons/face3.png"),
	("bimathon", "lolol", "icons/face4.png"),
	("clark", "kent", "icons/face5.png"),
	("williamsonmanson", "password32", "icons/face6.png"),
	("tahvo_teboho", "thisissecure_not", "icons/face2.png"),
	("ma'toes", "2389u4891", "icons/face6.png"),
	("sabinea", "1290854389", "icons/face3.png"),
	("oissine", "aiurhgsghs832", "icons/face1.png"),
	("daquan", "29u8wrg89", "icons/face5.png")
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
