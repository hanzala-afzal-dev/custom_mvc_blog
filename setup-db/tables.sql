CREATE TABLE IF NOT EXISTS users (
                                     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                     name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','normal') NOT NULL DEFAULT 'normal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS posts (
                                     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                     user_id INT UNSIGNED NOT NULL,
                                     title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_posts_user_id (user_id),
    CONSTRAINT fk_posts_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS comments (
                                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        post_id INT UNSIGNED NOT NULL,
                                        user_id INT UNSIGNED NOT NULL,
                                        body TEXT NOT NULL,
                                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        INDEX idx_comments_post_id (post_id),
    INDEX idx_comments_user_id (user_id),
    CONSTRAINT fk_comments_post
    FOREIGN KEY (post_id) REFERENCES posts(id)
    ON DELETE CASCADE,
    CONSTRAINT fk_comments_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
