<?php
include 'includes/function.php';

$isAdmin = isset($_SESSION['name']) && !empty($_SESSION['name']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    $conn = dbConnect();

    if (isset($_POST['delete_photo_id'])) {
        deleteRoomPhoto($conn, $_POST['delete_photo_id']);
        header("Location: kamer.php");
        exit();
    }

    if (isset($_POST['add_photo_room_id']) && isset($_FILES['photos'])) {
        $result = uploadRoomPhotos($conn, $_POST['add_photo_room_id'], $_FILES['photos']);
        $query = $result['skipped'] > 0 && $result['uploaded'] === 0 ? '?photo_error=1' : '';
        header("Location: kamer.php$query");
        exit();
    }

    if (isset($_POST['create_room'])) {
        $roomId = createRoom($conn, $_POST['type'], $_POST['description'], $_POST['price']);
        if ($roomId && isset($_FILES['photos'])) {
            uploadRoomPhotos($conn, $roomId, $_FILES['photos']);
        }
        header("Location: kamer.php");
        exit();
    }

    if (isset($_POST['delete_room']) && isset($_POST['id'])) {
        deleteRoom($conn, $_POST['id']);
        header("Location: kamer.php");
        exit();
    }

    if (isset($_POST['id'])) {
        updateRoom($conn, $_POST['id'], $_POST['type'], $_POST['description'], $_POST['price']);
        header("Location: kamer.php");
        exit();
    }
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
        <section class="section-split" id="header-kamer">
            <div class="section-half">
                <div class="text-bg">
                    <h1>Kamers en appartementen</h1>
                    <p>Verdeeld over vier historische panden heb je bij ons de keuze uit tien appartementen, vier boetiek kamers en één studio.<br>
                        Variërend in grootte en stijl zit er ongetwijfeld een mooi onderkomen tussen die jouw/jullie verblijf onvergetelijk zal maken.<br>
                        Welke kies je?
                    </p>
                </div>
            </div>
            <div class="section-half">

            </div>
        </section>
        <?php if ($isAdmin && isset($_GET['photo_error'])): ?>
            <p class="form-error">Geen enkele foto kon worden geüpload. Controleer of het een geldig afbeeldingsbestand is.</p>
        <?php endif; ?>
        <section class="section-rooms">
            <div class="rooms-grid">
                <?php displayRoomsCustomer(dbConnect()); ?>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/script.js"></script>
</body>