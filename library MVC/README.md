Run the in-built php server with this 

```
php -S localhost:8000 -t public
```

The SQL create table statements.
```
CREATE TABLE users(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,  
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE
);
```

```
CREATE TABLE books(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(50) UNIQUE NOT NULL,
    author VARCHAR(50) NOT NULL,
    isbn VARCHAR(17) UNIQUE NOT NULL,
    publicationDate YEAR NOT NULL,
    genre VARCHAR(50) NOT NULL,
    image VARCHAR(50) NOT NULL,
    description VARCHAR(150) NOT NULL
);
```

```
CREATE TABLE borrowing(
    borrowId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,
    bookId INT NOT NULL,
    bookTitle VARCHAR(50) NOT NULL,
    borrowDate DATE NOT NULL,
    dueDate DATE NOT NULL,
    returnDate DATE NULL,
    fine INT NULL,

    CONSTRAINT fk_userId FOREIGN KEY (userId) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_bookId FOREIGN KEY (bookId) REFERENCES books(id) ON UPDATE CASCADE ON DELETE CASCADE
);
```

```
CREATE TABLE inventory(
    bookId INT NOT NULL PRIMARY KEY,
    totalCopies INT NOT NULL,
    availableCopies INT NOT NULL,

    CONSTRAINT fk_inven_bookId FOREIGN KEY (bookId) REFERENCES books(id) ON UPDATE CASCADE ON DELETE CASCADE
);
```




PSR
Dependency Injection!
APIs
Frameworks
Middlewares
Design Patterns -- Factory!
Backend 