-- データベース作成
CREATE DATABASE IF NOT EXISTS yse_register CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE yse_register;

-- salesテーブル作成
CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    amount INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 初期データ（任意でサンプルを追加）
INSERT INTO sales (amount, created_at) VALUES
(1200, '2025-04-01 10:15:00'),
(3800, '2025-04-02 14:45:00'),
(560, '2025-04-10 09:10:00');
