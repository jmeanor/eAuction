SET storage_engine=INNODB;
SET GLOBAL event_scheduler = ON;

CREATE DATABASE IF NOT EXISTS eauction;
USE eauction;

DROP TABLE IF EXISTS addresses;
DROP TABLE IF EXISTS social_media;
DROP TABLE IF EXISTS people;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS items_in_categories;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS items_with_keywords;
DROP TABLE IF EXISTS keywords;
DROP TABLE IF EXISTS item_pictures;
DROP TABLE IF EXISTS ratings;
DROP TABLE IF EXISTS won_items;
DROP TABLE IF EXISTS bids;
DROP TABLE IF EXISTS credit_cards;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS users;
DROP PROCEDURE IF EXISTS proc_endAuction;

DELIMITER //
CREATE PROCEDURE `proc_endAuction` (IN endedauc_id INT)
BEGIN
   DECLARE res_price, max_bid, bidid, bid_count, bin_count INT;
   
   SELECT COUNT(*) INTO bid_count
   FROM bids 
   WHERE item_id = endedauc_id;
   
   IF bid_count > 0 THEN
      SELECT reserve_price INTO res_price
      FROM items 
      WHERE item_id = endedauc_id;
	  
	  SELECT COUNT(*) INTO bin_count
	  FROM bids
	  WHERE item_id = endedauc_id 
	  AND bid_type = 'buy-it-now';
      
      IF bin_count > 0 THEN
         SELECT bid_id INTO bidid 
		 FROM bids 
		 WHERE item_id = endedauc_id 
		 AND bid_type = 'buy-it-now';
		 
		 INSERT INTO won_items (item_id, winning_bid, date_won) 
         VALUES (endedauc_id, bidid, NOW());
      ELSE
         SELECT bid_id, price INTO bidid, max_bid
		 FROM bids 
		 WHERE item_id = endedauc_id 
		 AND price = (SELECT MAX(price) FROM items WHERE item_id = endedauc_id);
		 
		 IF max_bid >= res_price THEN
            INSERT INTO won_items (item_id, winning_bid, date_won) 
            VALUES (endedauc_id, bidid, NOW());
         ELSE
            DELETE FROM items 
            WHERE item_id = endedauc_id;
         END IF;
      END IF;
   ELSE
      DELETE FROM items 
      WHERE item_id = endedauc_id;
   END IF;
END //
DELIMITER ;

CREATE TABLE users (
    user_id INTEGER AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password CHAR(64) NOT NULL,
	salt CHAR(16) NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255),
    phone_number CHAR(10),
    description TEXT,
    public_location VARCHAR(255),
    url TEXT,
    user_type ENUM('person', 'company'),
    `admin` TINYINT(1) DEFAULT 0,
    PRIMARY KEY (user_id)
);

CREATE TABLE addresses (
    address_id INTEGER AUTO_INCREMENT,
    user_id INTEGER,
    street VARCHAR(255),
    city VARCHAR(255),
    state CHAR(2),
    zip CHAR(5),
    PRIMARY KEY (address_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id) 
        ON DELETE CASCADE
);

CREATE TABLE social_media (
    sm_id INTEGER AUTO_INCREMENT, 
    user_id INTEGER,
    sm_type ENUM('fb', 'tw'),
    username VARCHAR(255),
    PRIMARY KEY (sm_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id) 
        ON DELETE CASCADE
);

CREATE TABLE people (
    user_id INTEGER,
    age INTEGER,
    gender ENUM('M','F'),
    annual_income DECIMAL(10,2),
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON DELETE CASCADE
);

