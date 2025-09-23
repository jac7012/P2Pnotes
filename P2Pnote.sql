-- User TABLE and DATA
CREATE TABLE `User` (
  `userID` varchar(20) PRIMARY KEY,
  `role` enum('student','lecturer','admin') NOT NULL
);

INSERT INTO `user` (`userID`, `role`) VALUES
('A000001', 'admin'),
('L000001', 'lecturer'),
('L000002', 'lecturer'),
('S000001', 'student');


-- Student TABLE and DATA
CREATE TABLE `Student` (
  `studentID` varchar(10) PRIMARY KEY,
  `userID` varchar(20) NOT NULL,
  `studentFirstName` varchar(50) NOT NULL,
  `studentLastName` varchar(50) NOT NULL,
  `studentDOB` date NOT NULL,
  `studentPassword` varchar(255) NOT NULL,
  `studentEmail` varchar(100) NOT NULL,
  `studentHP` varchar(11) NOT NULL,
  `studentType` enum('standard','premium') NOT NULL,
  `studentStatus` enum('active','suspended') NOT NULL,
  `creditBalance` int(5) NOT NULL,
  `downloadLimit` int(1) NOT NULL,
  FOREIGN KEY (userID) REFERENCES User(userID)
);

INSERT INTO `Student` (`studentID`, `userID`, `studentFirstName`, `studentLastName`, `studentDOB`, `studentPassword`, 
`studentEmail`, `studentHP`, `studentType`, `studentStatus`, `creditBalance`, `downloadLimit`) VALUES
('S000001', 'S000001', 'Hui', 'Ang', '2003-03-10', 'qwer1234',
'fiona@student.mmu.edu.my', '0123456789', 'standard', 'active', '22', '3');


-- Lecturer TABLE and DATA
CREATE TABLE `Lecturer` (
  `lecturerID` varchar(10) PRIMARY KEY,
  `userID` varchar(20) NOT NULL,
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
  `userID` varchar(20) NOT NULL,
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
  `noteUploader` varchar NOT NULL,
  `noteTag` varchar(50) NOT NULL,
  `noteStatus` enum('pending','approved','admin') NOT NULL
  `noteValidationResult` enum('student','lecturer','admin') NOT NULL
  FOREIGN KEY (noteUploaderID) REFERENCES User(userID),
);

--exchange TABLE and DATA
CREATE TABLE exchange (
    exchangeID INT AUTO_INCREMENT PRIMARY KEY,
    offeredNoteID VARCHAR(10) NOT NULL,     -- e.g. 'N000123'
    requestedNoteID VARCHAR(10) NOT NULL,   -- e.g. 'N000456'
    FOREIGN KEY (offeredNoteID) REFERENCES notes(noteID),
    FOREIGN KEY (requestedNoteID) REFERENCES notes(noteID)
);
INSERT INTO exchange (exchangeID, offeredNoteID, requestedNoteID)
VALUES ('E000001', 'N000123', 'N000456');

INSERT INTO exchange (exchangeID, offeredNoteID, requestedNoteID)
VALUES ('E000002', 'N000789', 'N000321');

-- leaderboard TABLE and DATA
CREATE TABLE leaderboard (
    leaderboardID VARCHAR(10) PRIMARY KEY,  
    userID VARCHAR(10) NOT NULL,           
    totalUploads INT DEFAULT 0,
    score INT DEFAULT 0,
    FOREIGN KEY (userID) REFERENCES users(userID)
);


INSERT INTO leaderboard (leaderboardID, userID, totalUploads, score)
VALUES ('LB000001', 'S000001', 15, 120);

INSERT INTO leaderboard (leaderboardID, userID, totalUploads, score)
VALUES ('LB000002', 'S000002', 5, 60);

-- report TABLE and DATA
CREATE TABLE report (
    reportID INT AUTO_INCREMENT PRIMARY KEY,
    noteID VARCHAR(10) NOT NULL, 
    reportStatus ENUM('reviewing', 'removed', 'resolved') DEFAULT 'reviewing',
    FOREIGN KEY (noteID) REFERENCES notes(noteID)
);

INSERT INTO report (reportID, noteID, reportStatus)
VALUES ('RP000001', 'N000045', 'reviewing');

INSERT INTO report (reportID, noteID, reportStatus)
VALUES ('RP000002', 'N000046', 'removed');

--review TABLE and DATA
CREATE TABLE review (
    reviewID VARCHAR(10) PRIMARY KEY,       
    noteID VARCHAR(10) NOT NULL,            
    reviewStatus ENUM('pending', 'approved', 'declined') DEFAULT 'pending',
    FOREIGN KEY (noteID) REFERENCES notes(noteID)
);

INSERT INTO review (reviewID, noteID, reviewStatus)
VALUES ('RV000001', 'N000045', 'pending');

INSERT INTO review (reviewID, noteID, reviewStatus)
VALUES ('RV000002', 'N000046', 'approved');

INSERT INTO review (reviewID, noteID, reviewStatus)
VALUES ('RV000003', 'N000047', 'declined');

--rating TABLE and DATA
CREATE TABLE rating (
    ratingID VARCHAR(10) PRIMARY KEY,          
    noteID VARCHAR(10) NOT NULL,              
    rating TINYINT CHECK (rating BETWEEN 1 AND 5),
    ratingDate DATE NOT NULL,
    averageRating FLOAT DEFAULT 0,
    FOREIGN KEY (noteID) REFERENCES notes(noteID)
);

INSERT INTO rating (ratingID, noteID, rating, ratingDate, averageRating)
VALUES ('RT000001', 'N000123', 5, '2025-09-18', 4.8);

INSERT INTO rating (ratingID, noteID, rating, ratingDate, averageRating)
VALUES ('RT000002', 'N000123', 4, '2025-09-18', 4.6);

--averageRating calculation:
SELECT AVG(rating) AS avgRating
FROM rating
WHERE noteID = 'N000123';

--feedback TABLE and DATA
CREATE TABLE feedback (
    feedbackID VARCHAR(10) PRIMARY KEY,          -- e.g. 'F000001'
    noteID VARCHAR(10) NOT NULL,                 -- e.g. 'N000123'
    comment VARCHAR(500) NOT NULL,               -- Feedback text
    feedbackDate DATETIME NOT NULL,              -- YYYY-MM-DD HH:mm:ss
    FOREIGN KEY (noteID) REFERENCES notes(noteID)
);
INSERT INTO feedback (feedbackID, noteID, comment, feedbackDate)
VALUES ('F000001', 'N000123', 'Very detailed notes, easy to follow!', '2025-09-18 14:35:00');

INSERT INTO feedback (feedbackID, noteID, comment, feedbackDate)
VALUES ('F000002', 'N000123', 'Please add more examples for Chapter 3.', '2025-09-18 15:10:00');

--credit TABLE and DATA
CREATE TABLE Credit (
    creditID VARCHAR(10) PRIMARY KEY,
    transBalance INT NOT NULL,      
    transactionType ENUM('earned', 'spent') NOT NULL, 
    transactionDate DATE NOT NULL   
);
-- Insert sample credit transactions
INSERT INTO Credit (creditID, transBalance, transactionType, transactionDate) VALUES
('C000001', 100, 'earned', '2025-09-01'),
('C000002', 80,  'spent',  '2025-09-03'),
('C000003', 150, 'earned', '2025-09-05'),
('C000004', 120, 'spent',  '2025-09-08'),
('C000005', 200, 'earned', '2025-09-10');





