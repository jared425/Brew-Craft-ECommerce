# Brew Craft E-Commerce Website
Welcome to  **Brew Craft**, this E-Commerce website is designed to bring the cafe experience to peoples homes.
---
## Table of Contents
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Usage](#usage)
- [Folder Structure](#folder-structure)
- [Contributing](#contributing)
- [Contact](#contact)
---
## Features
- User authentication with email verification to ensure real users. 
- Product CRUD (Create, Read, Update, Delete). With the admin section of the project.
- Payment systems where users can choose between the three most popular payment options.
- Filters in the product page to make the website more user friendly.
- Product Detail page to show a detailed product description. 
- Responsiveness for all devices. 
- Secure password handling with passwrod hashing and form validation. 
- Contact Us page for users to raise issues or just give feedback.
- About Us for users to learn about our objectives.
- My Account section for the user to edit or just view their information.
- Payment Methods page for the user to edit their payment options and change their default paying method.
- Functional Footer for a more convinient user experience.
- Social media links in both the footer and the Contact Us page. 
---
## Technologies Used
- PHP 7.x / 8.x
- MySQL
- Bootstrap 5
- JavaScript (for UI interactivity)
- Composer (for the email verification)
- HTML5 & CSS3
---
## Installation
1. **Download the zip version of the project**
   ``` https://github.com/jared425/Brew-Craft-ECommerce/tree/Coding-Branch ```

2. **Prerequisites**
   Before running this project, ensure you have the following installed on your local machine:
   - A web server (e.g., [XAMPP](https://www.apachefriends.org/))
   - PHP 
   - MySQL
   - **[Composer](https://getcomposer.org/download/)** You MUST HAVE THIS INSTALLED AND WHEN INSTALLING MAKE SURE YOU ADD COMPOSER TO PHP PATH.
   - After installing Composer you can now open your GitBash and run 'composer --version' (dont run it with the quotes) to ensure that composer is installed on your local computer.

2.  **Install PHP Dependencies** using Composer:
    ```bash
    composer install
    ```
    *Open a bash terminal in VS Code and run the above command, this command will create the necessary `vendor` folder and autoload files.*

3. **Set up the database**
   - Open the ecommerce-cafe.sql file in MYSQL and run all the code (CTR+SHIFT+ENTER)

4. **Configure database connection**
   - Open `db.php`
   - Update the database host, username, password, and database name to match your environment

5. **Set up your web server**
   - Place the project files in your web root (e.g., `htdocs` folder)
   - Make sure PHP is enabled and your web server is running

7. **Access the application**
   - Open your browser and go to `http://localhost/` what the folder is saved as on your local computer put that after the slash nect tp localhost
---
## Usage
- Admin credentials for CRUD (Create, Read, Update, Delete):
  `Username: Aaliyah`
  `Password: password`
- Users will have full access to the site. But certain features are limited like purchasing etc. 
- User can only purchase, add to cart etc when they create an account and verify it. 
- When signing up the user must verify their by clicking the link sent to the email they provided.
---
## Contributing
Contributions, issues, and feature requests are welcome!
---
## Contact
For support or inquiries, contact:
**Jared Van Schalkwyk**
Email: jaredjerome16@gmail.com
GitHub: [Jared Van Schalkwyk](https://github.com/jared425/HR-System.git)
---
