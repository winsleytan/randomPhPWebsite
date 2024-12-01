<?php
// index.php

// Start the session
session_start();

// Database Configuration (Replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "register";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Define variables for error messages
$error_message = '';

// Assuming $conn is your PDO connection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($phone) && !empty($password)) {
        // Check if the user is an admin by looking in the admin.txt file
        $admin_file = 'admin.txt';
        $admin_found = false;
        $admins = file($admin_file, FILE_IGNORE_NEW_LINES);
        
        foreach ($admins as $admin) {
            list($admin_phone, $admin_password) = explode(',', $admin);
            if ($phone === $admin_phone && $password === $admin_password) {
                // Admin found, redirect to admin.php
                $_SESSION['loggedin'] = true;
                header("Location: admin.php");
                exit();
            }
        }

        // Check credentials against the database for normal users
        $sql = "SELECT * FROM users WHERE phone = :phone";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Check if the password matches
            if ($user['password'] === $password) {
                // Normal user found, login successful
                $_SESSION['loggedin'] = true;
                header("Location: user.php");
                exit();
            } else {
                $error_message = "Invalid phone number or password.";
            }
        } else {
            $error_message = "Invalid phone number or password.";
        }
    } else {
        $error_message = "Please enter both phone number and password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SPSYOK - HOME</title>
    <link rel="icon" href="./img/skibidi.jpg" type="image/x-icon" sizes="16x16">
    <style>
        /* Your CSS styles here */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .container {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            width: 800px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .content {
            display: flex;
            justify-content: space-between;
        }
        .login {
            width: 30%;
            padding: 10px;
            border-right: 1px solid #ccc;
        }
        .login h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .login label {
            display: block;
            margin-bottom: 5px;
        }
        .login input[type="text"], .login input[type="password"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login input[type="submit"], .login input[type="reset"] {
            padding: 10px 20px;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .login input[type="reset"] {
            background-color: #6c757d;
        }
        .login a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }
        .welcome {
            width: 65%;
            padding: 10px;
        }
        .welcome h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .categories {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .category {
            padding: 10px 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .images {
            display: flex;
            justify-content: space-between;
        }
        .image-box {
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            width: 100px;
            background-color: #f9f9f9;
        }
        .image-box img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .image-box p {
            margin-top: 10px;
            border -top: 1px solid #ccc;
            padding-top: 5px;
        }
        .image-box input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .image-box button {
            padding: 5px 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            SPSYOK
        </div>
        <div class="content">
            <div class="login">
                <h2>LOG MASUK</h2>
                <p>Untuk ruangan pelanggan sedia ada</p>
                <form method="post" action="">
                    <label for="phone">Nombor HP:</label>
                    <input id="phone" name="phone" placeholder="TAIP DI SINI" type="text" required/>
                    <label for="password">Kata Laluan:</label>
                    <input id="password" name="password" placeholder="********" type="password" required/>
                    <input type="submit" value="SIGN IN"/>
                    <input type="reset" value="RESET"/>
                    <a href="register.php">Pelanggan Baru?</a>
                </form>
                <?php if ($error_message): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
            </div>
            <div class="welcome">
        <h2>SELAMAT DATANG KE SPSYOK</h2>
        <div class="categories">
  <div class="category" data-category="Category 1">Category 1</div>
  <div class="category" data-category="Category 2">Category 2</div>
  <div class="category" data-category="Category 3">Category 3</div>
  <div class="category" data-category="Category 4">Category 4</div>
</div>

<div class="images">
  <div class="image-box" data-category="Category 1">
    <img alt="GAMBAR 1" src="https://placehold.co/100x100"/>
    <p>GAMBAR 1</p>
    <label for="quantity1">Quantity:</label>
    <input id="quantity1" type="number" min="1" value="1"/>
    <button onclick="showSignInMessage()">Add to Cart</button>
  </div>

  <div class="image-box" data-category="Category 2">
    <img alt="GAMBAR 2" src="https://placehold.co/100x100"/>
    <p>GAMBAR 2</p>
    <label for="quantity2">Quantity:</label>
    <input id="quantity2" type="number" min="1" value="1"/>
    <button onclick ="showSignInMessage()">Add to Cart</button>
  </div>

  <div class="image-box" data-category="Category 3">
    <img alt="GAMBAR 3" src="https://placehold.co/100x100"/>
    <p>GAMBAR 3</p>
    <label for="quantity3">Quantity:</label>
    <input id="quantity3" type="number" min="1" value="1"/>
    <button onclick="showSignInMessage()">Add to Cart</button>
  </div>

  <div class="image-box" data-category="Category 4">
    <img alt="GAMBAR 4" src="https://placehold.co/100x100"/>
    <p>GAMBAR 4</p>
    <label for="quantity4">Quantity:</label>
    <input id="quantity4" type="number" min="1" value="1"/>
    <button onclick="showSignInMessage()">Add to Cart</button>
  </div>
</div>

<script>
  const categories = document.querySelectorAll('.category');
  const images = document.querySelectorAll('.image-box');

  categories.forEach(category => {
    category.addEventListener('click', () => {
      const selectedCategory = category.getAttribute('data-category');
      images.forEach(image => {
        if (image.getAttribute('data-category') === selectedCategory) {
          image.style.display = 'block';
        } else {
          image.style.display = 'none';
        }
      });
    });
  });
</script>

<script>
        function showSignInMessage() {
            alert("Anda mesti log masuk terlebih dahulu untuk menambah ke troli.");
        }
</script>
</body>
</html>
