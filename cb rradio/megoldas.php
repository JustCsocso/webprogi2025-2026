<?php
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$adatok = $input['adatok'] ?? [];
$keresettSofor = $input['keresettSofor'] ?? '';

$bejegyzesekSzama = count($adatok);

$van4Adas = false;
foreach ($adatok as $a) {
    if ($a['adasDb'] === 4) { $van4Adas = true; break; }
}

$soforLetezik = false;
$soforAdasSzam = 0;

if ($keresettSofor !== '') {
    foreach ($adatok as $a) {
        if (strcasecmp($a['nev'], $keresettSofor) === 0) {
            $soforLetezik = true;
            $soforAdasSzam += $a['adasDb'];
        }
    }
}

$soforok = [];
foreach ($adatok as $a) { $soforok[$a['nev']] = true; }
$soforokSzama = count($soforok);

$osszAdasok = [];
foreach ($adatok as $a) {
    if (!isset($osszAdasok[$a['nev']])) $osszAdasok[$a['nev']] = 0;
    $osszAdasok[$a['nev']] += $a['adasDb'];
}
$legtobbAdas = 0;
$legtobbSofor = '';
foreach ($osszAdasok as $nev => $db) {
    if ($db > $legtobbAdas) {
        $legtobbAdas = $db;
        $legtobbSofor = $nev;
    }
}

echo json_encode([
    'bejegyzesekSzama'=>$bejegyzesekSzama,
    'van4Adas'=>$van4Adas,
    'soforLetezik'=>$soforLetezik,
    'soforAdasSzam'=>$soforAdasSzam,
    'soforokSzama'=>$soforokSzama,
    'legtobbSofor'=>$legtobbSofor,
    'legtobbAdas'=>$legtobbAdas
]);
