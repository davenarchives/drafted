# Drafted - A Simple Notes Sharing Web App

**Drafted** is a simple web application that allows users to share anonymous notes, rants, or heartfelt messages with others. Users can submit notes, browse shared messages, and even attach a music link to their notes. The app is built using **PHP** for the backend, **MySQL** for the database, and **HTML/CSS** for the frontend.

---

## Features

- **Submit Notes**: Share anonymous notes with a recipient, a message, and an optional music link.
- **Browse Notes**: View all shared notes in a grid layout.
- **Search Notes**: Search for notes by recipient name.
- **Responsive Design**: The app is designed to work seamlessly on both desktop and mobile devices.
- **Custom Styling**: Stylish and minimalistic design with a gradient background and handwritten fonts.

---

## Technologies Used

- **Frontend**:
  - HTML
  - CSS
  - JavaScript (for basic interactivity)
- **Backend**:
  - PHP (for server-side logic)
- **Database**:
  - MySQL (for storing notes)
- **Hosting**:
  - InfinityFree (free hosting service)

---

## Setup Instructions

### 1. **Set Up the Database**
1. Log in to your **InfinityFree control panel**.
2. Go to the **MySQL Databases** section and create a new database.
3. Create a new user and assign it to the database.
4. Use the following SQL query to create the `notes` table in your database:
   ```sql
   CREATE TABLE notes (
       id INT AUTO_INCREMENT PRIMARY KEY,
       to_person VARCHAR(255) NOT NULL,
       note TEXT NOT NULL,
       music_link VARCHAR(255),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
