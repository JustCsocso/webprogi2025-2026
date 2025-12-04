<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$adatok = $input['adatok'] ?? [];



$osszdarab = count($adatok);

// Van-e 1 perc alatt 4 adás
$van4Adas = false;
foreach ($adatok as $s) {
    if ($s['perc'] === 1 && $s['adasDb'] === 4) {
        $van4Adas = true;
        break;
    }
}

$keresett = trim($input['keresett'] ?? "");

$talalatDb = 0;
if ($keresett !== "") {
    foreach ($adatok as $s) {
        if (isset($s["nev"]) && strtolower($s["nev"]) == strtolower($keresett)) {
            $talalatDb++;
        }
    }
}
function AtszamolPercre(int $ora, int $perc): int {
    return $ora * 60 + $perc;
}

// Példa használat:
$ora = 8;
$perc = 5;
$osszPerc = AtszamolPercre($ora, $perc);

$egyediSoforok = 0;

foreach ($adatok as $sor) {
    $nev = $sor["nev"];
    $egyediSoforok[$nev]++;
}

$soforDb = count($egyediSoforok);

echo "Sofőrök száma: $soforDb\n"; // 3

echo json_encode([
    'osszdarab' => $osszdarab,
    'van4Adas' => $van4Adas ? "Van 4 adás 1 perc alatt" : "Nincs 4 adás 1 perc alatt",
    'talalatDb' => $talalatDb,
    "osszperc" => $osszPerc,
    ""
]);
?>