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
- Admin user for product CRUD (Create, Read, Update, Delete).
- Admin can view queries, compliments and complaints that are sent via the contact us page. 
- Payment systems where users can choose between the three most popular payment options.
- Filters and Sort by in the product page to make the website more user friendly.
- Product Detail page to show a detailed product description that also shows related products. 
- Responsiveness for all devices. 
- Secure password handling with password hashing and form validation. 
- Contact Us page for users to raise issues or just give feedback.
- About Us for users to learn about us.
- My Account section for the user to edit or just view their account information.
- Payment Methods page for the user to edit/delete/create their payment options.
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

3. **Prerequisites**
   Before running this project, ensure you have the following installed on your local machine:
   - A web server (e.g., [XAMPP](https://www.apachefriends.org/))
   - PHP 
   - MySQL
   - **[Composer](https://getcomposer.org/download/)** You MUST HAVE THIS INSTALLED AND WHEN INSTALLING MAKE SURE YOU ADD COMPOSER TO PHP PATH.
   - After installing Composer you can now open your GitBash and run the promnt below this is to ensure composer is installed.
   ```bash
    composer --version
    ```

4.  **Install PHP Dependencies** using Composer:
    ```bash
    composer install
    ```
    *Open a bash terminal in VS Code and run the above command, this command will create the necessary `vendor` folder and autoload files.*
    *IF YOU GET THE ERROR 'COMPOSER NOT FOUND' TRY CLOSING AND REOPENING VS CODE*

6. **Set up the database**
   - Open the ecommerce-cafe.sql file in MYSQL and run all the code

7. **Configure database connection**
   - Open `db.php`
   - Update the database host, username, password, and database name to match your environment

8. **Set up your web server**
   - Place the project folder in your web root (e.g., `htdocs` folder)
   - Make sure PHP is enabled and your web server is running

9. **Access the application**
   - Open XAMP, in Apache press start and then press admin
   - In your URL next to localhost put the name of the folder you saved it as in your `htdocs` folder
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
- Contributions, issues, and feature requests are welcome!
---
## Contact
- For support or inquiries, contact:
**Jared Van Schalkwyk**
Email: jaredjerome16@gmail.com
---

