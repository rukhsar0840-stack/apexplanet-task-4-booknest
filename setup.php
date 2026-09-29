<?php
$host="127.0.0.1";$user="root";$pass="";$db="apexplanet_task4";
$conn=new mysqli($host,$user,$pass);if($conn->connect_error)die("MySQL connection failed. Start MySQL in XAMPP.");
$conn->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");$conn->select_db($db);
$sqls=[
"CREATE TABLE IF NOT EXISTS roles(id INT AUTO_INCREMENT PRIMARY KEY,role_name VARCHAR(50) NOT NULL UNIQUE)",
"CREATE TABLE IF NOT EXISTS users(id INT AUTO_INCREMENT PRIMARY KEY,role_id INT NOT NULL,name VARCHAR(100) NOT NULL,email VARCHAR(150) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL,phone VARCHAR(30),bio TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(role_id) REFERENCES roles(id))",
"CREATE TABLE IF NOT EXISTS products(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(180) NOT NULL,author VARCHAR(120) NOT NULL,category VARCHAR(80) NOT NULL,price DECIMAL(10,2) NOT NULL,stock INT NOT NULL DEFAULT 0,description TEXT,is_active TINYINT(1) DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)",
"CREATE TABLE IF NOT EXISTS orders(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,total_amount DECIMAL(10,2) NOT NULL,status VARCHAR(30) DEFAULT 'Pending',address TEXT,phone VARCHAR(30),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id))",
"CREATE TABLE IF NOT EXISTS order_items(id INT AUTO_INCREMENT PRIMARY KEY,order_id INT NOT NULL,product_id INT NOT NULL,quantity INT NOT NULL,price DECIMAL(10,2) NOT NULL,FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE,FOREIGN KEY(product_id) REFERENCES products(id))",
"CREATE TABLE IF NOT EXISTS password_resets(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,token VARCHAR(64) NOT NULL UNIQUE,expires_at DATETIME NOT NULL,used TINYINT(1) DEFAULT 0,FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE)"
];
foreach($sqls as $s)$conn->query($s);
$conn->query("INSERT IGNORE INTO roles(role_name) VALUES('Admin'),('User')");
$adminHash=password_hash("admin123",PASSWORD_DEFAULT);$userHash=password_hash("user123",PASSWORD_DEFAULT);
$stmt=$conn->prepare("INSERT IGNORE INTO users(role_id,name,email,password) SELECT id,?,?,? FROM roles WHERE role_name='Admin'");$n="BookNest Admin";$e="admin@booknest.com";$stmt->bind_param("sss",$n,$e,$adminHash);$stmt->execute();$stmt->close();
$stmt=$conn->prepare("INSERT IGNORE INTO users(role_id,name,email,password) SELECT id,?,?,? FROM roles WHERE role_name='User'");$n="Demo User";$e="user@booknest.com";$stmt->bind_param("sss",$n,$e,$userHash);$stmt->execute();$stmt->close();
$count=(int)$conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()["c"];
if($count===0){
$products=[
["Atomic Habits","James Clear","Self Help",499,25,"A practical guide to building good habits and breaking bad ones."],
["The Alchemist","Paulo Coelho","Fiction",299,30,"A timeless story about dreams, courage and discovering your path."],
["Clean Code","Robert C. Martin","Technology",699,15,"A classic guide to writing readable and maintainable software."],
["Ikigai","Héctor García","Self Help",349,20,"Explore the Japanese concept of purpose, balance and everyday meaning."],
["The Psychology of Money","Morgan Housel","Finance",449,18,"Lessons on wealth, behavior and making better financial decisions."],
["Deep Work","Cal Newport","Productivity",399,22,"Strategies for focused work in a distracted world."],
["The Pragmatic Programmer","Andrew Hunt","Technology",799,12,"Practical principles for becoming a better software developer."],
["Rich Dad Poor Dad","Robert Kiyosaki","Finance",379,24,"Foundational lessons about money, assets and financial thinking."]
];$stmt=$conn->prepare("INSERT INTO products(title,author,category,price,stock,description) VALUES(?,?,?,?,?,?)");foreach($products as $p){$stmt->bind_param("sssdis",$p[0],$p[1],$p[2],$p[3],$p[4],$p[5]);$stmt->execute();}$stmt->close();}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><title>BookNest Setup</title></head><body class="bg-light"><div class="container py-5"><div class="card shadow-sm p-5"><h1 class="text-success">Setup complete ✓</h1><p>Database, tables, roles, demo users and sample products are ready.</p><p><b>Admin:</b> admin@booknest.com / admin123<br><b>User:</b> user@booknest.com / user123</p><a class="btn btn-primary" href="index.php">Open BookNest</a></div></div></body></html>