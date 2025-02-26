Run the in-built php server with this 

```
php -S localhost:8000 -t public/index.php
mysql -u root -p --protocol=TCP
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

Action | Endpoint
-| -
Sign Up | /user/{user_id} POST
Login   | /user/{user_id} GET
Edit Profile | /user/{user_id} PATCH
Upload Profile Image | /user/{user_id} PATCH
View All Books | /book GET
View A Book Detail | /book/{book_id} GET
Add Book | /book/{book_id} POST
Edit Book Details | /book/{book_id} PATCH
Upload Book Image | /book/{book_id} PATCH
Borrow A Book | /user/{user_id}/book/{book_id}/borrow POST
Return A Book | /user/{user_id}/book/{book_id}/return POST
Delete A Book | /book/{book_id} DELETE
Delete A User | /user/{user_id} DELETE
Change User Role | /user/{user_id}/role PATCH   
See Borrowed History |/user/{user_id}/book GET