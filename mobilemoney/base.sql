CREATE TABLE operateur(
    id INT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL
);

CREATE TABLE prefixe(
    id INT PRIMARY KEY,
    code VARCHAR(3) NOT NULL UNIQUE,
    id_operateur INT NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);

CREATE TABLE client(
    id INT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL UNIQUE,
    id_prefixe INT NOT NULL,
    solde DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_prefixe) REFERENCES prefixe(id)
);

CREATE TABLE type_operation(
    id INT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE bareme_frais(
    id INT PRIMARY KEY,
    id_type_operation INT NOT NULL,
    montant_min DECIMAL(10, 2) NOT NULL,
    montant_max DECIMAL(10, 2) NOT NULL,
    frais DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE operation(
    id INT PRIMARY KEY,
    id_client_source INT NOT NULL,
    id_client_destinataire INT,
    id_type_operation INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    frais DECIMAL(10, 2) NOT NULL,
    date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client_source) REFERENCES client(id),
    FOREIGN KEY (id_client_destinataire) REFERENCES client(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE prefixe_externe(
    id INT PRIMARY KEY,
    code VARCHAR(3) NOT NULL UNIQUE,
    nom_operateur_externe VARCHAR(50) NOT NULL,
    pourcentage_commission DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    id_operateur INT NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);

CREATE TABLE reglement_externe(
    id INT PRIMARY KEY,
    id_operateur INT NOT NULL,
    nom_operateur_externe VARCHAR(50) NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    date_reglement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id)
);

ALTER TABLE operation ADD COLUMN numero_destinataire_externe VARCHAR(20) NULL;
ALTER TABLE operation ADD COLUMN id_prefixe_externe INT NULL,
  ADD FOREIGN KEY (id_prefixe_externe) REFERENCES prefixe_externe(id);



-- INSERT SEED DATA
INSERT INTO type_operation (id, libelle) VALUES
(1, 'depot'),
(2, 'retrait'),
(3, 'transfert');

INSERT INTO operateur (id, nom, email, mdp) VALUES
(1, 'Orange Money', 'orange@mobilemoney.mg', '$2y$10$R9l6.30w7eP6D40H0X8W.evnJd8V8zQ3B6U2s8x4V5HwX9w8V9H9m'),
(2, 'MVola', 'mvola@mobilemoney.mg', '$2y$10$R9l6.30w7eP6D40H0X8W.evnJd8V8zQ3B6U2s8x4V5HwX9w8V9H9m'),
(3, 'Airtel Money', 'airtel@mobilemoney.mg', '$2y$10$R9l6.30w7eP6D40H0X8W.evnJd8V8zQ3B6U2s8x4V5HwX9w8V9H9m');

INSERT INTO prefixe (id, code, id_operateur) VALUES
(1, '032', 1),
(2, '037', 1),
(3, '034', 2),
(4, '038', 2),
(5, '033', 3);

INSERT INTO bareme_frais (id, id_type_operation, montant_min, montant_max, frais) VALUES
(1, 1, 0, 100000000, 0),
(2, 2, 0, 50000, 500),
(3, 2, 50001, 100000, 1000),
(4, 2, 100001, 500000, 2500),
(5, 2, 500001, 1000000, 5000),
(6, 3, 0, 50000, 300),
(7, 3, 50001, 100000, 700),
(8, 3, 100001, 500000, 1500),
(9, 3, 500001, 1000000, 3000);

INSERT INTO client (id, numero, id_prefixe, solde) VALUES
(1, '0321234567', 1, 500000.00),
(2, '0379999999', 2, 250000.00),
(3, '0341234567', 3, 750000.00),
(4, '0385555555', 4, 100000.00),
(5, '0339876543', 5, 300000.00);

INSERT INTO operation (id, id_client_source, id_client_destinataire, id_type_operation, montant, frais, date_transaction) VALUES
(1, 1, NULL, 1, 50000, 0, CURRENT_TIMESTAMP),
(2, 5, NULL, 2, 20000, 500, CURRENT_TIMESTAMP),
(3, 3, 1, 3, 100000, 700, CURRENT_TIMESTAMP),
(4, 4, 3, 3, 50000, 300, CURRENT_TIMESTAMP);


CREATE TABLE epargne(
    id INT PRIMARY KEY,
    id_client INT,
    pourcentage INT,
    FOREIGN KEY (id_client) REFERENCES client(id)
);