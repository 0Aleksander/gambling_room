<?php
session_start();

// Če so podatki iz obrazca poslani
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['users'] = [];

    for ($i = 1; $i <= 3; $i++) {
        $user = [
            'ime' => $_POST["ime$i"],
            'priimek' => $_POST["priimek$i"],
            'naslov' => $_POST["naslov$i"],
            'kocke' => [
                rand(1, 6),
                rand(1, 6),
                rand(1, 6)
            ]
        ];
        $_SESSION['users'][] = $user;
    }

    // Preusmeritev na rezultate
    header("Location: ?rezultati=1");
    exit();
}

// Če je zahtevano prikazovanje rezultatov
if (isset($_GET['rezultati']) && isset($_SESSION['users'])) {
    $users = $_SESSION['users'];

    echo "<h1>Rezultati igre s kockami</h1>";

    $max = 0;
    $zmagovalci = [];

    foreach ($users as $index => $user) {
        $vsota = array_sum($user['kocke']);
        if ($vsota > $max) {
            $max = $vsota;
            $zmagovalci = [$user['ime'] . " " . $user['priimek']];
        } elseif ($vsota === $max) {
            $zmagovalci[] = $user['ime'] . " " . $user['priimek'];
        }

        echo "<div style='margin-bottom:20px;'>";
        echo "<strong>Uporabnik " . ($index + 1) . ":</strong><br>";
        echo "Ime: " . htmlspecialchars($user['ime']) . "<br>";
        echo "Priimek: " . htmlspecialchars($user['priimek']) . "<br>";
        echo "Naslov: " . htmlspecialchars($user['naslov']) . "<br>";
        echo "Kocke: ";
        foreach ($user['kocke'] as $kocka) {
            echo "<img src='images/dice$kocka.png' width='50' height='50' alt='Kocka $kocka'>";
        }
        echo "<br>Vsota: $vsota";
        echo "</div>";
    }

    echo "<h2>Zmagovalec/i:</h2>";
    echo implode(", ", $zmagovalci);

    echo "<script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 10000);
    </script>";

    // Po prikazu rezultatov počistimo sejo
    session_destroy();

    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Igra s kockami</title>
</head>
<body>
    <h1>Vnesite podatke za 3 uporabnike</h1>
    <form method="post" action="">
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <fieldset style="margin-bottom:20px;">
                <legend>Uporabnik <?= $i ?></legend>
                Ime: <input type="text" name="ime<?= $i ?>" required><br>
                Priimek: <input type="text" name="priimek<?= $i ?>" required><br>
                Naslov: <input type="text" name="naslov<?= $i ?>" required><br>
            </fieldset>
        <?php endfor; ?>
        <input type="submit" value="Začni igro">
    </form>
</body>
</html>
