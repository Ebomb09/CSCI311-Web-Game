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


