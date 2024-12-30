CREATE TABLE IF NOT EXISTS Patients (
	PatientID INT NOT NULL AUTO_INCREMENT,
	FirstName VARCHAR(50),
	LastName VARCHAR(50),
	DateOfBirth DATE,
	Phone VARCHAR(15),
	Email VARCHAR(100) UNIQUE,
	Address TEXT,
	Pass VARCHAR(255),
	PRIMARY KEY (PatientID)
);

CREATE TABLE IF NOT EXISTS Staff (
	StaffID INT NOT NULL AUTO_INCREMENT,
	FirstName VARCHAR(50),
	LastName VARCHAR(50),
	Phone VARCHAR(15),
	Email VARCHAR(100),
	PRIMARY KEY (StaffID)
);

CREATE TABLE IF NOT EXISTS Services (
	ServiceID INT NOT NULL AUTO_INCREMENT,
	ServiceName VARCHAR(100),
	Description TEXT,
	DurationHalfHours INT,
	Price DECIMAL(10,2),
	PRIMARY KEY (ServiceID)
);

CREATE TABLE IF NOT EXISTS Appointments (
	AppointmentID INT NOT NULL AUTO_INCREMENT,
	PatientID INT,
	StaffID INT,
	ServiceID INT,
	AppointmentDate DATETIME,
	AppointmentStatus ENUM ("Done", "In Progress", "Pending"),
	PRIMARY KEY (AppointmentID),
	FOREIGN KEY (PatientID) REFERENCES Patients (PatientID),
	FOREIGN KEY (StaffID) REFERENCES Staff (StaffID),
	FOREIGN KEY (ServiceID) REFERENCES Services (ServiceID)
);

INSERT INTO Staff (FirstName, LastName, Phone, Email) VALUES
("Jan", "Kowalski", "123123123", "jankowalski@zebex.pl"),
("Monika", "Nowak", "321321321", "monikanowak@zebex.pl"),
("Janusz", "Cebulski", "123456789", "januszcebulski@zebex.pl"),
("Snoop", "Dogg", "420420420", "snoopdogg@zebex.pl");

INSERT INTO Services (ServiceName, Description, DurationHalfHours, Price) VALUES
("Kontrola", "Podstawowe badania dentystyczne", 1, 100),
("Wyrywanie (1-2)", "Wyrwanie jednego lub dwoch zebow", 1 ,500),
("Wyrywanie (3-5)", "Wyrwanie od trzech do pieciu zebow", 2, 1000),
("Wyrywanie (6-10)", "Wyrwanie od szesciu do dziesieciu zebow", 3, 2000),
("Wyrywanie (11+)", "Wyrwanie 11 lub wiecej zebow", 4, 5000),
("Kanalowe (1kan)", "Leczenie kanalowe jednokanalowego zeba", 2, 600),
("Kanalowe (2kan)", "Leczenie kanalowe dwukanalowego zeba", 3, 800),
("Kanalowe (3-4kan)", "Leczenie kanalowe 3 i 4 kanalowego zeba", 4, 1200),
("Plombowanie", "Plombowanie jednego zeba metoda xyz", 2, 600);
