-- library inventory site database
-- site name: 'www.HWL.com' 
-- database name: 'librarySite'


-- Table structure for table 'userAccount' 
CREATE TABLE `useraccounts` (
`username` varchar(30) NOT NULL,
`email` varchar(30) NOT NULL,
`password` varchar(30) NOT NULL,
`full_Name` varchar(30) NOT NULL,
`phone_Number` varchar(12) NOT NULL,
`items_Out` int(10) NOT NULL,
`items_Requested` int(10) NOT NULL,
`messages` int(10) NOT NULL,
`profile_Photo` text,
PRIMARY KEY (username)
);


-- Dumping data for table 'useraccounts'
INSERT INTO `useraccounts` (`username`, `email`, `password`, `full_Name`, `phone_Number`, `items_Out`, `items_Requested`, `messages`, `profile_Photo`) VALUES
('bperel', 'brianperel@comcast.net', 'Toyota1999', 'Brian Perel', '508-215-7296', '3', '1', '1', '');


-- Table structure for 'itemsout'
CREATE TABLE `itemsout` (
`item_Name` varchar(50),
`item_Account_Holder` varchar(30),
`days_Out` int(14),
`can_Be_Out` int(7), 
`renewed` boolean,
PRIMARY KEY (item_Name)
);


-- Table structure for table 'admin'
CREATE TABLE `admin` (
`username` varchar(30) NOT NULL,
`email` varchar(30) NOT NULL,
`password` varchar(30) NOT NULL,
`fullName` varchar(30) NOT NULL,
`phoneNumber` varchar(12) NOT NULL,
`admin_Profile_Photo` text, 
PRIMARY KEY (username)
);


-- Dumping data for table 'admin'
INSERT INTO `admin` (`username`, `email`, `password`, `fullName`, `phoneNumber`, `admin_Profile_Photo`) VALUES 
('Mr. X', 'mrx@yahoo.com', 'Boss11', 'Tony Rastafar', '456-234-7686', '');


-- Table structure for table 'items'
CREATE TABLE `items` (
`Item_Name` varchar(50) NOT NULL,
`Author` varchar(30),
`ISBN` int(18),
`Publication_Info` varchar(120),
`Year_of_Release` int(4),
`General_Audience` varchar(30), 
`Summary` varchar(120), 
`Item_Type` varchar(30),
`Col_No` varchar(30),
`Status` varchar(30),
`Location` varchar(30), 
`Price` decimal(10, 2),
`photo` text,
PRIMARY KEY(Item_Name)
);


-- Dumping data for table 'items'
INSERT INTO `items` (`Item_Name`, `Author`, `ISBN`, `Publication_Info`, `Year_of_Release`, `General_Audience`, `Summary`, `Item_Type`, `Col_No`, `Status`, `Location`, `Price`, `photo`) VALUES
('The Art of Being a Ninja', 'Brian Perel', 13212124, 'Hollywood, California : Paramount Home Entertainment', '2020', 'adult', 'The Art of Being a Ninja is a documentary film about how to become a ninja', 'Blu-ray', 'J796.815 B', 'Available', 'Framingham State University', '34.50', ''),
('Aikido', 'John Smith', 34534534, 'Hollywood, California : Universal Studios Entertainment', '2010', 'adult', 'Aikido is a movie about the way of aikido practitioners and there lives', 'Movie', 'Z271.154 A', 'Available', 'Framingham State University', '12.80', ''),
('Calisthenics Beasts', 'Jason Armstrong', 567565543, '[Old Saybrook, Ct.] : Tantor Media, Inc.', '2008', 'young-adult', 'Calisthenics Beasts is an ebook broken up into multiple sections by muscle groups', 'ebook', 'D938.121 C', 'Available', 'Framingham State University', '3.12', ''),
('Wild Hunt', 'Jeff Taylor', 234234231, 'The Illustrated Publishers : Stonehill', '2004', 'adult', 'Hear the time-honored stories of a man on the hunt', 'Audio-book', 'R459.232 U', 'Available', 'Framingham State University', '14.50', ''),
('Learn Russian - Русский язык', 'Dmitri Raslov', 678567453, 'The Russian Printing House : Hammerhead', '2017', 'adult', 'Learn to speak and read the Russian language like a pro', 'book', 'Q456.234 R', 'Available', 'Framingham State University', '20.50', ''),
('No Excuses - The Power of self discipline', 'Brian Perel', 789849567, 'Classic Publishers : Ace', '2018', 'young-adult', 'Learn how to be self disciplined in life', 'book', 'A789.567 C', 'Available', 'Framingham State University', '18.75', ''),
('Business Data Networks & Security', 'Julia Smith', 234456454, 'Prentice Hall', '2016', 'adult', 'Book on networking security covering packets, sockets, ip, protocols, network classes, and ftp', 'ebook', 'E657.435 T', 'Available', 'Framingham State University', '120.00', ''),
('Rock Climbing', 'William Backster', 657657342, 'Prentice Hall', '2014', 'young-adult', 'A book about how to rock climb like a pro', 'Audio-book', 'W234.564 P', 'Available', 'Framingham State University', '23.50', ''),
('Rock Climber Pro 2', 'Abraham Grocer', 636845653, 'The Publishing Company', '2017', 'kids', 'A movie about rock climbing for kids', 'Movie', 'Q456.345 S', 'Available', 'Framingham State University', '8.50', '');