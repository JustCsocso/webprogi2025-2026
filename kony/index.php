<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION["konyvek"])) {
        $olvas = file("kiadas.txt");
        $konyvek = [];
        
        foreach ($olvas as $sor) {
            $sor = trim($sor);
            if (!empty($sor)) {
                $reszek = explode(';', $sor);
                if (count($reszek) >= 5) {
                    $konyvek[] = [
                        "ev" => (int)$reszek[0],
                        "negyedev" => (int)$reszek[1],
                        "eredet" => $reszek[2],
                        "leiras" => $reszek[3],
                        "peldanyszam" => (int)$reszek[4]
                    ];
                }
            }
        }
        $_SESSION["konyvek"] = $konyvek;
    }
    
    $konyvek = $_SESSION["konyvek"];
    $data = json_decode(file_get_contents("php://input"), true);
    $feladat = $data["feladat"] ?? "";
    $szerzoNev = $data["szerzo"] ?? "";
    
    if ($feladat == "2") {
        $darab = 0;
        foreach ($konyvek as $konyv) {
            if (strpos($konyv["leiras"], $szerzoNev) !== false) {
                $darab++;
            }
        }
        
        if ($darab > 0) {
            echo json_encode(["kiir" => "$darab könyvkiadás"]);
        } else {
            echo json_encode(["kiir" => "Nem adtak ki"]);
        }
       
    }
    elseif($feladat=="3"){
         $peldanyszamok = array_column($konyvek, 'peldanyszam');
            
        // Megkeressük a maximumot
        $legnagyobb = max($peldanyszamok);
            
        // Megszámoljuk, hányszor fordul elő
        $elofordulas = array_count_values($peldanyszamok)[$legnagyobb];
            
        echo json_encode(["kiir" => "Legnagyobb példányszám: $legnagyobb, előfordult $elofordulas alkalommal"]);
    }
    elseif($feladat=="4"){
        foreach($konyvek as $as){
            if($as["eredet"]=="kf" && $as["peldanyszam"]>=40000){
                $kiir=$as["ev"]. "/". $as["negyedev"]." ". $as["leiras"]; 
                echo json_encode(["kiir"=>$kiir]);          
            }
            
        }
    }
     exit;
    
}
?>