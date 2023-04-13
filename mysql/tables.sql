DROP DATABASE IF EXISTS ${db_name};
CREATE DATABASE ${db_name};
USE ${db_name};

CREATE TABLE users(
	id INT NOT NULL UNIQUE AUTO_INCREMENT,
	name VARCHAR(16) NOT NULL,
	icon VARCHAR(512),
	password_hash VARCHAR(60),
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
	(name, password_hash, icon) 
VALUES
	("jeff", "$2y$10$eE..ucGlA/My/X7VxgL/FOuO7VXdJ5xdW/oXe3qftLNoxtYDkqx5W", "images/icons/face1.png"),
	("matt", "$2y$10$/bsvLSt0nGdHvjz8W4iP/OvloNYuhhe4H4qt8J2S9yv61jSSc0d.C", "images/icons/face2.png"),
	("jimjon", "$2y$10$W9FqOXUS/qqum.zY/0hRf.AJGLzb5O5N5TCN.RtRkiR/J9XHDy1l2", "images/icons/face3.png"),
	("bimathon", "$2y$10$i8c/UW72XyTnMCfH1B.1k.yQwzAdZM6fEEx6ncsUwZb/88xV7KjGq", "images/icons/face4.png"),
	("clark", "$2y$10$jZUzFdZDJ0n/WU6EsosU6.WyH8eqyztmXm.B0gjKkixBw8HvzbdAe", "images/icons/face5.png"),
	("williamsonmanson", "$2y$10$zgxKjv57ZQh.mNN5RtRnDeaLjc/Bbx9wkq1qJ.V/VjzOjEfoOveuG", "images/icons/face6.png"),
	("tahvo_teboho", "$2y$10$5W8dDT0Zlj9XuhXvEaB7WucjREm0G.w7m.mdb5AL5J69d8GeEyC5m", "images/icons/face2.png"),
	("ma'toes", "$2y$10$LK.uV7foEpbxq/3q3gjKcuuZ7L8K.BC4qgF4G/ZsYsM4HxwuRdwX2", "images/icons/face6.png"),
	("sabinea", "$2y$10$6MH12y6Ux0USU9TE6gnpBO6BCyrZfEDNpG4Hq3sMZsEXsElnNlAZS", "images/icons/face3.png"),
	("oissine", "$2y$10$HS6U71yQJ15n6Uxtd6U9fe6GvZJbLRB0vxzJ5VE5EF5p5B8W1xHhi", "images/icons/face1.png"),
	("daquan", "$2y$10$DDYjF1foDz7DOJp/OAqVH.qSc.U70OgZ7RwTr/px2hInlL/BKN8y.", "images/icons/face5.png")
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
