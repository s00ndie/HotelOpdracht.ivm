<?php
include 'includes/function.php';
require_once __DIR__ . '/src/Exception.php';
require_once __DIR__ . '/src/PHPMailer.php';
require_once __DIR__ . '/src/SMTP.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="reserveren">
    <?php include 'includes/header.php'; ?>
    <main>
        <section class="section-rooms">
            <article class='room corection '>

                <?php
                if (!isset($_GET['room_id'])) {
                    echo "<h1>Geen kamer geselecteerd.</h1>";
                    exit();
                } else {
                    kamerReserveren(dbConnect(), $_GET['room_id']);

                }

                ?>
            </article>
        </section>

        <?php
        if (isset($_GET['room_id']) && $_GET['room_id'] !== '') {
            echo "<form method='POST'>";
            echo "<div class='reserv-form'>";
            echo "<h1 class='reser-h'>Wilt u een reservering maken?</h1>";
            echo "<h2 class='reser-h'>Vul de volgende gegevens in</h2>";
            echo "<div class='form-columns'>";
            echo "<div class='persons'>";
            echo "<label for='name'>Uw naam:</label><input class='text-field' type='text' name='name' id='name' required>";
            echo "<label for='telephone'>Uw mobiel nummer:</label><input class='text-field' type='tel' name='telephone' id='telephone' required>";
            echo "<label for='email'>Uw e-mail adress:</label><input class='text-field' type='email' name='email' id='email' required>";
            echo "</div>";
            echo "<div class='booking-info'>";
            echo "<div class='persons'><label for='personen'>Aantal personen</label><input type='number' name='personen' min='1' max='10' required></div>";
            echo "<div class='date-fields'>";
            echo "<div class='date-group'><label for='check_in'>Incheckdatum</label><input type='date' id='check_in' name='check_in' min='" . date('Y-m-d') . "' required></div>";
            echo "<div class='date-group'><label for='check_out'>Uitcheckdatum</label><input type='date' id='check_out' name='check_out' min='" . date('Y-m-d', strtotime('+1 day')) . "' required></div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<input class='cta-button top-but' type='submit' value='Reserveren'>";
            echo "</div>";
            echo "</form>";


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $naam = $_POST["name"];
                $tel = $_POST["telephone"];
                $email = $_POST["email"];
                $check_in = $_POST["check_in"];
                $check_out = $_POST["check_out"];
                $aantal = $_POST["personen"];
                $room_id = $_GET["room_id"];
                /*echo $naam, ' ', $tel, ' ', $email, ' ', $check_in, ' ', $check_out, ' ', $aantal, ' ', $room_id;*/


                $kamer = getRoomByID(dbConnect(), $room_id);
                $kamertype = $kamer['type'];

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'valleizonne@gmail.com';
                $mail->Password = 'omdj nudw mhst yvua';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('valleizonne@gmail.com', 'Hotel De Zonne Vallei');

                $mail->addAddress($email, $naam);

                $mail->isHTML(true);
                $mail->Subject = 'Bevestiging van uw reservering';

                $mail->Body = "
                <h1>Bedankt voor uw reservering, $naam!</h1>
                <p>Wij hebben uw reservering ontvangen.</p>

                <h2>Uw reservering:</h2>
                <p>Type kamer gereserveerd: $kamertype</p>
                <p>Check-in: $check_in</p>
                <p>Check-out: $check_out</p>
                <p>Aantal personen: $aantal</p>
                <p>Met vrindelijke groet </p>
                <p>De Zonne Vallei Hotel</p>
                
                ";

                $mail->send();
                /*echo "Email is verzonden!";*/
            }




        }

        ?>

    </main>
    <?php include 'includes/footer.php'; ?>
    <script>
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');

        checkIn.addEventListener('change', function () {
            // Беремо вибрану дату заїзду
            const selectedDate = new Date(this.value);

            // Додаємо 1 день
            selectedDate.setDate(selectedDate.getDate() + 1);

            // Перетворюємо дату у формат YYYY-MM-DD
            const minDate = selectedDate.toISOString().split('T')[0];

            // Встановлюємо мінімальну дату виїзду
            checkOut.min = minDate;

            // Якщо дата виїзду менша — змінюємо її
            if (checkOut.value && checkOut.value < minDate) {
                checkOut.value = minDate;
            }
        });
    </script>
</body>

</html>