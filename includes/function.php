<?php
session_start();
function dbConnect()
{
    //mysql gegevens
    $servername = "localhost";
    $database = "hotelopdracht";
    $dsn = "mysql:host=$servername;dbname=$database";
    $gebruikername = "root";
    $wachtwoord = "";
    //database aanmaken met pdo functie
    $conn = new PDO($dsn, $gebruikername, $wachtwoord);
    //geeft de connectie terug
    return $conn;
}

function login($conn)
{
    // kijkt of de submit knop is ingeklikt
    if (isset($_POST['submit'])) {

        // kijkt of de name en wachtwoord zijn ingevuld en daarna vult hij ze in variabelen
        if (isset($_POST['name']) && isset($_POST['password'])) {
            $name = $_POST['name'];
            $wachtwoord = $_POST['password'];

            // try catch voor pdo
            try {
                $stmt = $conn->prepare("SELECT * FROM admin WHERE name = :name");
                $stmt->bindParam(':name', $name);
                $stmt->execute();
                $name = $stmt->fetch(PDO::FETCH_ASSOC);

                // als die de gebruiker kan ophalen uit de database en als de statement niet false is dan voert die de volgende code uit
                if ($name !== false) {

                    // kijkt of het wachtwoord gelijk is als het wachtwoord in de database
                    if (password_verify($wachtwoord, $name['password'])) {
                        echo "Wachtwoord klopt";

                        // sla de naam op in de sessie
                        $_SESSION['name'] = $name['name'];

                        // Redirect naar index.php na succesvolle login
                        echo "<script>window.location.href='index.php';</script>";
                        exit();
                    } else {
                        echo "Verkeerd wachtwoord";
                    }
                } else {
                    echo "Gebruiker niet gevonden";
                }
            } catch (PDOException $e) {
                echo "Fout: " . $e->getMessage();
            }
        } else {
            echo "name en wachtwoord moeten worden ingevuld";
        }
    }
}

function getRooms($conn)
{
    try {
        $stmt = $conn->prepare("SELECT * FROM rooms");
        $stmt->execute();
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rooms;
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        return [];
    }
}

