DROP DATABASE IF EXISTS chill;
CREATE DATABASE chill;

CREATE USER IF NOT EXISTS 'sigma'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON chill.* TO 'sigma'@'localhost' WITH GRANT OPTION;

USE chill;

DROP TABLE IF EXISTS score;
DROP TABLE IF EXISTS reset_password_request;
DROP TABLE IF EXISTS player;
DROP TABLE IF EXISTS game;

CREATE TABLE game (
    id INT(11) AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    img VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE player (
    id INT(11) AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    roles JSON NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE reset_password_request (
    id INT(11) AUTO_INCREMENT,
    id_user INT(11) NULL,
    selector VARCHAR(20) NOT NULL,
    hashed_token VARCHAR(100) NOT NULL,
    request_at DATETIME NOT NULL,
    expires_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_user) REFERENCES player(id)
);

CREATE TABLE score (
    id INT(11) AUTO_INCREMENT,
    game_id INT(11) NOT NULL,
    player_id INT(11) NOT NULL,
    score INT(11) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (game_id) REFERENCES game(id),
    FOREIGN KEY (player_id) REFERENCES player(id)
);

INSERT INTO `game` VALUES
(1,'Bomberman','games/bomberman/img/bomberman.jpeg'),
(2,'Operators Match','games/operators-match/img/cardMatch.jpg'),
(3,'Pong JS','games/pong-js/img/pong.jpg'),
(4,'Snake JS','games/snake-js/img/snake.jpg'),
(5,'Tetris JS','games/tetris-js/img/tetris.jpg');

INSERT INTO `player` VALUES 
(1,'SIGMA[!]','[\"ROLE_ADMIN\"]','$argon2id$v=19$m=65536,t=4,p=1$UpBkBq/hjVmDjhrgDuddow$Tfof8SdoF3lroaqgmc19DtBQzqGnw/2wrFZukj+go5E','lascortinashuelenaporro@gmail.com'),
(2,'Nayem','[\"ROLE_ADMIN\"]','$2y$10$LFqHPQiMZgUln.ttbbm54uHwjnRKpUlAurtOdCTUN47qCq0uCQ4ZK','nayemms@gmail.com'),
(3,'Kevin','[\"ROLE_ADMIN\"]','$2y$10$YcJy8pwTTbYkBdjwfgd.A.kchzWtYV/LyEQMhgNE6p49IsZ4CoVgq','kevinguzman0816@gmail.com'),
(4,'Testator','[\"ROLE_USER\"]','$2y$10$RuzOvQv/JF7mkTMP.Pz6kekdJWjvD1hiq9x/CuaNnmJ.kwHpPFC/e','test@xyz.com');
