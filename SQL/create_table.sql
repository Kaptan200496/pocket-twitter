-- Таблица для пользователей
CREATE TABLE users (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	
	-- Информация о пользователе
	login VARCHAR(32) NOT NULL,
	email VARCHAR(320),
	-- id изображения аватарки
	avatar INTEGER(64) UNSIGNED,
	-- SHA1 хэш (40 символов)
	password_hash VARCHAR(40),
	-- UUID (36 символов)
	salt VARCHAR(36),
	rights ENUM('user', 'administrator') DEFAULT 'user',

	-- Информация о записи в базе
	created INTEGER(64),
	modified INTEGER(64),
	active INTEGER(1) DEFAULT 1
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TRIGGER users_insert_created
	BEFORE INSERT ON users 
	FOR EACH ROW 
	SET 
		new.created = UNIX_TIMESTAMP(NOW()),
		new.modified = UNIX_TIMESTAMP(NOW());

CREATE TABLE login_attempts (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

	login VARCHAR(32),
	successful INTEGER(1),
	ip VARCHAR(40),
	user_agent TEXT,

	created INTEGER(64)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TRIGGER login_attempt_created
	BEFORE INSERT ON login_attempts 
	FOR EACH ROW 
	SET 
		new.created = UNIX_TIMESTAMP(NOW());

CREATE TABLE sessions (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

	user INTEGER(64) UNSIGNED,
	
	token VARCHAR(36),

	created INTEGER(64),
	modified INTEGER(64),
	active INTEGER(1) DEFAULT 1
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TRIGGER session_created
	BEFORE INSERT ON sessions 
	FOR EACH ROW 
	SET 
		new.created = UNIX_TIMESTAMP(NOW()),
		new.modified = UNIX_TIMESTAMP(NOW());

CREATE TABLE tweets (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

	author INTEGER(64) UNSIGNED,
	reply_to_id INTEGER(64) UNSIGNED,
	text VARCHAR(280),
	image_id INTEGER(64) UNSIGNED,
	
	created INTEGER(64),
	modified INTEGER(64),
	active INTEGER(1) DEFAULT 1
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TRIGGER tweet_created
	BEFORE INSERT ON tweets 
	FOR EACH ROW 
	SET 
		new.created = UNIX_TIMESTAMP(NOW()),
		new.modified = UNIX_TIMESTAMP(NOW());

CREATE TABLE images (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

	author INTEGER(64) UNSIGNED,
	file_name VARCHAR(32),
	
	created INTEGER(64),
	modified INTEGER(64),
	active INTEGER(1) DEFAULT 1
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TRIGGER image_created
	BEFORE INSERT ON images 
	FOR EACH ROW 
	SET 
		new.created = UNIX_TIMESTAMP(NOW()),
		new.modified = UNIX_TIMESTAMP(NOW());

CREATE TABLE subscribers (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	subscriber_id INTEGER(64) UNSIGNED,
	issuer_id INTEGER(64) UNSIGNED,
	
	created INTEGER(64),
	modified INTEGER(64),
	active INTEGER(1) DEFAULT 1
)  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TRIGGER subscriber_created
	BEFORE INSERT ON subscribers 
	FOR EACH ROW 
	SET 
		new.created = UNIX_TIMESTAMP(NOW()),
		new.modified = UNIX_TIMESTAMP(NOW());

CREATE TABLE hashtags (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	
	text VARCHAR(280),
	
	created INTEGER(64),
	modified INTEGER(64),
	active INTEGER(1) DEFAULT 1
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TRIGGER hashtag_created
	BEFORE INSERT ON hashtags 
	FOR EACH ROW 
	SET 
		new.created = UNIX_TIMESTAMP(NOW()),
		new.modified = UNIX_TIMESTAMP(NOW());

CREATE TABLE hashtag_appearances (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

	tweet INTEGER(64) UNSIGNED,
	hashtag INTEGER(64) UNSIGNED,

	active INTEGER(1) DEFAULT 1
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


CREATE TABLE notifications (
	id INTEGER(64) UNSIGNED AUTO_INCREMENT PRIMARY KEY,

	user INTEGER(64),
	`read` INTEGER(1) DEFAULT 0,
	text TEXT,
	
	created INTEGER(64),
	modified INTEGER(64),
	active INTEGER(1) DEFAULT 1
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TRIGGER notification_created
	BEFORE INSERT ON notifications 
	FOR EACH ROW 
	SET 
		new.created = UNIX_TIMESTAMP(NOW()),
		new.modified = UNIX_TIMESTAMP(NOW());
