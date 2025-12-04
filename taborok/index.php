<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if(!isset($_SESSION["adatok"])){
        $olvas = file("taborok.txt");
        $adatok = [];
        
        foreach($olvas as $sor){  // JAVÍTVA: $olvas as $sor
            $sor = trim($sor);
            if(!empty($sor)){
                $darabok = explode("\t", $sor);
                if(count($darabok) == 6){
                    $adatok[] = [
                        "kezdho" => (int)$darabok[0],      // JAVÍTVA: (int)
                        "kezdnap" => (int)$darabok[1],
                        "vegho" => (int)$darabok[2],
                        "vegnap" => (int)$darabok[3],
                        "azonosito" => $darabok[4],
                        "erdeklodes" => $darabok[5]
                    ];  // JAVÍTVA: pontosvessző hozzáadva
                }
            }
        }
        $_SESSION["adatok"] = $adatok;
    }
    
    $adatok = $_SESSION["adatok"];
    $data = json_decode(file_get_contents("php://input"), true);
    $feladat = $data["feladat"] ?? "";
    
    if($feladat == "2"){
        $osszes = count($adatok);
        echo json_encode(["kiir" => $osszes]);  // JAVÍTVA: "kiir" kulcs
    }
    
    exit;  // Fontos, hogy ne jelenjen meg HTML a válasz után
}
?>
