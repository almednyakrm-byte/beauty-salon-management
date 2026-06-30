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

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  service_id INT NOT NULL,
  user_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_service_id (service_id),
  INDEX idx_user_id (user_id),
  FOREIGN KEY (service_id) REFERENCES services(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  phone VARCHAR(20),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user_permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  page_name VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_user_id (user_id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO services (name, description) VALUES
  ('Service 1', 'This is service 1'),
  ('Service 2', 'This is service 2');

INSERT INTO appointments (service_id, user_id, date, time) VALUES
  (1, 1, '2024-01-01', '10:00:00'),
  (2, 1, '2024-01-02', '11:00:00');

INSERT INTO customers (name, email, phone) VALUES
  ('Customer 1', 'customer1@example.com', '0123456789'),
  ('Customer 2', 'customer2@example.com', '0987654321');

INSERT INTO user_permissions (user_id, page_name) VALUES
  (1, 'الرئيسية'),
  (1, 'قائمة الخدمات'),
  (1, 'قائمة المواعيد'),
  (1, 'إدارة الخدمات'),
  (1, 'إدارة المواعيد'),
  (1, 'إدارة العملاء');