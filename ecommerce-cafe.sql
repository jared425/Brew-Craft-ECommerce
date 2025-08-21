CREATE DATABASE cafe_ecommerce;
USE cafe_ecommerce;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    is_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(255),
    token_expires_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('cafe_kit', 'mug', 'coffee', 'machines', 'decor', 'others') NOT NULL,
    image_path VARCHAR(255),
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

CREATE TABLE payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    method_type ENUM('card', 'paypal', 'ozow') NOT NULL,
    card_number VARCHAR(20),
    card_expiry VARCHAR(10),
    card_cvv VARCHAR(5),
    paypal_username VARCHAR(100),
    paypal_account VARCHAR(100),
    ozow_username VARCHAR(100),
    ozow_account VARCHAR(100),
    is_default BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    payment_method_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE RESTRICT
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT INTO users (username, email, password, first_name, last_name, address, phone, is_admin)
VALUES ('Aaliyah', 'aaliyahvermeulen1001@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Aaliyah', 'Vermeulen', '9 Long Street', '0653261254', TRUE);

INSERT INTO products (name, description, price, category, image_path, stock) VALUES
('Artisan Pour-Over Coffee Kit', 'Complete pour-over set includes: ceramic dripper, 100 filters, glass carafe, gooseneck kettle, scoop, and premium coffee samples. Perfect for home baristas.', 429.99, 'cafe_kit', 'https://img.ltwebstatic.com/v4/j/spmp/2025/06/18/6f/1750232247a9795f76e98f54332ca3363c35966bd9_thumbnail_900x.webp', 50),
('Stainless Steel Portable Coffee Kit (6-Piece)', 'Durable camping set includes: French press, 2 double-wall mugs, coffee scoop, storage tin, and carrying case. Ideal for travel and outdoor adventures.', 999.99, 'cafe_kit', 'https://img.kwcdn.com/product/fancy/bc9a8bb4-09af-49a5-8847-a972546d1d7d.jpg?imageView2/2/w/800/q/70/format/webp', 100),
('Ceramic Coffee Gift Set with Storage Box', 'Premium 9-piece set features: handcrafted ceramic pour-over, 2 mugs, wooden scoop, linen coasters, and premium beans in a beautiful gift-ready box.', 1300.00, 'cafe_kit', 'https://img.ltwebstatic.com/images3_spmp/2024/09/06/65/17255866321869d987ff017bc205d2f3855aa8d291_thumbnail_405x.webp', 200),
('Hamilton Beach Programmable Coffee Kit', 'Complete 9-cup system with: thermal carafe, reusable gold-tone filter, measuring scoop, and cleaning brush. Includes "Coffee 101" beginner guide.', 1420.50, 'cafe_kit', 'https://www.seriouseats.com/thmb/E4XIUE1SiMK2OrEIM-9fgBNwkp8=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/CoffeePercolatorLeadImage-d2b1c3e6caec42d78348e462f267e942.jpg', 150),
('Vietnamese Phin Coffee Starter Kit', 'Authentic set includes: 3 stainless steel phin filters, condensed milk sampler, 2 traditional cups, and robusta coffee blend. Makes traditional ca phe sua da.', 350.00, 'cafe_kit', 'https://m.media-amazon.com/images/I/91msS+M3SmL._AC_UF894,1000_QL80_DpWeblab_.jpg', 300),
('Premium Glass French Press Kit', '600ml borosilicate glass press with: stainless steel filter, 2 tempered glass mugs, coffee sampler, and brewing guide. Dishwasher safe for easy cleanup.', 900.00, 'cafe_kit', 'https://cdn11.bigcommerce.com/s-6h7ychjk4/images/stencil/1280x1280/products/9061/89037/yield-french-press-amber-glass-front-angle__78539.1597181148.jpg?c=1&imbypass=on', 250);

