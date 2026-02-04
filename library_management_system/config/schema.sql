-- Create Database
CREATE DATABASE IF NOT EXISTS lmms;
USE lmms;

-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    emri VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    confirm_password VARCHAR(255),
    is_admin INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Books Table
CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    published_year INT,
    image VARCHAR(255),
    description TEXT,
    quantity INT DEFAULT 1,
    available INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Reservations Table
CREATE TABLE IF NOT EXISTS reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Admin User
INSERT INTO users (emri, username, email, password, is_admin) 
VALUES ('Admin User', 'admin', 'admin@library.com', '$2y$10$YIjlrPnoJ.ZVlnPu6KVkp.LhfCKKmU5Pm6p10iRsVxe8Uyq5oC7fG', 1);

-- Insert 40 Sample Books
INSERT INTO books (title, author, category, published_year, image, description, quantity, available) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'Classic', 1925, 'the_great_gatsby.jpg', 'A classic American novel set in the Jazz Age', 5, 3),
('To Kill a Mockingbird', 'Harper Lee', 'Classic', 1960, 'to_kill_a_mockingbird.jpg', 'A gripping tale of racial injustice and childhood innocence', 4, 2),
('1984', 'George Orwell', 'Dystopian', 1949, '1984.jpg', 'A dystopian novel about totalitarianism', 6, 4),
('Pride and Prejudice', 'Jane Austen', 'Romance', 1813, 'pride_and_prejudice.jpg', 'A tale of love and social class', 5, 3),
('The Catcher in the Rye', 'J.D. Salinger', 'Classic', 1951, 'the_catcher_in_the_rye.jpg', 'Coming-of-age story of Holden Caulfield', 3, 2),
('Jane Eyre', 'Charlotte Brontë', 'Romance', 1847, 'jane_eyre.jpg', 'Gothic romance with strong female protagonist', 4, 2),
('Wuthering Heights', 'Emily Brontë', 'Romance', 1847, 'wuthering_heights.jpg', 'Intense love story set in Yorkshire', 3, 1),
('The Hobbit', 'J.R.R. Tolkien', 'Fantasy', 1937, 'the_hobbit.jpg', 'Adventure in Middle-earth', 7, 5),
('The Lord of the Rings', 'J.R.R. Tolkien', 'Fantasy', 1954, 'lord_of_the_rings.jpg', 'Epic fantasy trilogy', 8, 6),
('Harry Potter and the Philosopher\'s Stone', 'J.K. Rowling', 'Fantasy', 1997, 'harry_potter_1.jpg', 'Young wizard\'s first year at Hogwarts', 10, 8),
('Harry Potter and the Chamber of Secrets', 'J.K. Rowling', 'Fantasy', 1998, 'harry_potter_2.jpg', 'Harry\'s second year at Hogwarts', 9, 7),
('Harry Potter and the Prisoner of Azkaban', 'J.K. Rowling', 'Fantasy', 1999, 'harry_potter_3.jpg', 'Escape and mystery', 8, 6),
('The Chronicles of Narnia', 'C.S. Lewis', 'Fantasy', 1950, 'narnia.jpg', 'Children adventures in a magical world', 6, 4),
('Moby Dick', 'Herman Melville', 'Adventure', 1851, 'moby_dick.jpg', 'Epic tale of whale hunting', 4, 2),
('Treasure Island', 'Robert Louis Stevenson', 'Adventure', 1881, 'treasure_island.jpg', 'Pirate adventure and treasure hunting', 5, 3),
('The Adventures of Sherlock Holmes', 'Arthur Conan Doyle', 'Mystery', 1892, 'sherlock_holmes.jpg', 'Detective mysteries and investigations', 6, 4),
('The Picture of Dorian Gray', 'Oscar Wilde', 'Classic', 1890, 'dorian_gray.jpg', 'Gothic tale of beauty and corruption', 3, 1),
('Frankenstein', 'Mary Shelley', 'Horror', 1818, 'frankenstein.jpg', 'Classic horror story', 4, 2),
('Dracula', 'Bram Stoker', 'Horror', 1897, 'dracula.jpg', 'Gothic vampire novel', 5, 3),
('The Invisible Man', 'H.G. Wells', 'Science Fiction', 1897, 'invisible_man.jpg', 'Science fiction classic', 4, 2),
('The Time Machine', 'H.G. Wells', 'Science Fiction', 1895, 'time_machine.jpg', 'Early science fiction tale', 5, 3),
('A Tale of Two Cities', 'Charles Dickens', 'Classic', 1859, 'tale_of_two_cities.jpg', 'French Revolution drama', 6, 4),
('Oliver Twist', 'Charles Dickens', 'Classic', 1838, 'oliver_twist.jpg', 'Story of an orphan boy', 5, 3),
('Great Expectations', 'Charles Dickens', 'Classic', 1860, 'great_expectations.jpg', 'Coming-of-age in Victorian England', 4, 2),
('The Odyssey', 'Homer', 'Classic', -800, 'the_odyssey.jpg', 'Ancient Greek epic poem', 3, 1),
('The Iliad', 'Homer', 'Classic', -750, 'the_iliad.jpg', 'Trojan War epic', 3, 1),
('Dune', 'Frank Herbert', 'Science Fiction', 1965, 'dune.jpg', 'Epic sci-fi on desert planet', 7, 5),
('Foundation', 'Isaac Asimov', 'Science Fiction', 1951, 'foundation.jpg', 'Galactic empire foundation', 5, 3),
('Brave New World', 'Aldous Huxley', 'Dystopian', 1932, 'brave_new_world.jpg', 'Dystopian future society', 6, 4),
('Fahrenheit 451', 'Ray Bradbury', 'Dystopian', 1953, 'fahrenheit_451.jpg', 'Book burning future', 5, 3),
('The Handmaid\'s Tale', 'Margaret Atwood', 'Dystopian', 1985, 'handmaids_tale.jpg', 'Totalitarian regime story', 6, 4),
('Animal Farm', 'George Orwell', 'Allegory', 1945, 'animal_farm.jpg', 'Political allegory with farm animals', 7, 5),
('Slaughterhouse-Five', 'Kurt Vonnegut', 'Science Fiction', 1969, 'slaughterhouse_five.jpg', 'Anti-war science fiction', 4, 2),
('The Odyssey of Homer', 'Homer', 'Classic', -800, 'odyssey_homer.jpg', 'Journey home after Troy', 3, 1),
('Sense and Sensibility', 'Jane Austen', 'Romance', 1811, 'sense_sensibility.jpg', 'Sisters and romance', 4, 2),
('Emma', 'Jane Austen', 'Romance', 1815, 'emma.jpg', 'Matchmaking and romance', 5, 3),
('The Scarlet Letter', 'Nathaniel Hawthorne', 'Classic', 1850, 'scarlet_letter.jpg', 'Puritan guilt and redemption', 4, 2),
('Huckleberry Finn', 'Mark Twain', 'Adventure', 1884, 'huckleberry_finn.jpg', 'Boy\'s adventures on the Mississippi', 6, 4),
('Alice in Wonderland', 'Lewis Carroll', 'Fantasy', 1865, 'alice_wonderland.jpg', 'Girl\'s surreal adventures', 8, 6),
('The Jungle Book', 'Rudyard Kipling', 'Adventure', 1894, 'jungle_book.jpg', 'Boy raised by animals in jungle', 5, 3);
