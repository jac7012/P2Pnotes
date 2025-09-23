-- User TABLE and DATA
CREATE TABLE `User` (
  `userID` varchar(10) PRIMARY KEY,
  `role` enum('student','lecturer','admin') NOT NULL
);

INSERT INTO `User` (`userID`, `role`) VALUES
('A000001', 'admin'),
('L000001', 'lecturer'),
('L000002', 'lecturer'),
('S000001', 'student'),
('S000002', 'student'),
('S000003', 'student');


-- Student TABLE and DATA
CREATE TABLE `Student` (
  `studentID` varchar(10) PRIMARY KEY,
  `userID` varchar(10) NOT NULL,
  `studentFirstName` varchar(50) NOT NULL,
  `studentLastName` varchar(50) NOT NULL,
  `studentDOB` date NOT NULL,
  `studentPassword` varchar(255) NOT NULL,
  `studentEmail` varchar(100) NOT NULL,
  `studentHP` varchar(11) NOT NULL,
  `studentType` enum('standard','premium') NOT NULL,
  `studentStatus` enum('active','suspended') NOT NULL,
  `creditBalance` int NOT NULL,
  `downloadLimit` int NOT NULL,
  FOREIGN KEY (userID) REFERENCES User(userID)
);

INSERT INTO `Student` (`studentID`, `userID`, `studentFirstName`, `studentLastName`, `studentDOB`, `studentPassword`, 
`studentEmail`, `studentHP`, `studentType`, `studentStatus`, `creditBalance`, `downloadLimit`) VALUES
('S000001', 'S000001', 'Hui', 'Ang', '2003-03-10', 'qwer1234',
'fiona@student.mmu.edu.my', '0123456789', 'standard', 'active', '21', '3'),
('S000002', 'S000002', 'Yee', 'Woo', '2004-04-17', 'qwer1234',
'fiona@student.mmu.edu.my', '0193456789', 'standard', 'active', '22', '3'),
('S000003', 'S000003', 'Jean', 'Soo', '2007-05-20', 'qwer1234',
'jean@student.mmu.edu.my', '0183456789', 'premium', 'active', '18', '5');


-- Lecturer TABLE and DATA
CREATE TABLE `Lecturer` (
  `lecturerID` varchar(10) PRIMARY KEY,
  `userID` varchar(10) NOT NULL,
  `lecturerFirstName` varchar(50) NOT NULL,
  `lecturerLastName` varchar(50) NOT NULL,
  `lecturerDOB` date NOT NULL,
  `lecturerPassword` varchar(255) NOT NULL,
  `lecturerEmail` varchar(100) NOT NULL,
  `lecturerHP` varchar(11) NOT NULL,
  `lecturerStatus` enum('active','suspended') NOT NULL,
  FOREIGN KEY (userID) REFERENCES User(userID)
);

INSERT INTO `Lecturer` (`lecturerID`, `userID`, `lecturerFirstName`, `lecturerLastName`, `lecturerDOB`, `lecturerPassword`, 
`lecturerEmail`, `lecturerHP`, `lecturerStatus`) VALUES
('L000001', 'L000001', 'Qing', 'Loo', '1992-08-31', 'qwerasdf',
'loo@mmu.edu.my', '0113456789', 'active'),
('L000002', 'L000002', 'Nin', 'Lam', '1993-01-20', 'qwerzxcv',
'lam@mmu.edu.my', '0133456789', 'active');


-- Admin TABLE and DATA
CREATE TABLE `Admin` (
  `adminID` varchar(10) PRIMARY KEY,
  `userID` varchar(10) NOT NULL,
  `adminFirstName` varchar(50) NOT NULL,
  `adminLastName` varchar(50) NOT NULL,
  `adminDOB` date NOT NULL,
  `adminPassword` varchar(255) NOT NULL,
  `adminEmail` varchar(100) NOT NULL,
  `adminHP` varchar(11) NOT NULL,
  FOREIGN KEY (userID) REFERENCES User(userID)
);

INSERT INTO `Admin` (`adminID`, `userID`, `adminFirstName`, `adminLastName`, `adminDOB`, `adminPassword`, 
`adminEmail`, `adminHP`) VALUES
('A000001', 'A000001', 'Richard', 'Wong', '1998-12-02', 'qwer1234',
'richard@mmu.edu.my', '0103456789');


-- Note TABLE and DATA
CREATE TABLE `Note` (
  `noteID` varchar(10) PRIMARY KEY,
  `noteTitle` varchar(255) NOT NULL,
  `noteDesc` text NOT NULL,
  `noteFile` longblob DEFAULT NULL,
  `noteUploaderID` varchar(10) NOT NULL,
  `noteTag` varchar(50) NOT NULL,
  `noteVerifiedStatus` enum('pending','approved','rejected') NOT NULL,
  FOREIGN KEY (noteUploaderID) REFERENCES User(userID)
);

INSERT INTO `Note` (`noteID`, `noteTitle`, `noteDesc`, `noteFile`, `noteUploaderID`, `noteTag`, `noteVerifiedStatus`) VALUES
('N000001', 'CS Students must know', 'This note is based on my 1 years experience, hope it may help!', '', 'S000001', 'Computer Science', 'approved'),
('N000002', 'IT Students must know', 'This note is based on my 2 years experience, hope it may help!', '', 'S000002', 'Information Technology', 'approved'),
('N000003', 'SE Students must know', 'This note is based on my 3 years experience, hope it may help!', '', 'S000003', 'Software Engineering', 'approved'),
('N000004', 'DATA SCIENCE Students must know', 'This note is based on my 4 years experience, hope it may help!', '', 'S000001', 'Data Science', 'rejected');

