# WebApp with Login, Registration, and Post Management

## Project Overview
This is a web application that allows users to register, log in, create posts, delete posts, and view their own posts. The project also demonstrates common web security vulnerabilities such as Cross-Site Scripting (XSS) and SQL Injection for educational purposes.

The application uses a SQL database imported from the provided `sepehr_mahan.sql` file.

## Features
- User registration and login system
- Create, delete, and display posts tied to individual users
- Demonstration of XSS and SQL Injection vulnerabilities
- Educational insights into securing web applications against these attacks

## Getting Started

### Prerequisites
- Node.js and npm installed
- A SQL database system (e.g., MySQL, SQLite) to import the `sepehr_mahan.sql` file

### Installation

1. Clone the repository:

2. Import the database:
   Import the `sepehr_mahan.sql` file into your SQL database. For example, using MySQL:

3. Install dependencies:

4. Configure your database connection in the application (update config files as needed).

5. Start the application:

6. Open your browser and navigate to `http://localhost:8000` (or your configured port).

## Usage

- Register a new user account.
- Log in with your credentials.
- Create new posts, view your posts, and delete posts.
- Experiment with input fields to observe XSS and SQL Injection vulnerabilities (for learning purposes only).

## Security Notes
This project intentionally includes vulnerabilities to demonstrate how XSS and SQL Injection attacks work. Do **not** use this code in production environments without applying proper security measures such as parameterized queries, input validation, and output escaping.

## Contributing
Contributions are welcome! Please open an issue or submit a pull request for improvements or fixes.

## License
This project is licensed under the MIT License.

## Credits
- Developed by [Porya Rohizade](https://github.com/Porya1376)
- Inspired by security learning resources on XSS and SQL Injection

## Last Updated
This README was last updated on June 10, 2025.
