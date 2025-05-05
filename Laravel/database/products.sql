-- Create products table
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `status` enum('published','draft','archived') NOT NULL DEFAULT 'draft',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `products` (`name`, `description`, `price`, `stock`, `category`, `brand`, `status`, `is_active`, `image`, `created_at`, `updated_at`) VALUES
('iPhone 13 Pro', 'Latest iPhone with advanced camera system', 999.99, 50, 'Smartphones', 'Apple', 'published', 1, 'iphone13pro.jpg', NOW(), NOW()),
('Samsung Galaxy S21', 'Powerful Android smartphone', 799.99, 30, 'Smartphones', 'Samsung', 'published', 1, 'galaxys21.jpg', NOW(), NOW()),
('MacBook Pro', 'Professional laptop for creative work', 1299.99, 20, 'Laptops', 'Apple', 'published', 1, 'macbookpro.jpg', NOW(), NOW()),
('Sony WH-1000XM4', 'Premium noise-cancelling headphones', 349.99, 15, 'Audio', 'Sony', 'published', 1, 'sonyheadphones.jpg', NOW(), NOW()),
('iPad Pro', 'Professional tablet with M1 chip', 799.99, 25, 'Tablets', 'Apple', 'published', 1, 'ipadpro.jpg', NOW(), NOW()); 