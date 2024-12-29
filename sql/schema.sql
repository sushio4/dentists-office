CREATE TABLE IF NOT EXISTS Patients (
	PatientID INT,
	FirstName VARCHAR(50),
	LastName VARCHAR(50),
	DateOfBirth DATE,
	Phone VARCHAR(15),
	Email VARCHAR(100) UNIQUE,
	Address TEXT,
	Pass VARCHAR(50),
	PRIMARY KEY (PatientID)
);

CREATE TABLE IF NOT EXISTS Staff (
	StaffID INT,
	FirstName VARCHAR(50),
	LastName VARCHAR(50),
	Phone VARCHAR(15),
	Email VARCHAR(100),
	PRIMARY KEY (StaffID)
);

CREATE TABLE IF NOT EXISTS Services (
	ServiceID INT,
	ServiceName VARCHAR(100),
	Description TEXT,
	DurationHours INT,
	Price DECIMAL(10,2),
	PRIMARY KEY (ServiceID)
);

CREATE TABLE IF NOT EXISTS Appointments (
	AppointmentID INT,
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