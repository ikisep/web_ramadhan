-- Database setup for Ramadhan Planner

CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category ENUM('ibadah', 'sosial', 'belajar') NOT NULL,
    activity_date DATE NOT NULL,
    activity_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reflections table
CREATE TABLE IF NOT EXISTS reflections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reflection_date DATE NOT NULL UNIQUE,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data (optional)
INSERT INTO activities (title, category, activity_date, activity_time) VALUES
('Quran Study', 'ibadah', '2024-04-09', '20:00:00'),
('Charity Drive', 'sosial', '2024-04-19', '14:00:00'),
('Iftar with Friends', 'sosial', '2024-04-27', '18:00:00'),
('Taraweeh Prayer', 'ibadah', '2024-04-29', '20:30:00'),
('Exercise', 'belajar', '2024-04-30', '06:00:00');

INSERT INTO reflections (reflection_date, content) VALUES
(CURDATE(), 'Day 10: Feeling grateful today, enjoyed Iftar with friends. Need to focus more on Quran recitation.');


