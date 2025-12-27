-- Profil için ek alanlar
-- Oluşturulma Tarihi: 27.12.2025

USE job_platform;

-- Users tablosuna profil alanları ekle
ALTER TABLE users 
ADD COLUMN location VARCHAR(255) NULL AFTER phone,
ADD COLUMN bio TEXT NULL AFTER location,
ADD COLUMN date_of_birth DATE NULL AFTER bio,
ADD COLUMN gender ENUM('male', 'female', 'other') NULL AFTER date_of_birth,
ADD COLUMN nationality VARCHAR(100) NULL AFTER gender,
ADD COLUMN linkedin_url VARCHAR(500) NULL AFTER nationality,
ADD COLUMN github_url VARCHAR(500) NULL AFTER linkedin_url,
ADD COLUMN portfolio_url VARCHAR(500) NULL AFTER github_url,
ADD COLUMN skills TEXT NULL AFTER portfolio_url,
ADD COLUMN languages TEXT NULL AFTER skills,
ADD COLUMN experience_years INT DEFAULT 0 AFTER languages,
ADD COLUMN education TEXT NULL AFTER experience_years,
ADD COLUMN certifications TEXT NULL AFTER education,
ADD COLUMN work_experience TEXT NULL AFTER certifications,
ADD COLUMN projects TEXT NULL AFTER work_experience,
ADD COLUMN current_position VARCHAR(255) NULL AFTER projects,
ADD COLUMN expected_salary VARCHAR(100) NULL AFTER current_position,
ADD COLUMN work_preference VARCHAR(100) NULL AFTER expected_salary,
ADD COLUMN availability VARCHAR(100) NULL AFTER work_preference,
ADD COLUMN cv_path VARCHAR(500) NULL AFTER availability;

-- Index ekle
CREATE INDEX idx_location ON users(location);
CREATE INDEX idx_experience ON users(experience_years);
CREATE INDEX idx_availability ON users(availability);
