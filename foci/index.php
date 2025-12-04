<?php
session_start();

if(!isset($_SESSION["adatok"])) {
    // Beolvasás
    $lines = file("meccs.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $meccs_szam = intval(array_shift($lines)); // első sor: mérkőzések száma
    $adatok = [];

    foreach($lines as $line){
        $parts = explode(" ", $line);
        if(count($parts) == 7){
            $adatok[] = [
                "fordulo" => intval($parts[0]),
                "hazaiveg" => intval($parts[1]),
                "vendegveg" => intval($parts[2]),
                "hazaifel" => intval($parts[3]),
                "vendegfel" => intval($parts[4]),
                "hazaicsapat" => $parts[5],
                "vendegcsapat" => $parts[6],
            ];
        }
    }

    $_SESSION["adatok"] = $adatok;
}

$adatok = $_SESSION["adatok"];
$data = json_decode(file_get_contents("php://input"), true);
$feladat = $data["feladat"] ?? "";

header('Content-Type: application/json');

if($feladat == "2") {
    $fordulo = intval($data["fordulo"] ?? 0);
    $eredmenyek = [];
    foreach($adatok as $s){
        if($s["fordulo"] == $fordulo){
            $eredmenyek[] = sprintf("%-20s-%-20s: %d-%d (%d-%d)",
                $s["hazaicsapat"], $s["vendegcsapat"],
                $s["hazaiveg"], $s["vendegveg"],
                $s["hazaifel"], $s["vendegfel"]
            );
        }
    }
    if(empty($eredmenyek)){
        $eredmenyek[] = "Nincs ilyen forduló.";
    }
    echo json_encode(["kiir"=>implode("\n", $eredmenyek)]);
}

elseif($feladat == "3") {
    $fordulasok = [];
    foreach($adatok as $s){
        if($s["hazaifel"] < $s["vendegfel"] && $s["hazaiveg"] > $s["vendegveg"]){
            $fordulasok[] = $s["fordulo"]." ".$s["hazaicsapat"];
        }
        elseif($s["vendegfel"] < $s["hazaifel"] && $s["vendegveg"] > $s["hazaiveg"]){
            $fordulasok[] = $s["fordulo"]." ".$s["vendegcsapat"];
        }
    }
    echo json_encode(["kiir"=>implode("\n", $fordulasok)]);
}

elseif($feladat == "4") {
    $csapat = $data["csapat"] ?? "Lelkesek";
    $_SESSION["csapat"] = $csapat;
    echo json_encode(["kiir"=>"A kiválasztott csapat: ".$csapat]);
}

elseif($feladat == "5") {
    $csapat = $_SESSION["csapat"] ?? "Lelkesek";
    $lottek = 0;
    $kapottak = 0;
    foreach($adatok as $s){
        if($s["hazaicsapat"] == $csapat){
            $lottek += $s["hazaiveg"];
            $kapottak += $s["vendegveg"];
        }
        elseif($s["vendegcsapat"] == $csapat){
            $lottek += $s["vendegveg"];
            $kapottak += $s["hazaiveg"];
        }
    }
    echo json_encode(["kiir"=>"lőtt: $lottek kapott: $kapottak"]);
}

elseif($feladat == "6") {
    $csapat = $_SESSION["csapat"] ?? "Lelkesek";
    $talalat = false;
    foreach($adatok as $s){
        if($s["hazaicsapat"] == $csapat && $s["hazaiveg"] < $s["vendegveg"]){
            $kiir = "Először a ".$s["fordulo"].". fordulóban kaptak ki: ".$s["vendegcsapat"];
            $talalat = true;
            break;
        }
    }
    if(!$talalat) $kiir = "A csapat otthon veretlen maradt.";
    echo json_encode(["kiir"=>$kiir]);
}

elseif($feladat == "7") {
    $statisztika = [];
    foreach($adatok as $s){
        $a = $s["hazaiveg"];
        $b = $s["vendegveg"];
        if($a < $b) list($a,$b) = [$b,$a]; // nagyobb előre
        $kulcs = $a."-".$b;
        $statisztika[$kulcs] = ($statisztika[$kulcs] ?? 0) + 1;
    }
    // fájlba írás
    $out = "";
    foreach($statisztika as $eredmeny => $db){
        $out .= "$eredmeny: $db\n";
    }
    file_put_contents("stat.txt", $out);
    echo json_encode(["kiir"=>"Statisztika elkészült a stat.txt-ben"]);
}
?>
