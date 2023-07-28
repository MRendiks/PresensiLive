# ![PresensiLive](logo.png)

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)


Clone the repository

    git clone https://github.com/MRendiks/PresensiLive.git

Switch to the repo folder

    cd PresensiLive

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Set Database for what you want
    DB_DATABASE=your_database
    DB_USERNAME=your_user_database

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Or Run the database migration with seeder

    php artisan migrate:fresh--seed

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone https://github.com/MRendiks/PresensiLive.git
    cd PresensiLive
    composer install
    cp .env.example .env
    php artisan key:generate
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate:fresh--seed
    php artisan serve
