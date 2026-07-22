CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email)
);

CREATE TABLE مواعيد (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_start_date (start_date)
);

CREATE TABLE خدمات (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE العملاء (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(20),
  address TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email)
);

CREATE TABLE user_mواعيد (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  مواعيد_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (مواعيد_id) REFERENCES مواعيد(id),
  UNIQUE KEY uk_user_mواعيد (user_id, مواعيد_id),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user_خدمات (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  خدمات_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (خدمات_id) REFERENCES خدمات(id),
  UNIQUE KEY uk_user_خدمات (user_id, خدمات_id),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user_العملاء (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  العملاء_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (العملاء_id) REFERENCES العملاء(id),
  UNIQUE KEY uk_user_العملاء (user_id, العملاء_id),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO مواعيد (title, description, start_date, end_date)
VALUES ('Meeting', 'This is a meeting', '2024-01-01', '2024-01-01');

INSERT INTO خدمات (title, description, price)
VALUES ('Service 1', 'This is service 1', 10.99);

INSERT INTO العملاء (name, email, phone, address)
VALUES ('Customer 1', 'customer1@example.com', '1234567890', '123 Main St');

INSERT INTO user_mواعيد (user_id, مواعيد_id)
VALUES (1, 1);

INSERT INTO user_خدمات (user_id, خدمات_id)
VALUES (1, 1);

INSERT INTO user_العملاء (user_id, العملاء_id)
VALUES (1, 1);