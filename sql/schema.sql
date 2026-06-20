CREATE TABLE IF NOT EXISTS vendors (
  id VARCHAR(32) PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  category VARCHAR(80) NOT NULL,
  city VARCHAR(80) NOT NULL,
  budget VARCHAR(80) NOT NULL,
  price VARCHAR(80) NOT NULL,
  rating DECIMAL(2,1) NOT NULL DEFAULT 0,
  review_count VARCHAR(40) NOT NULL DEFAULT '0 reviews',
  verified TINYINT(1) NOT NULL DEFAULT 0,
  gradient VARCHAR(40) NOT NULL,
  tagline VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  styles_json JSON NULL,
  highlights_json JSON NULL,
  packages_json JSON NULL,
  gallery_json JSON NULL,
  image_url VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS weddings (
  id VARCHAR(32) PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  subtitle VARCHAR(255) NOT NULL,
  city VARCHAR(80) NOT NULL,
  season VARCHAR(80) NOT NULL,
  gradient VARCHAR(40) NOT NULL,
  wedding_date VARCHAR(80) NOT NULL,
  excerpt VARCHAR(255) NOT NULL,
  story TEXT NOT NULL,
  palette_json JSON NULL,
  vendors_json JSON NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS saved_vendors (
  session_id VARCHAR(128) NOT NULL,
  vendor_id VARCHAR(32) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (session_id, vendor_id),
  INDEX idx_saved_vendor_id (vendor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS checklist_items (
  session_id VARCHAR(128) NOT NULL,
  item_id VARCHAR(80) NOT NULL,
  completed TINYINT(1) NOT NULL DEFAULT 0,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (session_id, item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS enquiries (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(128) NOT NULL,
  vendor_id VARCHAR(32) NOT NULL,
  name VARCHAR(160) NOT NULL,
  email VARCHAR(180) NOT NULL,
  phone VARCHAR(80) NULL,
  event_date DATE NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_enquiries_session (session_id),
  INDEX idx_enquiries_vendor (vendor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS chat_messages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(128) NOT NULL,
  role ENUM('user', 'assistant') NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_chat_session (session_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS vendor_registrations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(128) NOT NULL,
  business_name VARCHAR(180) NOT NULL,
  category VARCHAR(80) NOT NULL,
  city VARCHAR(80) NOT NULL,
  contact_name VARCHAR(160) NOT NULL,
  email VARCHAR(180) NOT NULL,
  phone VARCHAR(80) NULL,
  price_range VARCHAR(120) NULL,
  message TEXT NULL,
  status ENUM('new', 'reviewing', 'approved', 'declined') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_vendor_registrations_session (session_id),
  INDEX idx_vendor_registrations_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
