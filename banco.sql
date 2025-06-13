-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS gerenciador_tarefas;
USE gerenciador_tarefas;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Tabela de tarefas
CREATE TABLE IF NOT EXISTS Tarefa (
    id_tarefa INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    descricao TEXT NOT NULL,
    setor VARCHAR(100) NOT NULL,
    prioridade ENUM('baixa', 'média', 'alta') NOT NULL,
    data_cadastro DATE NOT NULL,
    status ENUM('a fazer', 'fazendo', 'pronto') NOT NULL DEFAULT 'a fazer',
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
);

-- Inserir alguns usuários de exemplo
INSERT INTO Usuario (nome, email) VALUES
('João Silva', 'joao.silva@email.com'),
('Maria Oliveira', 'maria.oliveira@email.com'),
('Carlos Pereira', 'carlos.pereira@email.com');

-- Consulta para verificar os usuários
SELECT * FROM Usuario;