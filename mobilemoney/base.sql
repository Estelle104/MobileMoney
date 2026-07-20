CREATE TABLE operateur(
    id INT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
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
