<?php
session_start();

// Session tömb inicializálása
if (!isset($_SESSION['domains'])) {
    $_SESSION['domains'] = array();
}

if (isset($_POST['sub'])) {
    $email = $_POST['email'];
    $ex = explode("@", $email);
    if (count($ex) == 2) {
        $domain = $ex[1];
        // Domain eltárolása
        if (!isset($_SESSION['domains'][$domain])) {
            $_SESSION['domains'][$domain] = 1;
        } else {
            $_SESSION['domains'][$domain]++;
        }
        echo "<b>Beküldött email:</b> " . htmlspecialchars($email) . "<br>";
        echo "<b>Domain:</b> " . htmlspecialchars($domain) . "<br>";
    } else {
        echo "<span style='color:red'>Hibás email cím!</span>";
    }
}

// Kiírás: melyik domain hányszor szerepelt
if (!empty($_SESSION['domains'])) {
    echo "<h3>Domain statisztika:</h3><ul>";
    foreach ($_SESSION['domains'] as $dom => $count) {
        echo "<li>" . htmlspecialchars($dom) . ": " . $count . " db</li>";
    }
    echo "</ul>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

    <form action="email.php" method="post">
        <input type="email" name="email" id="email" required>
        <input type="submit" name="sub" value="Küldés">
    </form>

    // ...existing code...
    ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>