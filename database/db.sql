CREATE DATABASE IF NOT EXISTS track_flow_m1;
USE track_flow_m1;

CREATE TABLE IF NOT EXISTS funcionario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(15) NOT NULL,
    cargo VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL
);
INSERT INTO funcionario (nome, email, telefone, cargo, senha)
VALUES
('João Silva', 'joao@empresa.com', '(47) 99999-1111', 'operador', '123456'),
('Maria Souza', 'maria@empresa.com', '(47) 99999-2222', 'operador', '123456'),
('Pedro Santos', 'pedro@empresa.com', '(47) 99999-3333', 'operador', '123456'),
('Ana Oliveira', 'ana@empresa.com', '(47) 99999-4444', 'admin', '123456'),
('Carlos Pereira', 'carlos@empresa.com', '(47) 99999-5555', 'admin', '123456'),
('Juliana Costa', 'juliana@empresa.com', '(47) 99999-6666', 'admin', '123456');

CREATE TABLE IF NOT EXISTS trem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    modelo VARCHAR(50) NOT NULL
);
CREATE TABLE IF NOT EXISTS trilho(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    localizacao VARCHAR(100) NOT NULL
);
CREATE TABLE IF NOT EXISTS sensor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    trem_id INT NOT NULL,
    FOREIGN KEY (trem_id) REFERENCES trem(id)
    
);
CREATE TABLE IF NOT EXISTS dados_sensor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sensor_id INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sensor_id) REFERENCES sensor(id)
        ON DELETE RESTRICT
);