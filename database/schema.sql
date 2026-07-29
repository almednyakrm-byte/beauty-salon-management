CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE خدمات (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE مواعيد (
  id INT AUTO_INCREMENT,
  service_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (service_id),
  CONSTRAINT fk_مواعيد_خدمات FOREIGN KEY (service_id) REFERENCES خدمات (id)
);

CREATE TABLE العملاء (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (service_id),
  CONSTRAINT fk_bookings_users FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_bookings_خدمات FOREIGN KEY (service_id) REFERENCES خدمات (id)
);

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO خدمات (name, description) VALUES
('خدمة 1', 'وصف الخدمة 1'),
('خدمة 2', 'وصف الخدمة 2');

INSERT INTO مواعيد (service_id, date, time) VALUES
(1, '2024-01-01', '10:00:00'),
(2, '2024-01-02', '11:00:00');

INSERT INTO العملاء (name, email, phone) VALUES
('عميل 1', 'عميل1@example.com', '0123456789'),
('عميل 2', 'عميل2@example.com', '0987654321');