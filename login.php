<?php Include 'includes/function.php'; 
    $conn = dbConnect();
    if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
        header("Location: kamer.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <section>
            <div class="login-container">
                <form method="POST">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit" name="submit">Login</button>
                    <?php
                    if (isset($_GET['succes'])) {
                        echo "<p>U bent succesvol uitgelogd</p>";
                    }
                    ?>
                </form> 
                <?php login($conn); ?>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/script.js"></script>
</body>

</html>