CREATE TABLE credit_cards (
    card_id INTEGER AUTO_INCREMENT,
    user_id INTEGER,
    card_type ENUM('MasterCard', 'Visa', 'American Express', 'Discover'),
    card_number VARCHAR(16),
    expiration VARCHAR(5),
    PRIMARY KEY (card_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON DELETE CASCADE
);

CREATE TABLE companies (
    user_id INTEGER,
    revenue DECIMAL(10,2),
    category VARCHAR(255),
    point_of_contact VARCHAR(255),
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON DELETE CASCADE
);

CREATE TABLE items (
    item_id INTEGER AUTO_INCREMENT,
    seller_id INTEGER DEFAULT 0,
    name VARCHAR(255),
    description TEXT,
    starting_price DECIMAL(10,2),
    buy_it_now_price DECIMAL(10,2) DEFAULT 0.00,
    reserve_price DECIMAL(10,2),
    start_time DATETIME, /*I don't know what version of MySQL we're using but the DEFAULT might work, might not*/
    location VARCHAR(255),
    url TEXT,
    template ENUM('1','2','3'),
    PRIMARY KEY (item_id),
    FOREIGN KEY (seller_id) REFERENCES users (user_id) 
        ON DELETE NO ACTION
);

CREATE TABLE categories (
    category_id INTEGER AUTO_INCREMENT,
    name VARCHAR(255),
    parent INTEGER,
    PRIMARY KEY (category_id),
    FOREIGN KEY (parent) REFERENCES categories (category_id)
        ON DELETE CASCADE
);

CREATE TABLE items_in_categories (
    item_id INTEGER,
    category_id INTEGER,
    PRIMARY KEY (item_id, category_id),
    FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories (category_id)
        ON DELETE NO ACTION
);

CREATE TABLE keywords (
    keyword VARCHAR(255),
    PRIMARY KEY (keyword)
);

CREATE TABLE items_with_keywords (
    item_id INTEGER,
    keyword VARCHAR(255),
    PRIMARY KEY (item_id, keyword),
    FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON DELETE CASCADE,
    FOREIGN KEY (keyword) REFERENCES keywords (keyword)
        ON DELETE NO ACTION
);

CREATE TABLE item_pictures (
    picture_id INTEGER AUTO_INCREMENT,
    item_id INTEGER,
    url TEXT,
    PRIMARY KEY (picture_id),
    FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON DELETE CASCADE
);

CREATE TABLE ratings (
    item_id INTEGER DEFAULT 0,
    buyer_id INTEGER,
    score DECIMAL(10,2),
    description TEXT,
    seller_response TEXT,
    PRIMARY KEY (item_id, buyer_id),
    FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON DELETE NO ACTION,
    FOREIGN KEY (buyer_id) REFERENCES users (user_id) 
        ON DELETE CASCADE
);

CREATE TABLE bids (
    bid_id INTEGER AUTO_INCREMENT,
    item_id INTEGER,
    card_id INTEGER,
    bid_type ENUM('bid','buy-it-now'),
    bid_datetime DATETIME,
    price DECIMAL(10,2),
    PRIMARY KEY (bid_id),
    FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON DELETE CASCADE,
    FOREIGN KEY (card_id) REFERENCES credit_cards (card_id) 
        ON DELETE NO ACTION
);

CREATE TABLE won_items (
    item_id INTEGER,
    winning_bid INTEGER,
    date_won DATETIME,
    item_received_date DATETIME,
    item_sent_date DATETIME,
    card_charged_date DATETIME,
    check_mailed_date DATETIME,
    successful_date DATETIME,
    failure_notification_date DATETIME,
    PRIMARY KEY (item_id),
    FOREIGN KEY (item_id) REFERENCES items (item_id)
        ON DELETE NO ACTION,
    FOREIGN KEY (winning_bid) REFERENCES bids (bid_id) 
        ON DELETE NO ACTION
);

INSERT INTO users (username, password, salt, name, email, phone_number, description, public_location, url, user_type, `admin`) VALUES
('zdeer1', 'b069bcb42b80b00a26a366ea6080928ea0e093c4cc9b30db043820c1c5208e97', '939730c1b8572dc', 'Zachary Deering', 'zpd5008@psu.edu', '8144040751', '', '', '', 'person', '1'), /*user_id = 1 */
('tjbyrne', '0620dc9dfeb19183b7900c2300e383ce59e8a5107920633279d96828eefde256', '247bc13449b21fdd', 'Tom Byrne', 'tjbyrne2@gmail.com', '5164245787', '', '', '', 'person', '1'), /* user_id = 2 */
('jmeanor', 'b794613dc6b7f87eb041a6b7f9ac8f01679695640b63941058e8fc269aec0664', '5f627b183bbb3703', 'John Meanor', 'jmeanor@gmail.com', '7246018842', '', '', '', 'person', '1'), /* user_id = 3 */
('lkeniston', 'af55358a59b0c488163a9bff7c7e688ec4efc0540bb361898ff6829a09f97f17', 'bf1036950723763', 'Luke Keniston', 'lkeniston@gmail.com', '9739608048', '', '', '', 'person', '1'), /* user_id = 4 */
('beckymapes', '47127b16141afeb74245b7c2fdeb2e9c5d00af1d4aeacfeba0ce80673fc74d86', '2b863f705537feac', 'Becky Mapes', 'bmapes@psu.edu', '8141234567', '', '', '', 'person', '0'), /* user_id = 5 */
('gad157', '68e573b8a62e00330ce69fb6da4bb96eaebc71bddf2b62e8f5863438b2174153', '7ed9bc011cb5b561', 'Greg Drane', 'gad157@psu.edu', '8147771234', '', '', '', 'person', '0'), /* user_id = 6 */
('psu', '1407f6d84960d9a783ad6409985b38f50c32f3bbbaa2e66c71e3f3234ca5d1a0', '712300c35f6f1b18', 'Pennsylvania State University', 'eauction@psu.edu', '8148651234', 'The Pennsylvania State University is a public land-grant university in Pennsylvania', 'University Park, PA', 'http://www.psu.edu', 'company', '0'), /* user_id = 7 */
('lhmartin', '05d6763da8ed6119eb367d7dd2f93f5ddec7b9a1afa6bf597c8d281cda7060b2', '7e8990d753ccf84e', 'Lockheed Martin', 'eauction@lockheed.com', '9995551234', 'Lockheed Martin is a government contractor', 'Washington, D.C.', 'http://www.lockheed.com/', 'company', '0'), /* user_id = 8 */
('bsclassic', '9fa9573c0558e8b98a9928b2ce5895b8b02d376002e2f68316961615a64d5be3', '38db6e20c9d7d36', 'Blue Sapphire Classic', 'bluesapphireclassic@gmail.com', '9995551235', 'The Blue Sapphire Classic is a charity organization that funds a scholarship for the Feature Twirler of the Penn State Blue Band', 'University Park, PA', 'http://blueband.psu.edu/bsc/', 'company', '0'), /* user_id = 9 */
('shweelz', 'cf085414d11cb6140788a1406db14f18701e23562e66b078cb9cc5a59479f7c4', '12a372be1e03d7eb', 'Shane Besong', 'shweelz@aol.com', '8144445555', '', '', '', 'person', '0'); /* user_id = 10 */

INSERT INTO addresses (user_id, street, city, state, zip) VALUES 
('1', '127 N. Sparks St. Apt. 8', 'State College', 'PA', '16801'),
('2', '750 E. College Ave.', 'State College', 'PA', '16801'),
('3', '255 S. Atherton St. Apt. 204', 'State College', 'PA', '16801'),
('4', '1038 N. Atherton St.', 'State College', 'PA', '16803'),
('5', '133 Beam Hall', 'University Park', 'PA', '16802'),
('6', '123 Main Street', 'Lemont', 'PA', '16801'),
('7', '101 Old Main', 'University Park', 'PA', '16802'),
('8', '3800 Pennsylvania Ave.', 'Washington', 'D.C.', '09542'),
('9', '111 Sheetz St.', 'Altoona', 'PA', '16875'),
('10', '308 Pancake Rd.', 'Snow Shoe', 'PA', '16543');

INSERT INTO social_media (user_id, sm_type, username) VALUES 
('1', 'tw', 'zdeer1'),
('5', 'fb', 'bmapes3');

INSERT INTO people (user_id, age, gender, annual_income) VALUES 
('1', '21', 'M', '50000.00'),
('2', '21', 'M', '100000.00'),
('3', '22', 'M', '75000.00'),
('4', '21', 'M', '125000.00'),
('5', '22', 'F', '25000.00'),
('6', '40', 'M', '65000.00'),
('10', '21', 'M', '92500.00');

INSERT INTO credit_cards (user_id, card_type, card_number, expiration) VALUES 
('1', 'Visa', '4567123498765432', '08/15'), /* card_id = 1 */
('2', 'MasterCard', '4567123498765432', '08/15'), /* card_id = 2 */
('3', 'Visa', '4567123498765432', '08/15'), /* card_id = 3 */
('4', 'American Express', '4567123498765432', '08/15'), /* card_id = 4 */
('5', 'Visa', '4567123498765432', '08/15'), /* card_id = 5 */
('6', 'Discover', '4567123498765432', '08/15'), /* card_id = 6 */
('10', 'MasterCard', '4567123498765432', '08/15'); /* card_id = 7 */

INSERT INTO companies (user_id, revenue, category, point_of_contact) VALUES 
('7', '19500000000.00', 'Non-Profit', 'Graham Spanier'),
('8', '750000000.00', 'Technology', 'Kyle Moshinsky'),
('9', '11000.00', 'Non-Profit', 'PJ Maierhofer');

INSERT INTO items (seller_id, name, description, starting_price, buy_it_now_price, reserve_price, start_time, location, url, template) VALUES
('1', 'Plush Leather Couch', 'A black leather sectional with little wear and tear. Only 6 months old!', '250.00', '0.00', '250.00', '2014-03-14 17:30:22', 'State College, PA', '', '1'), /* item_id = 1, COMPLETED */
('1', 'iPod Touch 2nd Gen', 'Barely used iPod Touch 2nd Generation (2009). 32 GB of storage, rear camera, and working home and lock buttons!', '50.00', '150.00', '75.00', '2014-04-24 01:22:15', 'State College, PA', '', '2'), /* item_id = 2 */
('5', 'Timex Watch', 'Antique Timex Watch. $750.00 retail in 1922.', '500.00', '900.00', '500.00', '2014-03-10 12:35:33', 'Richmond, VA', 'http://www.beckyswatches.com', '3'), /* item_id = 3, COMPLETED */
('5', 'Timex Watch', 'Antique Timex Watch. $750.00 retail in 1922.', '500.00', '900.00', '500.00', '2014-04-20 12:35:33', 'Richmond, VA', 'http://www.beckyswatches.com', '3'), /* item_id = 4 */
('5', 'Timex Watch', 'Antique Timex Watch. $750.00 retail in 1922.', '500.00', '900.00', '500.00', '2014-04-20 12:35:33', 'Richmond, VA', 'http://www.beckyswatches.com', '3'), /* item_id = 5 */
('7', 'Joe Paterno Statue', 'Bronze statue of an old, shamed coach. Must pick up.', '1500.00', '0.00', '1575.33', '2014-04-23 00:36:27', 'University Park, PA', 'http://www.psu.edu', '2'), /* item_id = 6 */
('7', 'Billieve Tshirt', 'White tshirt that says "Billieve" on it.', '3.00', '3.00', '3.00', '2014-04-18 18:35:00', 'University Park, PA', 'http://www.psu.edu', '1'), /* item_id = 7 */
('8', 'A2100 Geosynchronous Satellite', 'Lockheed Martin is at the forefront of the space-based telecommunications revolution. Leading the way is our A2100, one of the most powerful flight-proven commercial spacecraft currently available. This modular geosynchronous satellite has a design life of 15 years and a flexible payload capacity ideally suited to meet the demand for commercial space systems well into the 21st century-a demand driven by growth in mobile telephony, business services, direct broadcast, internet, multimedia and broadband services.', '500000.00', '0.00', '500000.00', '2014-04-21 10:00:00', 'Ft. Lauderdale, FL', 'http://www.lockheedmartin.com/us/products/a2100.html', '1'), /* item_id = 8 */
('9', 'Signed Photo of Matt Freeman', 'Photo of outgoing Blue Band Feature Twirler Matt Freeman. Signed by Matt himself!', '10.00', '15.00', '11.00', '2014-04-19 11:35:23', 'State College, PA', 'http://blueband.psu.edu/bsc/', '3'), /* item_id = 9 */
('9', 'Signed Photo of Rachel Reiss', 'Photo of incoming Blue Band Feature Twirler Rachel Reiss. Signed by Rachel herself!', '10.00', '15.00', '11.00', '2014-04-19 11:35:23', 'State College, PA', 'http://blueband.psu.edu/bsc/', '3'); /* item_id = 10 */

DELIMITER //
DROP EVENT IF EXISTS item_event_2 //
CREATE EVENT item_event_2
   ON SCHEDULE AT '2014-05-08 01:22:15'
   DO
      BEGIN
         CALL proc_endAuction(2);
      END //

DROP EVENT IF EXISTS item_event_4 //
CREATE EVENT item_event_4
   ON SCHEDULE AT '2014-05-04 12:35:33'
   DO
      BEGIN
         CALL proc_endAuction(4);
      END //
   
DROP EVENT IF EXISTS item_event_5 //
CREATE EVENT item_event_5
   ON SCHEDULE AT '2014-05-04 12:35:33'
   DO
      BEGIN
         CALL proc_endAuction(5);
      END //
   
DROP EVENT IF EXISTS item_event_6 //
CREATE EVENT item_event_6
   ON SCHEDULE AT '2014-05-07 00:36:27'
   DO
      BEGIN
         CALL proc_endAuction(6);
      END //

DROP EVENT IF EXISTS item_event_7 //      
CREATE EVENT item_event_7
   ON SCHEDULE AT '2014-05-02 18:35:00'
   DO
      BEGIN
         CALL proc_endAuction(7);
      END //

DROP EVENT IF EXISTS item_event_8 //      
CREATE EVENT item_event_8
   ON SCHEDULE AT '2014-05-05 10:00:00'
   DO
      BEGIN
         CALL proc_endAuction(8);
      END //

DROP EVENT IF EXISTS item_event_9 //      
CREATE EVENT item_event_9
   ON SCHEDULE AT '2014-05-03 11:35:23'
   DO
      BEGIN
         CALL proc_endAuction(9);
      END //

DROP EVENT IF EXISTS item_event_10 //      
CREATE EVENT item_event_10
   ON SCHEDULE AT '2014-05-03 11:35:23'
   DO
      BEGIN
         CALL proc_endAuction(10);
      END //

DELIMITER ;

INSERT INTO categories (name, parent) VALUES 
('root', '1'), /* category_id = 1, root */
('Electronics', '1'), /* category_id = 2 */
('Music Players', '2'), /* category_id = 3 */
('Movies, Music, and Games', '2'), /* category_id = 4 */
('Bluray', '4'), /* category_id = 5 */
('DVD', '4'), /* category_id = 6 */
('Games', '4'), /* category_id = 7 */
('CD', '4'), /* category_id = 8 */
('Home Decor and Furniture', '1'), /* category_id = 9 */
('Clothing and Accessories', '1'), /* category_id = 10 */
('Outerwear', '10'), /* category_id = 11 */
('Accessories', '10'), /* category_id = 12 */
('Shirts', '10'), /* category_id = 13 */
('Pants', '10'), /* category_id = 14 */
('Collectibles', '1'), /* category_id = 15 */
('Books', '1'); /* category_id = 16 */

INSERT INTO items_in_categories (item_id, category_id) VALUES 
('1', '9'),
('2', '3'),
('3', '12'),
('4', '12'),
('5', '12'),
('6', '15'),
('7', '13'),
('8', '2'),
('9', '15'),
('10', '15');

INSERT INTO keywords (keyword) VALUES 
('leather'),
('couch'),
('watch'),
('coach'),
('JoePa'),
('O\'Brien'),
('feature'),
('twirler'),
('picture');

INSERT INTO items_with_keywords (item_id, keyword) VALUES 
('1', 'leather'),
('1', 'couch'),
('3', 'watch'),
('4', 'watch'),
('5', 'watch'),
('6', 'coach'),
('6', 'JoePa'),
('7', 'coach'),
('7', 'O\'Brien'),
('9', 'feature'),
('9', 'twirler'),
('9', 'picture'),
('10', 'feature'),
('10', 'twirler'),
('10', 'picture');

INSERT INTO item_pictures (item_id, url) VALUES 
('1', 'shop/images/1-couch.gif'),
('2', 'shop/images/2-ipod1.jpg'),
('2', 'shop/images/2-ipod2.jpg'),
('3', 'shop/images/345-watch.jpg'),
('4', 'shop/images/345-watch.jpg'),
('5', 'shop/images/345-watch.jpg'),
('6', 'shop/images/6-statue1.jpg'),
('6', 'shop/images/6-statue2.jpg'),
('7', 'shop/images/7-billieve.jpg'),
('8', 'shop/images/8-sat.jpg'),
('9', 'shop/images/9-matt.jpg'),
('10', 'shop/images/10-rachel.jpg');

INSERT INTO ratings (item_id, buyer_id, score, description, seller_response) VALUES 
('1', '3', '5.10', 'Item still hasn\'t shipped, two weeks later.', 'Your item will be shipped once it\'s done being restored.'),
('3', '1', '9.50', 'Couch was exactly as I expected. Love it!', '');

INSERT INTO bids (item_id, card_id, bid_type, bid_datetime, price) VALUES
('3', '1', 'buy-it-now', '2014-03-11 23:30:21', '900.00'), /* bid_id = 1 */
('1', '6', 'bid', '2014-03-15 00:30:35', '250.00'), /* bid_id = 2 */
('1', '3', 'bid', '2014-03-19 16:15:00', '262.50'); /* bid_id = 3 */

INSERT INTO won_items (item_id, winning_bid, date_won, item_received_date, item_sent_date, card_charged_date, check_mailed_date, successful_date) VALUES
('1', '3', '2014-03-28 17:30:22', '', '', '', '', ''),
('3', '1', '2014-03-11 23:30:21', '2014-03-13 17:22:00', '2014-03-15 14:23:00', '2014-03-14 10:00:00', '2014-03-14 14:23:00', '2014-03-15 14:30:00');