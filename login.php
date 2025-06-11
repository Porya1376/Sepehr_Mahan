<?php
global $connection;
require "includes/database.php";


if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Check user in database
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query failed
    if ($result->num_rows === 1) {
        $token = bin2hex(random_bytes(16));
        setcookie("remember_token", $token, time() + (86400 * 30), "/");
        $stmt = $connection->prepare("UPDATE users SET remember_token = ? WHERE users.username = ?");
        $stmt->bind_param('ss', $token, $username);
        $stmt->execute();
        header("location:posts.php");
    } else {
        header("location:login.php?error=username or password is incorrect");
    }
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="includes/css/style.css">
</head>
<body>
<?php
if (isset($_GET["error"])) {
    echo '<h4 class="alert alert-danger">' . $_GET["error"] . '</h4>';
}
?>

<form class="form" action="login.php" method="post">

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="form-group">
        <label for="pass">Password</label>
        <input type="text" name="password" class="form-control">
    </div>
    <div class="form-group">
        <input type="submit" name="login" value="Login" class="btn btn-success">
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>




