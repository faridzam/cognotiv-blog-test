requirement
- php version : 8.2.3^
- composer version : 2.4.2^
- node version : 18.14.2^
- npm version : 9.5.0^

local installation guide

- clone project from git (ex: "git clone https://github.com/faridzam/cognotiv-blog-test.git")
- Run "composer install"
- Copy .env.example file to .env and customize DB_DATABASE, DB_USERNAME, and DB_PASSWORD according to your database settings.
- Run "php artisan key:generate"
- Run "php artisan migrate --seed" (seeding admin user with email 'admin@app.com' and password 'admin')
- Run "php artisan storage:link"
- Run "npm install"

- open terminal then run "php artisan serve"
- open another terminal then run "npm run dev"

- open localhost port 8000 on your browser to access the website
