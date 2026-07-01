CREATE DATABASE ProjectTrackerDB;
USE ProjectTrackerDB;

CREATE TABLE roles(
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(100),
    role_description TEXT
);

CREATE TABLE barangays(
    barangay_id INT PRIMARY KEY AUTO_INCREMENT,
    barangay_name VARCHAR(100),
    boundary_geojson JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE users(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100),
    password_hash VARCHAR(255),
    user_email VARCHAR(100),
    role_id INT,
    barangay_id INT,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    remember_token VARCHAR(100) NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    FOREIGN KEY (barangay_id) REFERENCES barangays(barangay_id)
);

CREATE TABLE projects(
    project_id INT PRIMARY KEY AUTO_INCREMENT,
    project_code VARCHAR(100),
    project_name VARCHAR(300),
    project_type VARCHAR(100),
    barangay_id INT,
    location_description TEXT,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    approved_budget DECIMAL(18,2),
    actual_budget DECIMAL(18,2),
    start_date DATE,
    target_end_date DATE,
    actual_end_date DATE,
    current_status VARCHAR(100),
    remarks TEXT,
    created_by INT,
    updated_by INT,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (barangay_id) REFERENCES barangays(barangay_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id),
    FOREIGN KEY (updated_by) REFERENCES users(user_id)
);

CREATE TABLE project_updates(
    update_id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT,
    update_date DATE,
    status VARCHAR(100),
    progress_percentage TINYINT,
    remarks TEXT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(project_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE tasks(
    task_id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT,
    task_name VARCHAR(250),
    task_description TEXT,
    assigned_to INT,
    task_start_date DATE,
    task_end_date DATE,
    task_status VARCHAR(100),
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(project_id),
    FOREIGN KEY (assigned_to) REFERENCES users(user_id)
);

-- audit logs table
CREATE TABLE audit_logs(
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100),
    table_name VARCHAR(100),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- budget transaction table
CREATE TABLE budget_transactions(
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT,
    action VARCHAR(50),
    amount DECIMAL(18,2),
    transaction_type VARCHAR(200),
    description TEXT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(project_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


-- Roles FIRST before users
INSERT INTO roles (role_name, role_description) 
VALUES 
    ('Admin', 'System Administrator'),
    ('City Official', 'City Government Official'),
    ('Department', 'Department Staff'),
    ('Barangay Official', 'Barangay Official');

-- Barangays (add your 18 barangays here if not already in DB)

-- Admin user AFTER roles exist, with proper bcrypt hash
-- Password is: Admin@1234
INSERT INTO users (username, user_email, password_hash, role_id, barangay_id, created_at, updated_at) 
VALUES (
    'admin',
    'admin@projecttracker.com',
    '$2y$12$W0ZB9W5MsXd5B4vaHKdT8eSIBjREwnlOJJl100DBAlmcZyktFxhde',
    1,
    NULL,
    NOW(),
    NOW()
);

INSERT INTO barangays (barangay_name, created_at, updated_at) VALUES
('Banay-Banay', NOW(), NOW()),
('Banlic', NOW(), NOW()),
('Bigaa', NOW(), NOW()),
('Butong', NOW(), NOW()),
('Casile', NOW(), NOW()),
('Diezmo', NOW(), NOW()),
('Gulod', NOW(), NOW()),
('Marinig', NOW(), NOW()),
('Mamatid', NOW(), NOW()),
('Niugan', NOW(), NOW()),
('Pittland', NOW(), NOW()),
('Pulo', NOW(), NOW()),
('Sala', NOW(), NOW()),
('San Isidro', NOW(), NOW()),
('Baclaran', NOW(), NOW()),
('Banaybanay', NOW(), NOW()),
('Pob. Uno', NOW(), NOW()),
('Pob. Dos', NOW(), NOW());
    


SELECT role_id, role_name FROM roles;

UPDATE users 
SET password_hash = '\$2y\$12\$oT3kvQU6si8TcZv1pn8l.u.lPVbMu.7kVaDKnpDmlxUZL5T3JEWIe' 
WHERE user_id = 1;


ALTER TABLE projects
ADD COLUMN project_image VARCHAR(255) NULL AFTER remarks;

UPDATE users 
SET password_hash = '\$2y\$12\$.VVr2pCozRJf4MBQcDpC4erZC6lLabOcHuqYS9.4QXRp.muxq0DhC' 
WHERE user_id = 1;


select * from projects;
select * from users;