function getRoomById($conn, $roomId)
{
    try {
        $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = :id");
        $stmt->bindParam(':id', $roomId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        return false;
    }
}

function getRoomPhotos($conn, $roomId)
{
    try {
        $stmt = $conn->prepare("SELECT * FROM room_photos WHERE room_id = :room_id");
        $stmt->bindParam(':room_id', $roomId);
        $stmt->execute();
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $photos;
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        return [];
    }
}

function displayRoomsCustomer($conn)
{
    $isAdmin = isset($_SESSION['name']) && !empty($_SESSION['name']);
    $rooms = getRooms($conn);

    if ($isAdmin) {
        echo "<article class='room new-room-card'>";

        echo "<div class='room-info'>";
        echo "<div class='room-view'>";
        echo "<h2>Nieuwe kamer</h2>";
        echo "<span class='deco-line'></span>";
        echo "<p class='room-description'>Voeg een nieuwe kamer of appartement toe aan het overzicht.</p>";
        echo "<button type='button' class='edit-toggle-btn'>+ Kamer toevoegen</button>";
        echo "</div>";
        echo "</div>";

        echo "<form method='POST' class='new-room-form' enctype='multipart/form-data'>";

        echo "<div class='room-photos'>";
        echo "<div class='photo-edit-list'>";
        echo "<ul class='photo-list'></ul>";
        echo "<label for='new-photos'>Foto's</label>";
        echo "<input type='file' id='new-photos' name='photos[]' accept='image/*' multiple>";
        echo "</div>";
        echo "</div>";

        echo "<div class='room-info'>";
        echo "<div class='room-edit-form'>";
        echo "<input type='hidden' name='create_room' value='1'>";
        echo "<label for='new-type'>Kamer type</label>";
        echo "<input type='text' id='new-type' name='type' placeholder='Bijv. Comfort Kamer' required>";
        echo "<label for='new-description'>Beschrijving</label>";
        echo "<textarea id='new-description' name='description' placeholder='Beschrijving van de kamer'></textarea>";
        echo "<label for='new-price'>Prijs per nacht (&euro;)</label>";
        echo "<input type='number' step='0.01' id='new-price' name='price' placeholder='Bijv. 99.00' required>";
        echo "<div class='edit-form-actions'>";
        echo "<button type='submit' class='cta-button'>Aanmaken</button>";
        echo "<button type='button' class='cancel-edit-btn'>Annuleren</button>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

        echo "</form>";

        echo "</article>";
    }

    $displayedRooms = [];
    foreach ($rooms as $room) {
        if (in_array($room['type'], $displayedRooms)) {
            continue; // Skip if the room type has already been displayed
        }
        $displayedRooms[] = $room['type'];
        echo "<article class='room' id='" . htmlspecialchars($room['type']) . "'>";

        $photos = getRoomPhotos($conn, $room['id']);
        if (!empty($photos) || $isAdmin) {
            $photoCount = count($photos);
            echo "<div class='room-photos'>";

            if (!empty($photos)) {
                echo "<div class='carousel'>";
                echo "<div class='carousel-track'>";
                foreach ($photos as $photo) {
                    echo "<div class='carousel-slide'><img src='" . htmlspecialchars($photo['path']) . "' alt='" . htmlspecialchars($room['type']) . "'></div>";
                }
                echo "</div>";

                if ($photoCount > 1) {
                    echo "<button type='button' class='carousel-btn prev' aria-label='Vorige foto'>&#10094;</button>";
                    echo "<button type='button' class='carousel-btn next' aria-label='Volgende foto'>&#10095;</button>";
                    echo "<div class='carousel-dots'>";
                    for ($i = 0; $i < $photoCount; $i++) {
                        $active = $i === 0 ? ' active' : '';
                        echo "<span class='carousel-dot$active' data-index='$i'></span>";
                    }
                    echo "</div>";
                }

                echo "</div>";
            }

            if ($isAdmin) {
                echo "<div class='photo-edit-list'>";
                echo "<ul class='photo-list'>";
                foreach ($photos as $photo) {
                    $filename = !empty($photo['filename']) ? $photo['filename'] : basename($photo['path']);
                    echo "<li class='photo-list-item'>";
                    echo "<span class='photo-filename'>" . htmlspecialchars($filename) . "</span>";
                    echo "<form method='POST' class='delete-photo-form'>";
                    echo "<input type='hidden' name='delete_photo_id' value='" . htmlspecialchars($photo['id']) . "'>";
                    echo "<button type='submit' class='delete-photo-btn' aria-label='Foto verwijderen'>&times;</button>";
                    echo "</form>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "<form method='POST' enctype='multipart/form-data' class='add-photo-form'>";
                echo "<input type='hidden' name='add_photo_room_id' value='" . htmlspecialchars($room['id']) . "'>";
                echo "<label for='photos-" . htmlspecialchars($room['id']) . "'>Foto's</label>";
                echo "<input type='file' id='photos-" . htmlspecialchars($room['id']) . "' name='photos[]' accept='image/*' multiple>";
                echo "<button type='submit' class='add-photo-btn'>Foto's toevoegen</button>";
                echo "</form>";
                echo "</div>";
            }

            echo "</div>";
        }

        echo "<div class='room-info'>";

        echo "<div class='room-view'>";
        echo "<h2>" . htmlspecialchars($room['type']) . "</h2>";
        echo "<span class='deco-line'></span>";
        echo "<p class='room-description'>" . htmlspecialchars($room['description']) . "</p>";
        echo "<div class='room-price-container'>";
        echo "<p class='room-price'>Vanaf &euro;" . htmlspecialchars($room['price']) . " <span>per nacht</span></p>";
        echo "<a class='cta-button' href='reserveren.php?room_id=" . htmlspecialchars($room['id']) . "'>Boek nu!</a>";
        echo "</div>";
        if ($isAdmin) {
            echo "<button type='button' class='edit-toggle-btn'>Bewerken</button>";
        }
        echo "</div>";

        if ($isAdmin) {
            echo "<form method='POST' class='room-edit-form'>";
            echo "<input type='hidden' name='id' value='" . htmlspecialchars($room['id']) . "'>";
            echo "<label for='type-" . htmlspecialchars($room['id']) . "'>Kamer type</label>";
            echo "<input type='text' id='type-" . htmlspecialchars($room['id']) . "' name='type' value='" . htmlspecialchars($room['type']) . "'>";
            echo "<label for='description-" . htmlspecialchars($room['id']) . "'>Beschrijving</label>";
            echo "<textarea id='description-" . htmlspecialchars($room['id']) . "' name='description'>" . htmlspecialchars($room['description']) . "</textarea>";
            echo "<label for='price-" . htmlspecialchars($room['id']) . "'>Prijs per nacht (&euro;)</label>";
            echo "<input type='number' step='0.01' id='price-" . htmlspecialchars($room['id']) . "' name='price' value='" . htmlspecialchars($room['price']) . "'>";
            echo "<div class='edit-form-actions'>";
            echo "<button type='submit' class='cta-button'>Opslaan</button>";
            echo "<button type='button' class='cancel-edit-btn'>Annuleren</button>";
            echo "<button type='submit' name='delete_room' value='1' class='delete-room-btn'>Kamer verwijderen</button>";
            echo "</div>";
            echo "</form>";
        }

        echo "</div>";

        echo "</article>";
    }
}

function addRoomPhoto($conn, $roomId, $tmpPath, $originalFilename, $mimeType)
{
    $extensionByMimeType = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        'image/bmp' => 'bmp',
        'image/heic' => 'heic',
        'image/heif' => 'heif',
        'image/avif' => 'avif',
        'image/svg+xml' => 'svg',
    ];
    $extension = $extensionByMimeType[$mimeType] ?? null;
    if ($extension === null) {
        return false;
    }

    $diskFilename = uniqid('room' . $roomId . '_', true) . '.' . $extension;
    $destPath = __DIR__ . '/../images/rooms/' . $diskFilename;

    if (!move_uploaded_file($tmpPath, $destPath)) {
        return false;
    }

    $relativePath = 'images/rooms/' . $diskFilename;

    try {
        $stmt = $conn->prepare("INSERT INTO room_photos (room_id, filename, path) VALUES (:room_id, :filename, :path)");
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':filename', $originalFilename);
        $stmt->bindParam(':path', $relativePath);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        unlink($destPath);
        return false;
    }
}

