CREATE DATABASE IF NOT EXISTS easy_sklad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE easy_sklad;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  username VARCHAR(100) NOT NULL,
  balance DECIMAL(10,2) NOT NULL DEFAULT 0,
  tariff VARCHAR(50) NOT NULL DEFAULT 'Free',
  created_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE companies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  owner_user_id INT NOT NULL,
  name VARCHAR(190) NOT NULL,
  inn VARCHAR(20) DEFAULT NULL,
  address VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_owner (owner_user_id),
  CONSTRAINT fk_companies_owner FOREIGN KEY (owner_user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE warehouses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  name VARCHAR(190) NOT NULL,
  address VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_company (company_id),
  CONSTRAINT fk_warehouses_company FOREIGN KEY (company_id) REFERENCES companies(id)
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  name VARCHAR(190) NOT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_categories_company (company_id),
  CONSTRAINT fk_categories_company FOREIGN KEY (company_id) REFERENCES companies(id)
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  warehouse_id INT NOT NULL,
  category_id INT DEFAULT NULL,
  sku VARCHAR(100) DEFAULT NULL,
  name VARCHAR(190) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  cost DECIMAL(10,2) NOT NULL DEFAULT 0,
  unit VARCHAR(20) NOT NULL DEFAULT 'шт',
  min_stock INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  INDEX idx_products_warehouse (warehouse_id),
  INDEX idx_products_category (category_id),
  CONSTRAINT fk_products_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

CREATE TABLE product_stocks (
  product_id INT NOT NULL,
  warehouse_id INT NOT NULL,
  qty DECIMAL(10,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (product_id, warehouse_id),
  CONSTRAINT fk_stocks_product FOREIGN KEY (product_id) REFERENCES products(id),
  CONSTRAINT fk_stocks_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
) ENGINE=InnoDB;

CREATE TABLE stock_movements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  warehouse_id INT NOT NULL,
  product_id INT NOT NULL,
  type ENUM('in','out') NOT NULL,
  qty DECIMAL(10,2) NOT NULL,
  cost DECIMAL(10,2) NOT NULL,
  ref_type ENUM('income','order') NOT NULL,
  ref_id INT NOT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_movements_warehouse (warehouse_id),
  INDEX idx_movements_product (product_id)
) ENGINE=InnoDB;

CREATE TABLE incomes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  warehouse_id INT NOT NULL,
  supplier VARCHAR(190) DEFAULT NULL,
  date DATE NOT NULL,
  total_cost DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  INDEX idx_incomes_warehouse (warehouse_id),
  CONSTRAINT fk_incomes_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
) ENGINE=InnoDB;

CREATE TABLE income_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  income_id INT NOT NULL,
  product_id INT NOT NULL,
  qty DECIMAL(10,2) NOT NULL,
  cost DECIMAL(10,2) NOT NULL,
  INDEX idx_income_items_income (income_id),
  CONSTRAINT fk_income_items_income FOREIGN KEY (income_id) REFERENCES incomes(id),
  CONSTRAINT fk_income_items_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  warehouse_id INT NOT NULL,
  customer_name VARCHAR(190) DEFAULT NULL,
  payment_method VARCHAR(50) DEFAULT NULL,
  discount DECIMAL(10,2) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL DEFAULT 0,
  status ENUM('draft','paid','canceled') NOT NULL DEFAULT 'draft',
  created_at DATETIME NOT NULL,
  INDEX idx_orders_warehouse (warehouse_id),
  CONSTRAINT fk_orders_warehouse FOREIGN KEY (warehouse_id) REFERENCES warehouses(id)
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  qty DECIMAL(10,2) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  INDEX idx_order_items_order (order_id),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id),
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  name VARCHAR(190) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  description TEXT,
  created_at DATETIME NOT NULL,
  INDEX idx_services_company (company_id),
  CONSTRAINT fk_services_company FOREIGN KEY (company_id) REFERENCES companies(id)
) ENGINE=InnoDB;

CREATE TABLE order_services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  service_id INT NOT NULL,
  qty DECIMAL(10,2) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_order_services_order FOREIGN KEY (order_id) REFERENCES orders(id),
  CONSTRAINT fk_order_services_service FOREIGN KEY (service_id) REFERENCES services(id)
) ENGINE=InnoDB;

INSERT INTO users (email, password_hash, first_name, last_name, username, balance, tariff, created_at)
VALUES ('test@example.com', '$2y$12$opVkE0vVx3UPG6Glq95TwOq9p3mrOKOFpwcRerWcNhAzUHoma0VPG', 'Тест', 'Пользователь', 'testuser', 1500.00, 'Free', NOW());

INSERT INTO companies (owner_user_id, name, inn, address, created_at)
VALUES (1, 'Тестовая компания', '7700000000', 'Москва', NOW());

INSERT INTO warehouses (company_id, name, address, created_at)
VALUES (1, 'Основной склад', 'Москва, склад 1', NOW());

INSERT INTO categories (company_id, name, created_at)
VALUES (1, 'Напитки', NOW()), (1, 'Снеки', NOW());

INSERT INTO products (warehouse_id, category_id, sku, name, price, cost, unit, min_stock, created_at)
VALUES
(1, 1, 'DRINK-001', 'Минеральная вода', 50.00, 30.00, 'шт', 10, NOW()),
(1, 1, 'DRINK-002', 'Сок яблочный', 120.00, 80.00, 'шт', 8, NOW()),
(1, 2, 'SNACK-001', 'Чипсы', 90.00, 60.00, 'шт', 12, NOW());

INSERT INTO product_stocks (product_id, warehouse_id, qty)
VALUES (1, 1, 100), (2, 1, 80), (3, 1, 60);

INSERT INTO services (company_id, name, price, description, created_at)
VALUES (1, 'Доставка', 250.00, 'Быстрая доставка по городу', NOW()),
       (1, 'Упаковка', 100.00, 'Подарочная упаковка', NOW());

INSERT INTO incomes (warehouse_id, supplier, date, total_cost, created_at)
VALUES (1, 'Поставщик №1', CURDATE(), 10000.00, NOW());

INSERT INTO income_items (income_id, product_id, qty, cost)
VALUES (1, 1, 10, 30.00);

INSERT INTO orders (warehouse_id, customer_name, payment_method, discount, total, status, created_at)
VALUES (1, 'Иванов И.', 'card', 0, 250.00, 'paid', NOW());

INSERT INTO order_items (order_id, product_id, qty, price, total)
VALUES (1, 2, 2, 120.00, 240.00);