-- Machines Data
INSERT INTO products (name, description, price, category, image_path, stock) VALUES
('Nespresso Vertuo Plus', 'Premium single-serve coffee maker with centrifusion technology for perfect extraction and rich crema', 2279.99, 'machines', 'https://assets.epicurious.com/photos/63cec84d1686ec7198222bdb/1:1/w_3333,h_3333,c_limit/best-single-serve-coffee-maker_HERO_011823_13345_V1_Badge_final.jpg', 50),
('Programmable Coffee Maker 1.5 Litre', '12-cup drip coffee machine with programmable timer and strong brew option for home or office use', 1500.90, 'machines', 'https://media.takealot.com/covers_images/6b9aae00ca414804a72b0326e2ce183e/s-pdpxl.file', 30),
('Kaffe Glass French Press', 'Classic 34oz glass French press with stainless steel filter for full-flavored coffee extraction', 880.90, 'machines', 'https://kaffeproducts.com/cdn/shop/files/KF1010-02.jpg?v=1739669174', 25),
('Double Wall French Press', 'Insulated stainless steel French press that keeps coffee hot for hours with durable construction', 59.99,'machines', 'https://m.media-amazon.com/images/I/51DVCWYIKnL.jpg', 20),
('IAGREEA Espresso Machine', '15-bar pressure semi-automatic espresso machine with milk frother for café-quality drinks at home', 1238.87, 'machines', 'https://img.ltwebstatic.com/v4/j/spmp/2025/07/22/e0/1753168682c1b590f90547abe5d81c780b9a06b996_thumbnail_900x.webp', 40),
('Breville Espresso Machine', 'Professional-grade espresso maker with precision temperature control and commercial-style steam wand', 8830.90, 'machines', 'https://assets.epicurious.com/photos/62741684ef40ea9d3866a0be/16:9/w_2560%2Cc_limit/breville-bambino-espresso-maker_HERO_050422_8449_VOG_Badge_final.jpg', 35),
('Nespresso Pod Machine', 'Compact pod coffee system with one-touch operation and fast heat-up time (under 25 seconds)', 1920.99, 'machines', 'https://img.ltwebstatic.com/images3_spmp/2025/01/10/66/1736492162ebc408df44c3b600d5b3df02cb85d0ef_thumbnail_900x.webp', 55),
('Baccarat Barista Italico Espresso Maker', 'Classic stovetop moka pot that produces rich, concentrated coffee with authentic Italian design', 549.00, 'machines', 'https://thefoschini.vtexassets.com/arquivos/ids/214291629-1200-1600?v=638901923854400000&width=1200&height=1600&aspect=true', 55);

