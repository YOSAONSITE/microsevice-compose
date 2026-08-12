CREATE DATABASE IF NOT EXISTS db_yosa;
USE db_yosa;

CREATE TABLE IF NOT EXISTS hardware (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_tag VARCHAR(50) NOT NULL UNIQUE,
    device_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    status VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO hardware (asset_tag, device_name, category, ip_address, status) VALUES
('NET-2301010184', 'Cisco Edge Router 4331', 'Router', '10.0.1.1', 'Online'),
('NET-SW-9300', 'Catalyst Core Switch 9300', 'Switch', '10.0.1.2', 'Online'),
('NET-FW-0060', 'FortiGate Firewall 60F', 'Firewall', '10.0.1.254', 'Maintenance');
