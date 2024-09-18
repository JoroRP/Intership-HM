# Book Publishing Application

A Symfony-based web application for managing books, authors, genres, and redactors. This app allows users to create,
view, update, and delete records for books, along with their associated metadata. It also includes a search feature and
filtering functionality.

---

## Features

- **Manage Books**: Add, edit, and delete books with attributes such as title, ISBN, publication year, and genres.
- **Author Management**: Add, edit, and assign authors to books.
- **Genre Management**: Create and manage genres. Assign multiple genres to a book.
- **Redactor Management**: Manage redactors involved in editing the books.
- **Search and Filter**: Search for books by title, and filter by genres and authors.
- **Detailed Book Pages**: View comprehensive details of books, including authors, genres, and publication year.

---

## Installation

Follow these steps to install and set up the application:

1. **Clone the repository**:


2. **Install dependencies**:
    ```bash
    composer install
    ```

3. **Set up the environment configuration**:
   Modify the `.env.local` configuration file and modify it to fit your local setup.

   Update the database connection information:
    ```dotenv
    DATABASE_URL="mysql://username:password@127.0.0.1:3306/book_publishing?serverVersion=5.7"
    ```

4. **Run migrations**:
   Create the database and tables:
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

5. **(Optional) Load test data**:
   Load some sample data into the application using Doctrine fixtures:
    ```bash
    php bin/console doctrine:fixtures:load
    ```

---

## Running the Application

You can run the application using the Symfony CLI or a web server:

```bash
symfony serve