-- Mugs Data
INSERT INTO products (name, description, price, category, image_path, stock) VALUES
('Custom Coffee Mug', 'Personalized ceramic mug (holds 14oz) with dishwasher-safe ink. Choose from 5 font styles - perfect for gifts or your daily brew.', 299.99, 'mug', 'https://img.ltwebstatic.com/images3_spmp/2024/12/12/26/1733967749766d8d6054a5cb82e6fc2df03067c0c7_thumbnail_900x.webp', 50),
('Classic Stoneware Coffee Mug', '12oz heavyweight ceramic mug with ergonomic handle and chip-resistant glaze. Microwave and dishwasher safe - ideal for home or office.', 135.99, 'mug', 'https://m.media-amazon.com/images/I/51UnYsLgbVL.jpg', 100),
('Handmade Artisan Ceramic Mug', 'Unique 10oz mug with organic textures and food-safe glaze. Each piece is one-of-a-kind by local potters (includes authenticity card).', 420.80, 'mug', 'https://www.amandakjewelry.com/cdn/shop/files/PhotoMay052024_44632PM.jpg?v=1725312909&width=1946', 200),
('Vacuum-Insulated Travel Mug', '16oz stainless steel mug with leak-proof lid keeps drinks hot for 6+ hours. Fits most cup holders (comes with 2 lid types: sip and straw).', 255.90, 'mug', 'https://images.thdstatic.com/productImages/af66371a-741f-4934-9019-6de53cd09297/svn/mr-coffee-travel-mugs-tumblers-985120333m-64_600.jpg', 150),
('Borocilicate Glass Coffee Mug', '8oz double-wall glass mug shows off coffee layers while staying cool to touch. Heat-resistant up to 150°C - perfect for pour-over and lattes.', 220.99, 'mug', 'https://www.zhoozh.co.za/cdn/shop/products/images_-_2020-11-26T055404.877.jpg?v=1614957100', 300),
('Italian Espresso Cup & Saucer Set', 'Authentic 3oz porcelain demitasse set (cup + saucer) for true espresso experience. Stackable design with reinforced rim.', 300.00, 'mug', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRwqRr1YUkXfWxWa8PWT9-qrvY5yJKAbdhOzA&s', 250);

-- Deco Data
INSERT INTO products (name, description, price, category, image_path, stock) VALUES
('"But First Coffee" Wall Art', '12x16" framed canvas print with trendy coffee quote. Ready to hang with sawtooth hanger (available in 3 color schemes).', 299.99, 'decor', 'https://i.etsystatic.com/35571911/c/1271/1271/103/130/il/c0a924/4852543377/il_600x600.4852543377_n3oz.jpg', 50),
('Rustic Wood Coffee Station Shelf', '20" reclaimed wood shelf with metal hooks for mug display and lower rack for coffee gear. Includes mounting hardware.', 320.90, 'decor', 'https://www.jackcubedesign.com/cdn/shop/products/1_e21cd7f6-1810-457d-ab3d-0a7d53e8a58b.jpg?v=1617587714', 100),
('LED Neon Coffee Sign', 'Customizable 15" neon-style LED wall lamp with "Coffee Time" design. USB-powered with 10 color modes (remote included).', 264.99, 'decor', 'https://img.kwcdn.com/product/Fancyalgo/VirtualModelMatting/89d65985567ffd064808b86b4b849729.jpg?imageMogr2/auto-orient|imageView2/2/w/800/q/70/format/webp', 200),
('Vintage "More Espresso" Poster', '11x14" high-quality print featuring kimono cat art with humorous coffee quote. Printed on matte archival paper.', 190.99, 'decor', 'https://img.ltwebstatic.com/images3_spmp/2024/08/10/bc/1723259272b3fea17c1c76cd658d9d233183e098ba_thumbnail_900x.webp', 150),
('Minimalist Coffee Wall Prints (Set of 3)', '8x10" abstract coffee-themed art prints in neutral tones. Fits standard frames (packaged with protective sleeve).', 200.99, 'decor', 'https://img.ltwebstatic.com/v4/j/spmp/2025/04/23/a2/1745409115e18c348275ea81d98fde3e40a1efcbea_thumbnail_900x.webp', 300),
('Digital Coffee Art Collection', 'Instant download package with 5 printable coffee illustrations (JPEG + PNG). Perfect for DIY framing or gifts.', 150.90, 'decor', 'https://img.ltwebstatic.com/images3_spmp/2025/02/28/21/1740739490f1a096115cf38b8f41856fb5530be4d4_thumbnail_900x.webp', 250);

-- Other Data
-- Cleaning & Maintenance Products
INSERT INTO products (name, description, price, category, image_path, stock) VALUES
-- Machine Cleaning
('Cafiza Espresso Machine Cleaning Tablets (100ct)', 'Professional backflush tablets remove coffee oils from group heads and portafilters. Safe for all espresso machines.', 229.99, 'others', 'https://craftcoffeecanada.com/cdn/shop/products/Cafizatablets8x2g.jpg?v=1618444227', 200),
('Dezcal Descaling Solution (340g)', 'Citric acid-based descaler for coffee makers and kettles. Treats hard water buildup in 20 minutes (makes 4-5 treatments).', 560.00, 'others', 'https://us.moccamaster.com/cdn/shop/products/Dezcal_Descaling_Powder_3pk-386493.png?v=1683911816', 150),
('3-Piece Barista Brush Set', 'Nylon bristle brushes for group heads, portafilters, and grinders. Includes angled tip for hard-to-reach areas.', 220.00, 'others', 'https://m.media-amazon.com/images/I/71QznqIlpiL.jpg', 100),
-- Grinder Maintenance
('Urnex Grindz Grinder Cleaner (340g)', 'Food-safe cleaning pellets remove coffee oils from burrs. Run through grinder like normal beans.', 430.00, 'others', 'https://m.media-amazon.com/images/I/61mhG3Dw5gL.jpg', 120),
('Mini Coffee Grinder Vacuum', '200W handheld vacuum with narrow nozzle for ground coffee removal. USB rechargeable.', 960.90, 'others', 'https://m.media-amazon.com/images/I/71IfmHJCjUL._AC_UF894,1000_QL80_.jpg', 80),
-- Barista tools
('WDT Tool (Needle Distributor)', '9-needle tool for even coffee puck preparation. Anodized aluminum base.', 280.50, 'others', 'https://m.media-amazon.com/images/I/71gcoBCqrIL.jpg', 110),
('Calibrated Espresso Tamper', '58mm tamper with pressure gauge (15-30kg). CNC-machined stainless steel.', 89.99, 'others', 'https://cafeliegeois.ca/cdn/shop/files/level-calibrated-tamper-650849.jpg?v=1730809409', 60),
-- Sustainability
('Reusable Metal Pour-Over Filter', '304 stainless steel filter for Hario V60 (size 02). No paper waste, easy cleaning.', 210.99, 'others', 'https://m.media-amazon.com/images/I/71RS6CobTIL.jpg', 130);

-- Coffee data
INSERT INTO products (name, description, price, category, image_path, stock) VALUES
('Ethiopia Yirgacheffe', 'Floral and tea-like single-origin coffee with bright citrus notes', 330.45, 'coffee', 'https://mochaberry.ca/wp-content/uploads/coffee-FairtradeOrganic-EthiopiaYirgacheffe.jpg', 100),
('Colombia Huila Supremo', 'Balanced medium roast with milk chocolate sweetness', 629.99, 'coffee', 'https://javadoro.com/cdn/shop/products/Colombian_1024x1024@2x.png?v=1571327326', 80),
('Sumatra Mandheling', 'Full-bodied dark roast with earthy depth and spice', 280.90, 'coffee', 'https://www.vailcoffee.com/cdn/shop/files/VMCT-12oz-Sumatra.jpg?v=1733208045', 60),
('Morning Glory Blend', 'Signature medium-dark blend perfect for espresso or drip', 316.80, 'coffee', 'https://shop.nealbrothersfoods.com/cdn/shop/products/CC_MornGlory_FrontLarge.jpg?v=1702672033&width=1445', 120),
('Night Owl Decaf', 'Swiss Water Process decaf with velvety cocoa notes', 370.00, 'coffee', 'https://matieshop.co.za/wp-content/uploads/2024/09/4f371b65-2008-4374-b23a-03ffaf2de762.jpg', 90),
('Gesha Village GVA.10 Oma', 'Rare award-winning coffee with explosive floral aroma', 457.25, 'coffee', 'https://images.squarespace-cdn.com/content/v1/5f80761e4428d4743ca314a5/b082fb45-a092-46ac-b95e-234cff046df0/Gesha-Village-Full-Court-Press-Speciality-Coffee-Oma-Auction-Lot-Ethiopia.png', 20),
('Cold Brew Blend', 'Coarse-ground dark roast optimized for smooth cold brew', 285.95, 'coffee', 'https://joffreys.com/cdn/shop/files/WEB-Mockup-2022-ColdBrew-CBG.png?v=1708847702', 70),
('Italian Roast Espresso', 'Classic dark roast pre-ground for espresso machines', 344.50, 'coffee', 'https://deansbeans.com/cdn/shop/products/6aa121f806529f2a5fe70ee8bacde71c.jpg?v=1741357878', 110),
('Seasonal Reserve Box', 'Handpicked microlot coffees available for 3 months only. Includes tasting notes card and brew guide.', 750.00, 'coffee', 'https://us.zohocommercecdn.com/product-images/Seasonal+250gr.png/3838613000013141057/600x600?storefront_domain=www.brotherscoffee.co.za', 30);