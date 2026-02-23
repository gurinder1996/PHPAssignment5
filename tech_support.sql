-- COUNTRIES
CREATE TABLE countries (
  countryCode CHAR(2) PRIMARY KEY,
  countryName VARCHAR(100) NOT NULL
);

-- CUSTOMERS
CREATE TABLE customers (
  customerID INT AUTO_INCREMENT PRIMARY KEY,
  firstName VARCHAR(50) NOT NULL,
  lastName VARCHAR(50) NOT NULL,
  address VARCHAR(100),
  city VARCHAR(50),
  state VARCHAR(50),
  postalCode VARCHAR(20),
  countryCode CHAR(2),
  phone VARCHAR(25),
  email VARCHAR(100) UNIQUE,
  passwordHash VARCHAR(255),
  CONSTRAINT fk_customers_country FOREIGN KEY (countryCode) REFERENCES countries(countryCode)
    ON UPDATE CASCADE ON DELETE SET NULL
);

-- PRODUCTS
CREATE TABLE products (
  productCode VARCHAR(10) PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  version VARCHAR(20),
  releaseDate DATE
);

-- TECHNICIANS
CREATE TABLE technicians (
  techID INT AUTO_INCREMENT PRIMARY KEY,
  firstName VARCHAR(50) NOT NULL,
  lastName VARCHAR(50) NOT NULL,
  email VARCHAR(100) UNIQUE,
  phone VARCHAR(25),
  passwordHash VARCHAR(255)
);

-- INCIDENTS
CREATE TABLE incidents (
  incidentID INT AUTO_INCREMENT PRIMARY KEY,
  customerID INT NOT NULL,
  productCode VARCHAR(10) NOT NULL,
  techID INT NULL,
  dateOpened DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  dateClosed DATETIME NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  CONSTRAINT fk_incident_customer FOREIGN KEY (customerID) REFERENCES customers(customerID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_incident_product FOREIGN KEY (productCode) REFERENCES products(productCode)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_incident_tech FOREIGN KEY (techID) REFERENCES technicians(techID)
    ON UPDATE CASCADE ON DELETE SET NULL
);

-- REGISTRATIONS (customer - product)
CREATE TABLE registrations (
  registrationID INT AUTO_INCREMENT PRIMARY KEY,
  customerID INT NOT NULL,
  productCode VARCHAR(10) NOT NULL,
  registrationDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reg_customer FOREIGN KEY (customerID) REFERENCES customers(customerID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_reg_product FOREIGN KEY (productCode) REFERENCES products(productCode)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT uq_customer_product UNIQUE (customerID, productCode)
);

-- ADMINISTRATORS (not related to other tables per spec)
CREATE TABLE administrators (
  adminID INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  passwordHash VARCHAR(255) NOT NULL
);

-- SAMPLE DATA
INSERT INTO countries (countryCode, countryName) VALUES
('CA','Canada'), ('US','United States');

INSERT INTO products (productCode, name, version, releaseDate) VALUES
('BB10','Baseball Pro','1.0','2025-09-01'),
('SC15','Soccer Pro','1.5','2025-06-15');

INSERT INTO customers (firstName,lastName,address,city,state,postalCode,countryCode,phone,email) VALUES
('Alex','Morgan','10 King St','Toronto','ON','M5H 1A1','CA','416-111-2222','alex@example.com'),
('Jamie','Lee','200 Main Ave','Buffalo','NY','14201','US','716-333-4444','jamie@example.com');

INSERT INTO technicians (firstName,lastName,email,phone) VALUES
('Taylor','Ng','tng@sportspro.com','416-555-9876'),
('Chris','Patel','cpatel@sportspro.com','416-555-4567');

INSERT INTO administrators (username, passwordHash) VALUES
('admin', '$2y$10$exampleexampleexampleexampleexampleexampleexampleex');

INSERT INTO registrations (customerID, productCode) VALUES
(1, 'BB10'),
(2, 'SC15');

INSERT INTO incidents (customerID, productCode, techID, title, description)
VALUES
(1, 'BB10', 1, 'Cannot save league', 'Error appears when saving.'),
(2, 'SC15', NULL, 'Install issue', 'Setup fails at step 2.');
