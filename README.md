# Hotel Booking

A simple hotel booking system built using CSS, SCSS, PHP, HTML, and JavaScript.

## Description

This project is a hotel booking system that allows users to search for available rooms, make reservations, and manage bookings. It is designed to be user-friendly and efficient. The system includes features such as user authentication, room availability search, booking management, and more.

## Features

- **Search for Available Rooms:** Users can search for available rooms based on their preferences.
- **Make Reservations:** Users can make reservations by selecting available rooms.
- **Manage Bookings:** Users can view and manage their bookings from a user dashboard.
- **User Authentication:** Secure login and registration for users.
- **Responsive Design:** The system is designed to be responsive and works on various devices.
- **Room Details:** Detailed information about each room including images, amenities, and pricing.

## Technologies Used

- **CSS:** Styling of the web pages.
- **SCSS:** Enhanced styling with SASS.
- **PHP:** Backend logic and database interactions.
- **HTML:** Structure of the web pages.
- **JavaScript:** Client-side scripting for dynamic interactions.

## Installation

### Prerequisites

- PHP 7.x or higher
- MySQL or MariaDB
- Web server (e.g., Apache or Nginx)

### Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/VattraF4/hotelbooking.git
2. **Navigate to the project directory:**

    ```bash
    cd hotelbooking
    ```
- ### Start MySQL service:
If you are using XAMPP, MAMP, or another local server, start MySQL through the control panel.
Alternatively, if you're using MySQL in a Linux environment, start it with:
```bash
    sudo service mysql start
```
1. **Set up the database:**
 - Create a new database for the project.
 - Import the database schema from the database/``database.sql`` file into your database.
2. **Configure the database connection:**
   - Open the ``config.php`` file and update the database connction setting with your database credentials.
    ```bash
   <?php
   try {
       // Check if running on localhost
       if ($_SERVER['HTTP_HOST'] === 'localhost') {
           // Local Development
           define("DB_HOST", "localhost");
           define("DB_NAME", "e4g7wad_hotel-booking"); // Your local DB name
           define("DB_USER", "root");
           define("DB_PASS", "");
       } else {
           //host
           // define("DB_HOST", "bh-34.webhostbox.net");
           define("DB_HOST", "bh-34.webhostbox.net");
           //database name
           define("DB_NAME", "e4g7wad_hotel-booking");
           //database user
           define("DB_USER", "e4g7wad_root");
           //database password
           define("DB_PASS", "3C]apZ6Fip;x");
       }
       $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       // Check connection
       if ($conn == true) {
           // echo "Connected successfully";
       } else {
           echo "Connection failed: ";
       }


       //PDO: PHP Data Objects (PHP Extension) is a database access layer providing a uniform method of access to multiple databases.
   //PDO is not an abstraction layer which simply emulates the functionality of other databases;
   //  it is a unique layer designed specifically for PHP, that allows developers to write portable code between databases.
   } catch (PDOException $e) {
       echo "Connection failed: " . $e->getMessage();
   }
   ```
5. **Start the server:**
   ```bash
      php -S loacalhost:8000
   ```

6. **Access the system:**

Open a web browser and go to http://localhost:8000

## License

This project is open-source and available under the MIT License.

## Contributors

Ra Vattra - [Visit GitHub](https://vattraf4.github.io/My-Portfolio)