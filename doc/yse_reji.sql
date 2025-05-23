-- データベース作成
CREATE DATABASE IF NOT EXISTS yse_regi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE yse_regi;

-- salesテーブル作成
CREATE TABLE IF NOT EXISTS sales (
    id BIGINT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
    sales_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '売上日時',
    amount BIGINT NOT NULL COMMENT '売上高',
    receipt_no VARCHAR(50) UNIQUE COMMENT '領収書番号',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修正日'
);
