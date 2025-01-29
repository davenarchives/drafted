# Drafted: A Message Sharing Platform

## Table of Contents
1. [Introduction](#introduction)
2. [Features](#features)
3. [Technologies Used](#technologies-used)
4. [Project Structure](#project-structure)
5. [Installation](#installation)
6. [Configuration](#configuration)
7. [Usage](#usage)
8. [Hosting on InfinityFree](#hosting-on-infinityfree)
9. [Contributing](#contributing)
10. [License](#license)

## Introduction

Drafted is a web-based platform designed to let users anonymously share heartfelt messages, regrets, or unspoken thoughts. Each message can be accompanied by music links to enhance emotional impact. The platform allows users to submit and browse messages.

## Features

- Anonymous message submission
- Browse and search through shared messages
- Attach Spotify links to messages (automatically embedded)
- Responsive design for various screen sizes
- Pop-up view for reading full messages

## Technologies Used

- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- JavaScript (ES6+)

## Project Structure

```
drafted/
├── index.php          # Main entry point
├── header.php         # Common header
├── footer.php         # Common footer
├── home.php           # Home page content
├── browse.php         # Browse messages page
├── submit.php         # Submit new message page
├── process.php        # Form processing
├── db.php             # Database connection
├── functions.php      # Helper functions
├── styles.css         # Main stylesheet
└── script.js          # JavaScript functionality
```

This structure provides a clear overview of the project files and their purposes:

- `index.php`: The main entry point of the application
- `header.php` and `footer.php`: Common elements included in all pages
- `home.php`, `browse.php`, `submit.php`: Content for specific pages
- `process.php`: Handles form submissions and data processing
- `db.php`: Manages database connections
- `functions.php`: Contains reusable PHP functions
- `styles.css`: Defines the visual styling of the application
- `script.js`: Implements client-side interactivity and functionality

## Installation

1. Clone the repository: git clone [https://github.com/yourusername/drafted.git](https://github.com/yourusername/drafted.git)
2. Upload the files to your web server.
3. Create a MySQL database and import the `database.sql` file (you'll need to create this based on the structure we've discussed).
4. Update the `db.php` file with your database credentials.

## Configuration

1. Open `db.php` and update the database connection details:
```php
$host = 'your_host';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';
```

## Usage

1. **Home Page**: 
   - Visit the main page to see a welcome message and example messages.
   - Use the navigation menu to access other sections of the site.

2. **Browse Messages**:
   - Click on "Browse" in the navigation menu.
   - View all submitted messages in a grid layout.
   - Use the search bar at the top to filter messages by recipient.
   - Click on any message to view its full content in a popup.

3. **Submit a Message**:
   - Click on "Submit" in the navigation menu.
   - Fill out the form with the following information:
     - Recipient: Who the message is for.
     - Message: Your anonymous message.
     - Music Link (optional): A Spotify track URL.
   - Click "Submit" to post your message.

4. **Interacting with Messages**:
   - Click on a message to view its full content.
   - If a Spotify link is attached, you can play the song directly in the message.
   - Close the popup by clicking the "Close" button or clicking outside the popup.

## Hosting on InfinityFree

InfinityFree is a free web hosting service that supports PHP and MySQL. Here's how to host the project on InfinityFree:

1. Sign up for an account at [InfinityFree](https://infinityfree.net/).
2. After signing in, go to the control panel and create a new account.
3. In the control panel, find the "MySQL Databases" section and create a new database. Note down the database name, username, and password.
4. Use an FTP client (like FileZilla) or the file manager in the control panel to upload your Drafted files to the `htdocs` directory of your InfinityFree account.
5. Update the `db.php` file with the MySQL database details you noted in step 3.
6. Find the "PHP Settings" in the control panel and ensure that PHP version 7.4 or higher is selected.
7. Your site should now be live at the URL provided by InfinityFree (usually something like `your-site-name.infinityfreeapp.com`).

### Important Notes for InfinityFree Hosting:

- InfinityFree has a resource usage policy. Make sure your application doesn't exceed these limits.
- There's no SSH access, so any database changes need to be done through phpMyAdmin or similar tools provided in the control panel.
- Free subdomains are provided, but you can also use a custom domain if you have one.
- Ensure all file permissions are set correctly (usually 644 for files and 755 for directories).

## Contributing

Contributions to Drafted are welcome! Here's how you can contribute:

1. Fork the repository.
2. Create a new branch for your feature (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

Please ensure your code adheres to the existing style and that you've tested your changes thoroughly.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

MIT License

Copyright (c) 2025 Drafted. All rights reserved.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
