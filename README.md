# Unli wings

A project that shows a simple authentication for a super admin and admin role and a demonstration of update/delete of an order.

## How to install

-   Clone the repository
-   Create a database named `wings` then run your XAMPP or WAMPP depends on what you have (Note that if you name your database differently update the `DB_DATABASE` value on `.env.example` before you proceed to the next step).
-   Then `./install-script.bash`
-   You should be able to view the app on `http://127.0.0.1:8000/`
-   Here are the credentials you can use for Super Admin and Admin role:

Super Admin <br>
email: `super@admin.com` <br>
password: `password`

Admin <br>
email: `admin@admin.com` <br>
password: `password`

If you want to play around on your local, make sure to also run `npm run dev` (along with `php artisan serve`) to watch for any style/script changes. Enjoy!
