
-- Adicionando coluna prazo
ALTER TABLE budgets
ADD COLUMN deadline DATE NULL;

-- Adicionar criador do budget
ALTER TABLE budgets
ADD COLUMN user_id INT,
ADD FOREIGN KEY (user_id) REFERENCES users(id);

-- Adicionando data de criação
ALTER TABLE product_budgets
ADD COLUMN created_at DATE DEFAULT CURDATE();


ALTER TABLE budgets ADD COLUMN finished_at DATETIME NULL;

ALTER TABLE budgets
ADD COLUMN finished_at DATETIME NULL
ADD COLUMN payed TINYINT(1) NOT NULL DEFAULT 0;





CREATE TABLE project_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255) NULL,
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255) NULL,
    image_url VARCHAR(255) NOT NULL,
    project_type_id INT,
    FOREIGN KEY (project_type_id) REFERENCES projects_type(id)
);



ALTER TABLE budgets
ADD COLUMN discount DECIMAL(10,2) NULL
ADD COLUMN description TEXT NULL;