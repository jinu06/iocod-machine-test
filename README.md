## Frontend Tools

1. Laravel Blade Engine (html)
2. Javascript/Jquery
3. Css
4. Select2 (for handling big data the merchants are shown on with pagination)

## Backend Tools

1. Laravel
2. Mysql

## Installation

1. Clone the repository and set up the environment file:
   Copy .env.example and rename it to .env
2. Run the following commands:
   -> Composer Install
   -> php artisan key:generate
   -> php artisan storage:link
3. Configure your database settings in the .env file, then run:
   -> php artisan migrate
4. Start your Laravel application (local environment):
   -> php artisan serve

## How to Use

1. Run the following command to insert mock data:
   -> php artisan fund:deals
   This command will insert mock data from the JSON file located at storage/leads.json into the leads table.
   If a lead has a lead_score of 80 or higher, it will also be inserted into the deals table and marked as assigned.
2. Application Startup – Initial Page:
   Once the server is running, the initial page displays the Bank Statement Upload functionality.
   You can upload up to 4 bank statements.
   Only CSV or PDF file formats are accepted.
   The Merchant ID field is mandatory.
   The dropdown/select input for Merchant ID will show only the assigned leads from the deals table.

## Used Features

Back-end (Laravel)
Action Classes – Encapsulate business logic for better code organization and reusability.

Traits – Shared methods across multiple classes to reduce duplication.

Artisan Commands – Custom console commands for tasks like importing mock data.

Jobs – Handle queued background tasks .

Eloquent ORM Relationships – Define relationships such as hasOne, hasMany, belongsTo, etc.

Database Migrations – Version-controlled database schema management.

Helper Functions – Custom global functions used across the application.

Front-end
HTML & CSS – Structure and styling of the interface.

JavaScript / jQuery – Client-side interactivity and DOM manipulation.

AJAX – Asynchronous data fetching (e.g., loading merchants or submitting forms without page reload).

## Routes Overview

1. /

Displays the welcome page, which includes the Bank Statement Upload section.

2. /api/deals/merchants

A GET API endpoint that returns a list of assigned leads.

This is typically used to populate a Select2 dropdown with merchant options.

3. /api/dashboard/summary

A GET API endpoint that provides a summary of all leads and assigned leads.

4. /merchant/{id}/upload-bank-statement

A POST API endpoint used to upload bank statement files (.pdf or .csv) for a specific merchant.

Note:
These are not API routes in the typical sense — all the above routes are defined in the web.php file instead of api.php.
