
	
		
	CREATE TABLE persons (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
	password varchar(256) NOT NULL
);


CREATE TABLE trips (
    id SERIAL PRIMARY KEY,
    person_id INT NOT NULL,
    destination VARCHAR(100) NOT NULL,
    departure VARCHAR(100) NOT NULL,
    start_date VARCHAR(30) NOT NULL,
    end_date VARCHAR(30) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE
	-- we can already mention the cascade, 
	-- condorming to CNIL standards for data privacy protectionn
);



-- MOCK DATA

INSERT INTO persons (name, email, password) VALUES
('John Doe', 'john.doe@example.com','apples'),
('Jane Smith', 'jane.smith@example.com','dxbfjshbfigbkdj'),
('Alice Johnson', 'alice.johnson@example.com','dksbgkjhqbsikf');


INSERT INTO trips (person_id, destination,departure, start_date, end_date) VALUES
(1, 'Paris','Downtown', '2023-05-01', '2023-05-10'),
(1, 'New York', 'Canada','2023-06-15', '2023-06-20'),
(2, 'Tokyo', 'Ohio','2023-07-01', '2023-07-10'),
(3, 'London','Neverland', '2023-08-05', '2023-08-15');