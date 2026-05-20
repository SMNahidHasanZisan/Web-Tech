# ISP Media Content Project

Simple PHP + MySQL project for Web Technologies Project 04.

## How to run in XAMPP

1. Copy this project folder into `xampp/htdocs/isp_media`.
2. Start Apache and MySQL from XAMPP.
3. Open `http://localhost/phpmyadmin`.
4. Create/import the database using `database.sql`.
5. Visit `http://localhost/isp_media/public/setup.php` one time to create demo users and sample data.
6. Open `http://localhost/isp_media/public/index.php`.

The project requirement says guests/members should browse directly and should not see login/register in the navbar. Admin and moderator users can still open these direct URLs:

- `http://localhost/isp_media/public/login.php`
- `http://localhost/isp_media/public/register.php`

## Demo users

- Admin: `admin@example.com` / `admin12345`
- Moderator: `moderator@example.com` / `moderator12345`

## Main folders

- `config/` - database and helper functions
- `models/` - database queries
- `controllers/` - form and request processing
- `views/` - shared page parts such as header, footer and content cards
- `public/` - pages, CSS, JS, uploads
- `api/` - AJAX JSON endpoints
