<?php
global $connection;
require 'includes/database.php';

if ($_POST) {
    // Check XSS
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Validate data
    if (strlen($username) < 4) {
        header("Location:register.php?error=username is too short");
    }
    if (strlen($password) < 8) {
        header("Location:register.php?error=password is too short");
    }

    // Create user with mysql
    $token = bin2hex(random_bytes(16));
    $stmt = $connection->prepare("INSERT INTO users (username, password, remember_token) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $token);


    if($stmt->execute()) {
        // Make Cookie for user
        setcookie("remember_token", $token, time() + 3600 * 24 * 30, "/");
        header("Location:posts.php");
    }
    else {
        header("Location:register.php?error=invalid username or password");
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="includes/css/style.css">

</head>
<body>
<div class="container mt-5">

    <?php
    if (isset($_GET["error"])) {
        echo '<div class="alert alert-danger">'.htmlspecialchars($_GET["error"]).'</div>';
    }
    ?>

    <form class="form" action="register.php" method="post">
        <div class="form-group mb-3">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Register" class="btn btn-primary">
        </div>
    </form>

</div>

</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
