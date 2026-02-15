INSERT INTO users (name, email, password_hash, role)
VALUES
    ('Admin User', 'admin@example.com', '$2a$04$5qBAkhk1xcduccAoBE0R5uwgXKWbzZ0vVy.qO04tX7s7QBHUSzfFW', 'admin')
    ON DUPLICATE KEY UPDATE name = VALUES(name), password_hash = VALUES(password_hash), role = VALUES(role);