-- Exchange TABLE and DATA
CREATE TABLE `Exchange` (
    `exchangeID` varchar(10) PRIMARY KEY,
    `requesterID` varchar(10) NOT NULL,
    `receiverID` varchar(10) NOT NULL, 
    `offeredNoteID` VARCHAR(10) NOT NULL,     -- e.g. 'N000123'
    `requestedNoteID` VARCHAR(10) NOT NULL,   -- e.g. 'N000456'
    FOREIGN KEY (requesterID) REFERENCES User(userID),
    FOREIGN KEY (receiverID) REFERENCES User(userID),
    FOREIGN KEY (offeredNoteID) REFERENCES Note(noteID),
    FOREIGN KEY (requestedNoteID) REFERENCES Note(noteID)
);

INSERT INTO `Exchange` (`exchangeID`, `requesterID`, `receiverID`, `offeredNoteID`, `requestedNoteID`) VALUES 
('E000001', 'S000001', 'S000002', 'N000001', 'N000002'),
('E000002', 'S000002', 'S000001', 'N000002', 'N000003');


-- Leaderboard TABLE and DATA
CREATE TABLE `Leaderboard` (
    `leaderboardID` VARCHAR(10) PRIMARY KEY,  
    `userID` VARCHAR(10) NOT NULL,           
    `totalUploads` INT DEFAULT 0,
    `score` INT DEFAULT 0,
    FOREIGN KEY (userID) REFERENCES User(userID)
);

INSERT INTO `Leaderboard` (`leaderboardID`, `userID`, `totalUploads`, `score`) VALUES 
('LB000001', 'S000001', 15, 120),
('LB000002', 'S000002', 5, 60);


-- Report TABLE and DATA
CREATE TABLE `Report` (
    `reportID` VARCHAR(10) PRIMARY KEY,
    `noteID` VARCHAR(10) NOT NULL, 
    `reportStatus` ENUM('reviewing', 'removed', 'resolved') DEFAULT 'reviewing',
    FOREIGN KEY (noteID) REFERENCES Note(noteID)
);

INSERT INTO `Report` (`reportID`, `noteID`, `reportStatus`) VALUES 
('RP000001', 'N000001', 'reviewing'),
('RP000002', 'N000003', 'removed');


-- Review TABLE and DATA
CREATE TABLE `Review` (
    `reviewID` VARCHAR(10) PRIMARY KEY,       
    `noteID` VARCHAR(10) NOT NULL,            
    `reviewStatus` ENUM('pending', 'approved', 'declined') DEFAULT 'pending',
    FOREIGN KEY (noteID) REFERENCES Note(noteID)
);

INSERT INTO `Review` (reviewID, noteID, reviewStatus) VALUES 
('RV000001', 'N000001', 'pending'),
('RV000002', 'N000002', 'approved'),
('RV000003', 'N000003', 'declined');


-- Rating TABLE and DATA
CREATE TABLE `Rating` (
    `ratingID` VARCHAR(10) PRIMARY KEY,          
    `noteID` VARCHAR(10) NOT NULL,              
    `rating` TINYINT CHECK (rating BETWEEN 1 AND 5),
    `ratingDate` DATE NOT NULL,
    `averageRating` FLOAT DEFAULT 0,
    FOREIGN KEY (noteID) REFERENCES Note(noteID)
);

INSERT INTO `Rating` (`ratingID`, `noteID`, `rating`, `ratingDate`, `averageRating`) VALUES 
('RT000001', 'N000001', 5, '2025-09-18', 4.8),
('RT000002', 'N000002', 4, '2025-09-18', 4.6);


-- averageRating calculation:
SELECT AVG(rating) AS avgRating
FROM rating
WHERE noteID = 'N000001';


-- Feedback TABLE and DATA
CREATE TABLE `Feedback` (
    `feedbackID` VARCHAR(10) PRIMARY KEY,          -- e.g. 'F000001'
    `noteID` VARCHAR(10) NOT NULL,                 -- e.g. 'N000123'
    `comment` VARCHAR(500) NOT NULL,               -- Feedback text
    `feedbackDate` DATETIME NOT NULL,              -- YYYY-MM-DD HH:mm:ss
    FOREIGN KEY (noteID) REFERENCES Note(noteID)
);

INSERT INTO `Feedback` (`feedbackID`, `noteID`, `comment`, `feedbackDate`) VALUES 
('F000001', 'N000001', 'Very detailed notes, easy to follow!', '2025-09-18 14:35:00'),
('F000002', 'N000002', 'Please add more examples for Chapter 3.', '2025-09-18 15:10:00');


-- Credit TABLE and DATA
CREATE TABLE `Credit` (
    `creditID` VARCHAR(10) PRIMARY KEY,
    `userID` VARCHAR(10) NOT NULL,
    `transBalance` INT NOT NULL,      
    `transactionType` ENUM('earned', 'spent') NOT NULL, 
    `transactionDate` DATE NOT NULL,
    FOREIGN KEY (userID) REFERENCES User(userID)
);

INSERT INTO `Credit` (`creditID`, `userID`, `transBalance`, `transactionType`, `transactionDate`) VALUES
('C000001', 'S000001', 100, 'earned', '2025-09-01'),
('C000002', 'S000001', 80,  'spent',  '2025-09-03'),
('C000003', 'S000002', 150, 'earned', '2025-09-05'),
('C000004', 'S000002' , 120, 'spent',  '2025-09-08'),
('C000005', 'S000003', 200, 'earned', '2025-09-10');




