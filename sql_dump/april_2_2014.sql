SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip` char(5) DEFAULT NULL,
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `addresses` (`address_id`, `user_id`, `street`, `city`, `state`, `zip`) VALUES
(1, 1, '127 N. Sparks St. Apt. 8', 'State College', 'PA', '16801'),
(2, 2, '750 E. College Ave.', 'State College', 'PA', '16801'),
(3, 3, '255 S. Atherton St. Apt. 204', 'State College', 'PA', '16801'),
(4, 4, '1038 N. Atherton St.', 'State College', 'PA', '16803'),
(5, 5, '133 Beam Hall', 'University Park', 'PA', '16802'),
(6, 6, '123 Main Street', 'Lemont', 'PA', '16801'),
(7, 7, '101 Old Main', 'University Park', 'PA', '16802'),
(8, 8, '3800 Pennsylvania Ave.', 'Washington', 'D.', '09542'),
(9, 9, '111 Sheetz St.', 'Altoona', 'PA', '16875'),
(10, 10, '308 Pancake Rd.', 'Snow Shoe', 'PA', '16543');

CREATE TABLE IF NOT EXISTS `bids` (
  `bid_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `bid_type` enum('bid','buy-it-now') DEFAULT NULL,
  `bid_datetime` datetime DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`bid_id`),
  KEY `item_id` (`item_id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `bids` (`bid_id`, `item_id`, `card_id`, `bid_type`, `bid_datetime`, `price`) VALUES
(1, 3, 1, 'buy-it-now', '2014-03-11 11:30:21', 900.00),
(2, 1, 6, 'bid', '2014-03-15 12:30:35', 250.00),
(3, 1, 3, 'bid', '2014-03-19 04:15:00', 262.50);

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

INSERT INTO `categories` (`category_id`, `name`, `parent`) VALUES
(1, 'root', 1),
(2, 'Electronics', 1),
(3, 'Music Players', 2),
(4, 'Movies, Music, and Games', 2),
(5, 'Bluray', 4),
(6, 'DVD', 4),
(7, 'Games', 4),
(8, 'CD', 4),
(9, 'Home Decor and Furniture', 1),
(10, 'Clothing and Accessories', 1),
(11, 'Outerwear', 10),
(12, 'Accessories', 10),
(13, 'Shirts', 10),
(14, 'Pants', 10),
(15, 'Collectibles', 1),
(16, 'Books', 1);

CREATE TABLE IF NOT EXISTS `companies` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `revenue` decimal(10,2) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `point_of_contact` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `companies` (`user_id`, `revenue`, `category`, `point_of_contact`) VALUES
(7, 99999999.99, 'Non-Profit', 'Graham Spanier'),
(8, 99999999.99, 'Technology', 'Kyle Moshinsky'),
(9, 11000.00, 'Non-Profit', 'PJ Maierhofer');

CREATE TABLE IF NOT EXISTS `credit_cards` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `card_type` enum('MasterCard','Visa','American Express','Discover') DEFAULT NULL,
  `card_number` varchar(16) DEFAULT NULL,
  `expiration` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`card_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

INSERT INTO `credit_cards` (`card_id`, `user_id`, `card_type`, `card_number`, `expiration`) VALUES
(1, 1, 'Visa', '4567123498765432', '08/15'),
(2, 2, 'MasterCard', '4567123498765432', '08/15'),
(3, 3, 'Visa', '4567123498765432', '08/15'),
(4, 4, 'American Express', '4567123498765432', '08/15'),
(5, 5, 'Visa', '4567123498765432', '08/15'),
(6, 6, 'Discover', '4567123498765432', '08/15'),
(7, 10, 'MasterCard', '4567123498765432', '08/15');

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `starting_price` decimal(10,2) DEFAULT NULL,
  `buy_it_now_price` decimal(10,2) DEFAULT '0.00',
  `reserve_price` decimal(10,2) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `url` text,
  `template` enum('1','2','3') DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `seller_id` (`seller_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `items` (`item_id`, `seller_id`, `name`, `description`, `starting_price`, `buy_it_now_price`, `reserve_price`, `start_time`, `location`, `url`, `template`) VALUES
(1, 1, 'Plush Leather Couch', 'A black leather sectional with little wear and tear. Only 6 months old!', 250.00, 0.00, 250.00, '2014-03-14 05:30:22', 'State College, PA', '', '1'),
(2, 1, 'iPod Touch 2nd Gen', 'Barely used iPod Touch 2nd Generation (2009). 32 GB of storage, rear camera, and working home and lock buttons!', 50.00, 150.00, 75.00, '2014-04-02 01:22:15', 'State College, PA', '', '2'),
(3, 5, 'Timex Watch', 'Antique Timex Watch. $750.00 retail in 1922.', 500.00, 900.00, 500.00, '2014-03-10 12:35:33', 'Richmond, VA', 'http://www.beckyswatches.com', '3'),
(4, 5, 'Timex Watch', 'Antique Timex Watch. $750.00 retail in 1922.', 500.00, 900.00, 500.00, '2014-04-10 12:35:33', 'Richmond, VA', 'http://www.beckyswatches.com', '3'),
(5, 5, 'Timex Watch', 'Antique Timex Watch. $750.00 retail in 1922.', 500.00, 900.00, 500.00, '2014-04-10 12:35:33', 'Richmond, VA', 'http://www.beckyswatches.com', '3'),
(6, 7, 'Joe Paterno Statue', 'Bronze statue of an old, shamed coach. Must pick up.', 1500.00, 0.00, 1575.33, '2014-04-03 12:36:27', 'University Park, PA', 'http://www.psu.edu', '2'),
(7, 7, 'Billeve Tshirt', 'White tshirt that says "Billeve" on it.', 3.00, 3.00, 3.00, '2014-04-01 06:35:00', 'University Park, PA', 'http://www.psu.edu', '1'),
(8, 8, 'A2100 Geosynchronous Satellite', 'Lockheed Martin is at the forefront of the space-based telecommunications revolution. Leading the way is our A2100, one of the most powerful flight-proven commercial spacecraft currently available. This modular geosynchronous satellite has a design life of 15 years and a flexible payload capacity ideally suited to meet the demand for commercial space systems well into the 21st century—a demand driven by growth in mobile telephony, business services, direct broadcast, internet, multimedia and broadband services.', 500000.00, 0.00, 500000.00, '2014-04-04 10:00:00', 'Ft. Lauderdale, FL', 'http://www.lockheedmartin.com/us/products/a2100.html', '1'),
(9, 9, 'Signed Photo of Matt Freeman', 'Photo of outgoing Blue Band Feature Twirler Matt Freeman. Signed by Matt himself!', 10.00, 15.00, 11.00, '2014-04-08 11:35:23', 'State College, PA', 'http://blueband.psu.edu/bsc/', '3'),
(10, 9, 'Signed Photo of Rachel Reiss', 'Photo of incoming Blue Band Feature Twirler Rachel Reiss. Signed by Rachel herself!', 10.00, 15.00, 11.00, '2014-04-08 11:35:23', 'State College, PA', 'http://blueband.psu.edu/bsc/', '3');

CREATE TABLE IF NOT EXISTS `items_in_categories` (
  `item_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`,`category_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `items_in_categories` (`item_id`, `category_id`) VALUES
(1, 9),
(2, 3),
(3, 12),
(4, 12),
(5, 12),
(6, 15),
(7, 13),
(8, 2),
(9, 15),
(10, 15);

CREATE TABLE IF NOT EXISTS `items_with_keywords` (
  `item_id` int(11) NOT NULL DEFAULT '0',
  `keyword` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`item_id`,`keyword`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `items_with_keywords` (`item_id`, `keyword`) VALUES
(1, 'couch'),
(1, 'leather'),
(3, 'watch'),
(4, 'watch'),
(5, 'watch'),
(6, 'coach'),
(6, 'JoePa'),
(7, 'coach'),
(7, 'O''Brien'),
(9, 'feature'),
(9, 'picture'),
(9, 'twirler'),
(10, 'feature'),
(10, 'picture'),
(10, 'twirler');

CREATE TABLE IF NOT EXISTS `item_pictures` (
  `picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `url` text,
  PRIMARY KEY (`picture_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `keywords` (
  `keyword` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `keywords` (`keyword`) VALUES
('coach'),
('couch'),
('feature'),
('JoePa'),
('leather'),
('O''Brien'),
('picture'),
('twirler'),
('watch');

CREATE TABLE IF NOT EXISTS `people` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `age` int(11) DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `annual_income` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `people` (`user_id`, `age`, `gender`, `annual_income`) VALUES
(1, 21, 'M', 50000.00),
(2, 21, 'M', 100000.00),
(3, 22, 'M', 75000.00),
(4, 21, 'M', 125000.00),
(5, 22, 'F', 25000.00),
(6, 40, 'M', 65000.00),
(10, 21, 'M', 92500.00);

CREATE TABLE IF NOT EXISTS `ratings` (
  `item_id` int(11) NOT NULL DEFAULT '0',
  `buyer_id` int(11) NOT NULL DEFAULT '0',
  `score` decimal(10,2) DEFAULT NULL,
  `description` text,
  `seller_response` text,
  PRIMARY KEY (`item_id`,`buyer_id`),
  KEY `buyer_id` (`buyer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `ratings` (`item_id`, `buyer_id`, `score`, `description`, `seller_response`) VALUES
(1, 3, 5.10, 'Item still hasn''t shipped, two weeks later.', 'Your item will be shipped once it''s done being restored.'),
(3, 1, 9.50, 'Couch was exactly as I expected. Love it!', '');

CREATE TABLE IF NOT EXISTS `social_media` (
  `sm_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sm_type` enum('fb','tw') DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sm_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `social_media` (`sm_id`, `user_id`, `sm_type`, `username`) VALUES
(1, 1, 'tw', 'zdeer1'),
(2, 5, 'fb', 'bmapes3');

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(16) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` char(10) DEFAULT NULL,
  `description` text,
  `public_location` varchar(255) DEFAULT NULL,
  `url` text,
  `user_type` enum('person','company') DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `users` (`user_id`, `username`, `password`, `salt`, `name`, `email`, `phone_number`, `description`, `public_location`, `url`, `user_type`) VALUES
(1, 'zdeer1', 'b069bcb42b80b00a26a366ea6080928ea0e093c4cc9b30db043820c1c5208e97', '939730c1b8572dc', 'Zachary Deering', 'zpd5008@psu.edu', '8144040751', '', '', '', 'person'),
(2, 'tjbyrne', '0620dc9dfeb19183b7900c2300e383ce59e8a5107920633279d96828eefde256', '247bc13449b21fdd', 'Tom Byrne', 'tjbyrne2@gmail.com', '5164245787', '', '', '', 'person'),
(3, 'jmeanor', 'b794613dc6b7f87eb041a6b7f9ac8f01679695640b63941058e8fc269aec0664', '5f627b183bbb3703', 'John Meanor', 'jmeanor@gmail.com', '7246018842', '', '', '', 'person'),
(4, 'lkeniston', 'af55358a59b0c488163a9bff7c7e688ec4efc0540bb361898ff6829a09f97f17', 'bf1036950723763', 'Luke Keniston', 'lkeniston@gmail.com', '9739608048', '', '', '', 'person'),
(5, 'beckymapes', '47127b16141afeb74245b7c2fdeb2e9c5d00af1d4aeacfeba0ce80673fc74d86', '2b863f705537feac', 'Becky Mapes', 'bmapes@psu.edu', '8141234567', '', '', '', 'person'),
(6, 'gad157', '68e573b8a62e00330ce69fb6da4bb96eaebc71bddf2b62e8f5863438b2174153', '7ed9bc011cb5b561', 'Greg Drane', 'gad157@psu.edu', '8147771234', '', '', '', 'person'),
(7, 'psu', '1407f6d84960d9a783ad6409985b38f50c32f3bbbaa2e66c71e3f3234ca5d1a0', '712300c35f6f1b18', 'Pennsylvania State University', 'eauction@psu.edu', '8148651234', 'The Pennsylvania State University is a public land-grant university in Pennsylvania', 'University Park, PA', 'http://www.psu.edu', 'company'),
(8, 'lhmartin', '05d6763da8ed6119eb367d7dd2f93f5ddec7b9a1afa6bf597c8d281cda7060b2', '7e8990d753ccf84e', 'Lockheed Martin', 'eauction@lockheed.com', '9995551234', 'Lockheed Martin is a government contractor', 'Washington, D.C.', 'http://www.lockheed.com/', 'company'),
(9, 'bsclassic', '9fa9573c0558e8b98a9928b2ce5895b8b02d376002e2f68316961615a64d5be3', '38db6e20c9d7d36', 'Blue Sapphire Classic', 'bluesapphireclassic@gmail.com', '9995551235', 'The Blue Sapphire Classic is a charity organization that funds a scholarship for the Feature Twirler of the Penn State Blue Band', 'University Park, PA', 'http://blueband.psu.edu/bsc/', 'company'),
(10, 'shweelz', 'cf085414d11cb6140788a1406db14f18701e23562e66b078cb9cc5a59479f7c4', '12a372be1e03d7eb', 'Shane Besong', 'shweelz@aol.com', '8144445555', '', '', '', 'person');

CREATE TABLE IF NOT EXISTS `won_items` (
  `item_id` int(11) NOT NULL DEFAULT '0',
  `winning_bid` int(11) DEFAULT NULL,
  `date_won` datetime DEFAULT NULL,
  `item_received_date` datetime DEFAULT NULL,
  `item_sent_date` datetime DEFAULT NULL,
  `card_charged_date` datetime DEFAULT NULL,
  `check_mailed_date` datetime DEFAULT NULL,
  `successful_date` datetime DEFAULT NULL,
  `failure_notification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `winning_bid` (`winning_bid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `won_items` (`item_id`, `winning_bid`, `date_won`, `item_received_date`, `item_sent_date`, `card_charged_date`, `check_mailed_date`, `successful_date`, `failure_notification_date`) VALUES
(1, 3, '2014-03-28 05:30:22', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(3, 1, '2014-03-11 11:30:21', '2014-03-13 05:22:00', '2014-03-15 02:23:00', '2014-03-14 10:00:00', '2014-03-14 02:23:00', '2014-03-15 02:30:00', NULL);
