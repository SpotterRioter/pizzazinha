-- ============================================================
-- SAEP - Sistema de Gestao de Pizzaria
-- Script de criacao (DDL) e populacao inicial (DML)
-- Banco: saep_db (MySQL 8+)
-- ============================================================

CREATE DATABASE IF NOT EXISTS saep_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE saep_db;

-- ------------------------------------------------------------
-- Tabela: usuarios (atendentes)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS pizzas;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  cargo VARCHAR(255) NOT NULL DEFAULT 'Atendente',
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY usuarios_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: clientes (CPF armazenado de forma criptografada)
-- ------------------------------------------------------------
CREATE TABLE clientes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL,
  cpf TEXT NOT NULL COMMENT 'CPF cifrado (AES-256 pela aplicacao)',
  cpf_hash CHAR(64) NOT NULL COMMENT 'HMAC-SHA256 do CPF para busca/deduplicacao',
  email VARCHAR(255) NULL,
  telefone VARCHAR(20) NOT NULL,
  endereco VARCHAR(180) NULL,
  cidade VARCHAR(100) NULL,
  uf CHAR(2) NULL,
  observacoes TEXT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY clientes_cpf_hash_unique (cpf_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: pizzas (cardapio)
-- ------------------------------------------------------------
CREATE TABLE pizzas (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  sabor VARCHAR(255) NOT NULL,
  ingredientes TEXT NOT NULL,
  tamanho CHAR(1) NOT NULL DEFAULT 'M',
  preco DECIMAL(8,2) NOT NULL,
  disponivel TINYINT(1) NOT NULL DEFAULT 1,
  personalizada TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: pedidos
-- ------------------------------------------------------------
CREATE TABLE pedidos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cliente_id BIGINT UNSIGNED NOT NULL,
  pizza_id BIGINT UNSIGNED NULL,
  quantidade SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  status VARCHAR(255) NOT NULL DEFAULT 'pendente',
  data_pedido DATE NOT NULL,
  data_entrega DATE NULL,
  valor_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  observacoes TEXT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY pedidos_data_pedido_index (data_pedido),
  KEY pedidos_status_index (status),
  CONSTRAINT pedidos_cliente_fk FOREIGN KEY (cliente_id) REFERENCES clientes (id) ON DELETE CASCADE,
  CONSTRAINT pedidos_pizza_fk FOREIGN KEY (pizza_id) REFERENCES pizzas (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Populacao inicial (DML) - ao menos 3 registros por tabela
-- ============================================================

INSERT INTO usuarios (name, cargo, ativo, email, password, created_at, updated_at) VALUES
('Ana Souza', 'Gerente', 1, 'ana.souza@pizzaria.test', '$2y$12$abcdefghijklmnopqrstuv', NOW(), NOW()),
('Bruno Lima', 'Atendente', 1, 'bruno.lima@pizzaria.test', '$2y$12$abcdefghijklmnopqrstuv', NOW(), NOW()),
('Carla Mendes', 'Atendente', 1, 'carla.mendes@pizzaria.test', '$2y$12$abcdefghijklmnopqrstuv', NOW(), NOW());

INSERT INTO clientes (nome, cpf, cpf_hash, email, telefone, endereco, cidade, uf, observacoes, created_at, updated_at) VALUES
('Mariana Oliveira', 'ENC_AES256_PLACEHOLDER_MARIANA', SHA2('52998224725', 256), 'mariana.oliveira@email.com', '(11) 98888-1010', 'Rua das Oliveiras, 142', 'Sao Paulo', 'SP', 'Prefere borda fina.', NOW(), NOW()),
('Rafael Pereira', 'ENC_AES256_PLACEHOLDER_RAFAEL', SHA2('11144477735', 256), 'rafael.pereira@email.com', '(11) 97777-2020', 'Avenida Forno a Lenha, 890', 'Campinas', 'SP', 'Cliente fidelidade.', NOW(), NOW()),
('Juliana Costa', 'ENC_AES256_PLACEHOLDER_JULIANA', SHA2('12345678909', 256), 'juliana.costa@email.com', '(19) 96666-3030', 'Rua da Basilica, 27', 'Jundiai', 'SP', 'Sem lactose.', NOW(), NOW());

INSERT INTO pizzas (sabor, ingredientes, tamanho, preco, disponivel, personalizada, created_at, updated_at) VALUES
('Margherita Artesanal', 'Molho San Marzano, mozzarella de bufala, manjericao.', 'M', 54.90, 1, 0, NOW(), NOW()),
('Calabresa Defumada', 'Molho artesanal, mozzarella, calabresa defumada, cebola.', 'G', 69.90, 1, 0, NOW(), NOW()),
('Quatro Queijos', 'Mozzarella, gorgonzola, parmesao e catupiry.', 'M', 64.90, 1, 0, NOW(), NOW()),
('Vegana da Casa', 'Queijo vegetal, tomate confit, rucula, cogumelos.', 'P', 49.90, 1, 1, NOW(), NOW());

INSERT INTO pedidos (cliente_id, pizza_id, quantidade, status, data_pedido, data_entrega, valor_total, observacoes, created_at, updated_at) VALUES
(1, 1, 2, 'entregue', '2024-08-15', '2024-08-15', 109.80, 'Entregar apos 19h.', NOW(), NOW()),
(2, 2, 1, 'entregue', '2025-03-22', '2025-03-22', 69.90, 'Programa fidelidade.', NOW(), NOW()),
(3, 4, 1, 'em_preparo', '2026-10-28', '2026-10-29', 49.90, 'Sem lactose.', NOW(), NOW()),
(1, 3, 3, 'pendente', '2026-10-29', '2026-10-30', 194.70, 'Comemoracao em familia.', NOW(), NOW());
