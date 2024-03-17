
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
