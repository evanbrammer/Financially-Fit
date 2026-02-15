use it_project

CREATE TABLE budget_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    year INT NOT NULL,
    month INT NOT NULL,
    income DECIMAL(10,2),
    housing DECIMAL(10,2),
    car DECIMAL(10,2),
    groceries DECIMAL(10,2),
    retirement DECIMAL(10,2),
    spending_money DECIMAL(10,2),
    leftover DECIMAL(10,2),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);