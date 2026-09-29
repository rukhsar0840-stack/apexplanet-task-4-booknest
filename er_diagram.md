# ER Diagram — Task 4

```text
ROLES
-----
id PK
role_name UNIQUE
   |
   | 1-to-many
   v
USERS
-----
id PK
role_id FK -> roles.id
name
email UNIQUE
password
phone
bio
created_at
   |
   | 1-to-many
   v
ORDERS
------
id PK
user_id FK -> users.id
total_amount
status
address
phone
created_at
   |
   | 1-to-many
   v
ORDER_ITEMS
-----------
id PK
order_id FK -> orders.id
product_id FK -> products.id
quantity
price
   ^
   | many-to-1
   |
PRODUCTS
--------
id PK
title
author
category
price
stock
description
is_active
created_at

USERS 1-to-many PASSWORD_RESETS
PASSWORD_RESETS: id, user_id FK, token, expires_at, used
```

The schema separates roles from users and orders from order line items, keeping repeated data out of the main entities.
