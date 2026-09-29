# ApexPlanet Task 4 — BookNest Real-World Full Stack Project

## Project
BookNest is a responsive online bookstore built with PHP, MySQL and Bootstrap 5.

### Features
- Project planning and modular structure
- User registration/login/logout with PHP sessions
- Role-based access: Admin/User
- Product CRUD in admin panel
- Product search, category filters and pagination
- Shopping cart with quantity updates
- Checkout and order creation using database transactions
- User order history and order details
- Admin order status management
- Admin user management
- Forgot/reset password demo flow
- Server-side validation
- `password_hash()` and `password_verify()`
- Prepared statements for user-input queries
- Responsive Bootstrap UI
- Mobile-first layout

## Setup
1. Copy the `task4` folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP.
3. Open `http://localhost/task4/setup.php` once.
4. Open `http://localhost/task4/`.

If phpMyAdmin is available, `database.sql` can also be imported manually.

### Demo accounts
- Admin: `admin@booknest.com` / `admin123`
- User: `user@booknest.com` / `user123`

## Demo flow for the 10-minute video
1. Home page and responsive design.
2. Register a new user.
3. Login as User and open dashboard.
4. Browse books.
5. Search/filter and open a product.
6. Add books to cart and update quantity.
7. Checkout and place an order.
8. Show order history/details.
9. Logout and login as Admin.
10. Open Admin Panel.
11. Add/edit/delete a product.
12. Show users and order management.
13. Mention prepared statements, password hashing, sessions and database relationships.
14. Show GitHub repository and README.

## Database design
`roles` stores Admin/User role names separately. `users` references roles through `role_id`. `products`, `orders` and `order_items` model the bookstore workflow. `password_resets` stores temporary reset tokens.

## Security
- Passwords are hashed, not stored as plaintext.
- `password_verify()` is used during login.
- Prepared statements are used for user input.
- Output is escaped with `htmlspecialchars`.
- Admin routes use session-based role checks.
- Checkout uses a transaction.
- Reset tokens expire and are marked used.
