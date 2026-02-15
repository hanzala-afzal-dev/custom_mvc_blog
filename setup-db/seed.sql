INSERT INTO users (name, email, password_hash, role)
VALUES
    ('Admin User', 'admin@example.com', '$2y$10$H6n7j1xXo3FBRf9d0j9hUe3mW6eYt8cK0u7dXg3m8wA5oG4jz1s2K', 'admin')
    ON DUPLICATE KEY UPDATE email = email;
