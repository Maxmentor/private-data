# 🔐 Private Data Vault - PHP Web Application

A secure, mobile-responsive, and role-based PHP web application designed to store and manage private documents (with multiple photos) and sensitive account credentials.

---

## 🚀 Hosting & Deployment Guide

Follow these steps to host and run this project on any web hosting provider (cPanel, Hostinger, InfinityFree, etc.):

### Step 1: Upload Project Files

1. Compress your project folder into a `.zip` file.
2. Log in to your hosting control panel (e.g., cPanel) and open the **File Manager**.
3. Navigate to the `public_html` directory (or your domain's root folder).
4. Upload the `.zip` file here and **Extract** it.
5. Create an empty folder named `uploads` inside your root directory (if not already present) and set its permissions to `755` or `777` so document photos can be saved.

### Step 2: Create MySQL Database & Import `data.sql`

1. In your hosting panel, look for **MySQL Database Wizard**.
2. Create a new database (e.g., `private_data`).
3. Create a database user with a strong password and **Assign all privileges** to the database.
4. Open **phpMyAdmin** from your control panel, select your newly created database, go to the **Import** tab, upload your `data.sql` file, and click **Go**.

### Step 3: Configure Database Connection (`db.php`)

1. Open the `db.php` file in your hosting File Manager editor.
2. Update the credentials with your live hosting database details:
```php
$host = "localhost";
$user = "your_db_username"; 
$pass = "your_db_password"; 
$dbname = "your_db_name";

```


3. Save the file.

---

## 🔑 Default Login Credentials

Use the following credentials to log in for the first time:

* **Username:** `admin`
* **Password:** `1234`

*(Make sure to change your password from the **Edit Profile** section after logging in).*

---

## ✨ Features

* **Role-Based Access Control (RBAC):** Distinct administrative and user environments. Admins can oversee users while preserving data isolation.
* **Document Management:** Store document details with multiple photo attachments, full-screen view modals, and direct downloads.
* **Account Vault:** Categorize and save encrypted-style credential details (Email, Passwords, Mobile, Recovery Mail, Backup Codes).
* **Enhanced Security:** Client-side anti-tamper script protection against unauthorized script manipulation or inspection exploits.
* **Corporate UI:** Clean, sharp-edged light theme optimized for mobile and desktop screens.

---

## 📸 Screenshots

*(Click below to view the application screenshots)*

* [Screenshot 1: Login & Authentication Link](https://www.google.com/search?q=%23)
* [Screenshot 2: Admin/User Dashboard Link](https://www.google.com/search?q=%23)
* [Screenshot 3: Document Management & Photo Viewer Link](https://www.google.com/search?q=%23)
* [Screenshot 4: Account Vault & Categories Link](https://www.google.com/search?q=%23)
* [Screenshot 5: Admin Member Management Link](https://www.google.com/search?q=%23)

---

## 💻 Credits

Copyright © [@Maxmentor](https://github.com/maxmentor) | [Telegram](https://t.me/maxmentor)