function uploadRoomPhotos($conn, $roomId, $filesArray)
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $uploadedCount = 0;
    $skippedCount = 0;
    foreach ($filesArray['tmp_name'] as $i => $tmpPath) {
        if ($filesArray['error'][$i] === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($filesArray['error'][$i] !== UPLOAD_ERR_OK) {
            $skippedCount++;
            continue;
        }
        $mimeType = finfo_file($finfo, $tmpPath);
        if (strpos($mimeType, 'image/') !== 0) {
            $skippedCount++;
            continue;
        }
        $filename = substr(basename($filesArray['name'][$i]), 0, 255);
        if (addRoomPhoto($conn, $roomId, $tmpPath, $filename, $mimeType)) {
            $uploadedCount++;
        } else {
            $skippedCount++;
        }
    }
    finfo_close($finfo);
    return ['uploaded' => $uploadedCount, 'skipped' => $skippedCount];
}

function deleteRoomPhoto($conn, $photoId)
{
    try {
        $stmt = $conn->prepare("SELECT path FROM room_photos WHERE id = :id");
        $stmt->bindParam(':id', $photoId);
        $stmt->execute();
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("DELETE FROM room_photos WHERE id = :id");
        $stmt->bindParam(':id', $photoId);
        $result = $stmt->execute();

        if ($result && $photo) {
            $fullPath = __DIR__ . '/../' . $photo['path'];
            if (is_file($fullPath)) {
                unlink($fullPath);
            }
        }

        return $result;
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        return false;
    }
}

function deleteRoom($conn, $roomId)
{
    try {
        $stmt = $conn->prepare("SELECT path FROM room_photos WHERE room_id = :room_id");
        $stmt->bindParam(':room_id', $roomId);
        $stmt->execute();
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("DELETE FROM rooms WHERE id = :id");
        $stmt->bindParam(':id', $roomId);
        $result = $stmt->execute();

        if ($result) {
            foreach ($photos as $photo) {
                $fullPath = __DIR__ . '/../' . $photo['path'];
                if (is_file($fullPath)) {
                    unlink($fullPath);
                }
            }
        }

        return $result;
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        return false;
    }
}

function createRoom($conn, $type, $description, $price)
{
    try {
        $stmt = $conn->prepare("INSERT INTO rooms (type, description, price) VALUES (:type, :description, :price)");
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->execute();
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        return false;
    }
}

function updateRoom($conn, $roomId, $type, $description, $price)
{
    try {
        $stmt = $conn->prepare("UPDATE rooms SET type = :type, description = :description, price = :price WHERE id = :id");
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':id', $roomId);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Fout: " . $e->getMessage();
        return false;
    }
}

function kamerReserveren($conn, $roomID)
{

    $room = getRoomById($conn, $roomID);
    if ($room === false) {
        echo "<h1>Kamer niet gevonden.</h1>";
        return;
    }

    $photos = getRoomPhotos($conn, $room['id']);
    if (!empty($photos)) {
        foreach ($photos as $photo) {
            echo "<img class='reserverenig-img' src='" . htmlspecialchars($photo['path']) . "' alt='" . htmlspecialchars($room['type']) . "'>";
        }
    }

    echo "<div class='room-info'>";
    echo "<h2>" . htmlspecialchars($room['type']) . "</h2>";
    echo "<span class='deco-line'></span>";
    echo "<p class='room-description'>" . htmlspecialchars($room['description']) . "</p>";
    echo "<div class='room-price-container'>";
    echo "<p class='room-price'>Vanaf &euro;" . htmlspecialchars($room['price']) . " <span>per nacht</span></p>";
    echo "</div>";
    echo "</div>";
}