<?php
// Initialize variables for form data
$phone = '';
$name = '';
$password = substr($phone, 0, 4);


// Password generation function
// function generatePassword($length = 8) {
//     $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
//     $password = '';
//     for ($i = 0; $i < $length; $i++) {
//         $password = $charset[random_int(0, strlen($charset) - 1)];
//     }
//     return $password;
// }

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


$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
$passwordLength = 12; // Set desired password length
$password = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = htmlspecialchars(trim($_POST['phone']));
    if (!preg_match('/^[0-9]+$/', $phone)) {
        die("Invalid phone number. Please enter only numeric values.");
    }

    $name = htmlspecialchars(trim($_POST['name']));

    for ($i = 0; $i < $passwordLength; $i++) {
        $password .= $characters[random_int(0, strlen($characters) - 1)];
    }
    //$password = bin2hex(random_bytes(8));  // Replace with actual password generation logic

    try {
        // Insert into database
        $sql = "INSERT INTO users (name, phone, password) VALUES (:name, :phone, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Safely output JavaScript with sanitized variables
        echo "<script>
            alert('Pendaftaran berjaya! Nombor HP: ' + " . json_encode($phone) . " + ', Nama: ' + " . json_encode($name) . " + ', KATA LALUAN anda adalah: ' + " . json_encode($password) . ");
        </script>";
    } catch (PDOException $e) {
        echo "<script>alert('Pendaftaran gagal: " . addslashes($e->getMessage()) . "');</script>";
    }
}


$conn = null; // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPSYOK</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-image: url('./img/bg.png'); background-repeat: no-repeat;   }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 500px;
            width: 100%;
            margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .logo img {
            width: 100px;
            height: 100px;
        }
        .logo h1 {
            margin: 10px 0 0;
            font-size: 24px;
        }
        .header {  padding: 10px; text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 36px; }
        .content h2 { font-size: 24px; margin-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: bold; display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; font-size: 14px; }
        .buttons { display: flex; justify-content: space-between;  border-radius: 5px; }
        .buttons button { padding: 10px 20px; font-size: 14px;  cursor: pointer;  border-radius: 5px; }
        .footer { font-size: 12px; margin-top: 20px; }
        .cont {	
    text-align: center;
    margin-bottom: 15px;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SPSYOK</h1>
            <div class="logo">
            <img src="./img/logo.png" alt="Pizza Logo">  
        </div>
        <div class="content">
            <h2>PENDAFTARAN PELANGGAN BAHARU</h2>
            <p>*Pastikan maklumat anda betul sebelum membuat pendaftaran</p>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="phone">Nombor HP:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Nombor HP tanpa tanda -" pattern="[0-9]+" 
                    required >
                </div>
                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Nama Anda" required>
                </div>
                <div class="buttons">
                    <button type="submit">DAFTAR</button>
                    <button type="reset">RESET</button>
                    <button type="button" onclick="window.location.href='index.php';">BACK TO HOME</button>
                </div>
            </form>
        </div>
        <div class="footer">
            *Password adalah 4 digit di depan nombor HP anda yang dijana secara automatik
        </div>
    </div>
</body>
</html>
