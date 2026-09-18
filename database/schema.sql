-- MySQL 8.4, InnoDB, UTC DATETIME values.
-- IF NOT EXISTS makes initialisation repeatable, not a schema migration system.
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS articles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description VARCHAR(500) NOT NULL,
    body LONGTEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    views BIGINT UNSIGNED NOT NULL DEFAULT 0,
    published_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_articles_published (published_at DESC, id DESC),
    INDEX idx_articles_views (views DESC, published_at DESC, id DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS article_category (
    article_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id),
    INDEX idx_article_category_category (category_id, article_id),
    CONSTRAINT fk_article_category_article FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE,
    CONSTRAINT fk_article_category_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
