-- İşveren profili için ek alanlar
-- Oluşturulma Tarihi: 27.12.2025

USE job_platform;

-- Users tablosuna işveren bilgileri ekle
ALTER TABLE users 
ADD COLUMN company_website VARCHAR(500) NULL AFTER company_description,
ADD COLUMN company_address TEXT NULL AFTER company_website,
ADD COLUMN company_size VARCHAR(50) NULL AFTER company_address,
ADD COLUMN company_industry VARCHAR(255) NULL AFTER company_size,
ADD COLUMN company_founded_year INT NULL AFTER company_industry,
ADD COLUMN twitter_url VARCHAR(500) NULL AFTER linkedin_url,
ADD COLUMN facebook_url VARCHAR(500) NULL AFTER twitter_url;

-- Index ekle
CREATE INDEX idx_company_industry ON users(company_industry);
CREATE INDEX idx_company_size ON users(company_size);
