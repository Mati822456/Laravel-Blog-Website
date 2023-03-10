# Laravel-Blog-Website

A straightforward blog page written in PHP/Laravel. 

![main](https://user-images.githubusercontent.com/103435077/222498136-66c4fb01-4053-4b14-9b08-c61423a12380.png)

## Table of Contents
* [General Info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)
* [Incoming Features](#incoming-features)
* [Acknowledgements](#acknowledgements)
* [Contact](#contact)

## General Info
The website was built with PHP, MySQL, and Laravel. It allows you to go through every post that is on the main page. You can add comments. You have complete control while logged in as an administrator, including the ability to create, edit, and delete posts, users, and comments, as well as manage roles. There are two positions available: administrator and writer. Default permissions for Writer are: adding or editing owned posts; deleting comments in your posts. Furthermore, you can save posts, continue writing later, and then publish them. Also, you are able to send emails to users after updating their accounts.

Furthermore, the website is fully responsive.

I tried to add the best protection I could to this website. I'm referring to prohibitions against deleting other people's posts besides the "Admin" account, deleting roles owned by others or the "Admin" role, deleting yourself, and even editing another person's account.

![post](https://user-images.githubusercontent.com/103435077/222498262-06241a60-120f-4595-9765-e75f0371954d.png)

## Technologies
* Laravel 9.45.1
* Blade
* PHP 8.1.7
* MySQL 8.0.29
* HTML 5
* CSS 3
* JavaScript
* JQuery
* SweetAlert 2
* FontAwesome 6.1.2

## Setup
To run this project you will need to install PHP, MySQL, [Composer](https://getcomposer.org/download/), [NPM](https://www.npmjs.com/package/npm) on your local machine.

If you have everything, you can run these commands:

```
# Clone this respository
> git clone https://github.com/Mati822456/Laravel-Blog-Website.git

# Go into the folder
> cd Laravel-Blog-Website

# Install dependencies from lock file
> composer install

# Install packages from package.json
> npm install

# Compile assets 
> npm run dev
```

`Create or copy the .env file and configure it. e.g., db_username, db_password, db_database`
</br>
`You will need to configure SMTP in order to send emails.`

```
# Generate APP_KEY
> php artisan key:generate

# Run migrations if you have created database
> php artisan migrate

# Run seeder to create Permissions, Admin and Writer users and 10 random posts
> php artisan db:seed

# Start server
> php artisan serve

# Access using
http://localhost:8000
```

Now you can login using created accounts:
```
Role: Admin
Email: admin@db.com
Password: admin1234

Role: Writer
Email: writer@db.com
Password: writer1234
```

![dashboard](https://user-images.githubusercontent.com/103435077/222498375-b9d12ae4-1eb9-47bb-8a9d-b446675f7fc5.png)
![posts_create](https://user-images.githubusercontent.com/103435077/222498518-6e6b2c32-28dd-4379-8eeb-6a93d0bc9dec.png)

## Incoming Features
* version control of each post
* probably tiles on the home page

## Acknowledgements
Thanks <a href="https://www.flaticon.com/free-icons/user" title="user icons">User icons created by kmg design - Flaticon</a> for the user profile icon</br>
Thanks <a href="https://www.flaticon.com/free-icons/email" title="email icons">Email icons created by Freepik - Flaticon</a> for the envelope icon on the contact page</br>
Thanks <a href="https://www.flaticon.com/free-icons/blog" title="blog icons">Blog icons created by zero_wing - Flaticon</a> for the blog icon as favicon</br>

## Contact
Feel free to contact me via email mateusz.zaborski1@gmail.com. :D