<?php
session_start();
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
        <section class="hero">
            <?php if (isset($_SESSION['name'])) { ?>
                <p>U ben ingelogd als <?php echo $_SESSION['name']; ?>. </p><a href="includes/logout.php">logout</a>
            <?php } ?>
            <h1 class="section-title">De Zonne Vallei</h1>
            <a class="cta-button" href="kamers.php">Bekijk onze kamers</a>
        </section>
        <section class="section-split">
            <div class="section-half">
                <span>
                    <h1>Welkom bij Hotel De Zonne Vallei</h1>
                    <p>Ontsnap aan de dagelijkse drukte en ontdek de rust en luxe van Hotel De Zonne Vallei, een 3-duimen hotel gelegen in het hart van Alkmaar. Ons hotel biedt een perfecte mix van comfort, gastvrijheid en adembenemende natuur. Of u nu voor een romantisch uitje, een familievakantie of een zakelijke bijeenkomst komt, ons hotel heeft precies wat u nodig heeft voor een onvergetelijk verblijf.</p>
                </span>
                <span>
                    <h2>Ontdek Onze Kamers</h2>
                    <p>Of u nu op zoek bent naar een romantisch uitje, een familievakantie of een zakelijke bijeenkomst, onze stijlvolle en goed uitgeruste kamers bieden alles wat u nodig heeft voor een onvergetelijk verblijf. Geniet van moderne voorzieningen, comfortabele bedden en een prachtig uitzicht op de omgeving.</p>
                </span>
                <span class="deco-line"></span>
            </div>
            <div class="section-half">
                <div class="img-container">
                    <img src="assets/ivm-receptie.jpg" alt="foto van hotel">
                </div>
            </div>
        </section>
        <section class="section-index-rooms">
            <div class="section-half">
                <a class="img-container-btn" href="kamer.php#kamer" style="background-image: url('assets/ivm-deluxekamer.jpg');" aria-label="deluxekamer">
                    <h3>Kamer</h3>
                </a>
            </div>
            <div class="section-half">
                <a class="img-container-btn" href="kamer.php#suite" style="background-image: url('assets/ivm-familiesuite.jpg');" aria-label="familiesuite">
                    <h3>Suite</h3>
                </a>
                <a class="img-container-btn" href="kamer.php#bruidssuite" style="background-image: url('assets/ivm-bruidssuite.jpg');" aria-label="bruidssuite">
                    <h3>Speciale gelegenheid</h3>
                </a>
            </div>
        </section>
        <section class="section-split">
            <div class="section-half">
                <div class="img-container">
                    <img src="assets/ivm-restaurant.jpg" alt="foto van hotel">
                </div>
            </div>
            <div class="section-half">
                <span>
                    <h1>Culinaire Verwennerij</h1>
                    <p>Laat uw smaakpapillen prikkelen in ons restaurant, waar onze chef-kok met passie lokale ingrediënten omtovert tot culinaire meesterwerken. Van een uitgebreid ontbijt tot een intiem diner, elke maaltijd is een ervaring op zich. Ontdek ons menu en geniet van de smaken van Alkmaar.</p>
                </span>
                <span>
                    <a class="cta-button" href="restaurant.php">Ontdek het restaurant!</a>
                </span>
                <span class="deco-line"></span>
            </div>
        </section>
        <section class="section-surroundings">
            <a class="img-container-btn surroundings-card" href="https://inalkmaar.nl" target="_blank" rel="noopener noreferrer" style="background-image: url('assets/ivm-alkmaar-kaasmarkt.jpg');" aria-label="Ontdek Alkmaar - officiële toeristeninformatie">
                <div class="surroundings-content">
                    <h2>Ontdek de Omgeving</h2>
                    <span class="deco-line"></span>
                    <p>Hotel De Zonne Vallei ligt in het bruisende hart van Alkmaar, een stad die rijk is aan geschiedenis en cultuur. Verken de pittoreske straatjes, bewonder de eeuwenoude architectuur en bezoek de wereldberoemde kaasmarkt. Of u nu winkelt in de boetiekjes, geniet van een drankje op een van de vele terrassen of een ontspannen wandeling maakt langs de grachten, Alkmaar biedt voor ieder wat wils. Ontdek de schoonheid en charme van deze historische stad tijdens uw verblijf in ons hotel.</p>
                </div>
            </a>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="assets/script.js"></script>
</body>

</html>