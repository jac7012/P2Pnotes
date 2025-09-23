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

