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
    <title>SPSYOK - MENU</title>
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
            background-image: url("./img/bg2.jpg");
        }
        .container {
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    width: auto; /* Automatically adjust to fit content */
    height: auto; /* Automatically adjust to fit content */
    max-width: 80%; /* Optional: Restrict max width for responsive design */
    margin: auto;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
        .header {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            background-image: url("./img/bg.png");
            color: white;
        }
        .content {
            display: flex;
            justify-content: space-between;
        }
        .sidebar {
            width: 20%;
            padding: 10px;
        }
        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .sidebar a {
            display: block;
            color: blue;
            text-decoration: none;
            margin: 5px 0;
        }
        .sidebar input[type="text"] {
            width: 100%;
            padding: 5px;
            margin: 10px 0;
        }
        .sidebar input[type="button"] {
            width: 100%;
            padding: 5px;
            background-color: #ccc;
            border: none;
            cursor: pointer;
        }
        .main-content {
            width: 80%;
            padding: 10px;
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

<div class="sidebar">
     <h2>
      MENU
     </h2>
     <h3>
      PELANGGAN
    </h3>
     <a href="#">
      Home
     </a>
     <a href="#">
      Carian
     </a>
     <input placeholder="TAIP DI SINI" type="text"/>
     <input type="button" value="Cari"/>
     <a href="cart.php">
      Pesanan Terdahulu
     </a>
     <a href="logout.php">
      Keluar
     </a>
    </div>
    <div class="welcome">
        <h2>SELAMAT DATANG KE SPSYOK</h2>
        <div class="categories">
  <div class="category" data-category="Classic Pizza">Classic Pizza</div>
  <div class="category" data-category="Veggie Pizza">Veggie Pizza</div>
  <div class="category" data-category="Specialty Pizza">Specialty Pizza</div>
</div>

<div class="images">
  <div class="image-box" data-category="Classic Pizza">
    <img alt="margherita" src="./img/margherita.jpg" style="width: 100px; height: 100px; object-fit: cover;"/>
    <p>Margherita Pizza</p>
    <label for="quantity1">Quantity:</label>
    <input id="quantity1" type="number" min="1" value="1"/>
    <button onclick="showAddToCartMessage()">Add to Cart</button>
  </div>

  <div class="image-box" data-category="Classic Pizza">
  <img alt="hawaiian" src="./img/hawaiian.png" style="width: 100px; height: 100px; object-fit: cover;"/>
    <p>Hawaiian Chicken Pizza</p>
    <label for="quantity2">Quantity:</label>
    <input id="quantity2" type="number" min="1" value="1"/>
    <button onclick ="showAddToCartMessage()">Add to Cart</button>
  </div>

  <div class="image-box" data-category="Veggie Pizza">
    <img alt="veggie" src="./img/vegetarian.jpg" style="width: 100px; height: 100px; object-fit: cover;"/>
    <p>Veggie Supreme Pizza</p>
    <label for="quantity3">Quantity:</label>
    <input id="quantity3" type="number" min="1" value="1"/>
    <button onclick="showAddToCartMessage()">Add to Cart</button>
  </div>

  <div class="image-box" data-category="Specialty Pizza">
    <img alt="bbq" src="./img/bbq_chicken.jpg" style="width: 100px; height: 100px; object-fit: cover;"/>
    <p>BBQ Chicken Pizza</p>
    <label for="quantity4">Quantity:</label>
    <input id="quantity4" type="number" min="1" value="1"/>
    <button onclick="showAddToCartMessage()">Add to Cart</button>
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
        function showAddToCartMessage(){
            alert("Makanan telah ditambah ke cart!!");
        }
</script>
</body>
</html>
