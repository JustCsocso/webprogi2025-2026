<?php
session_start();

if($_SERVER["REQUEST_METHOD"]=="POST") {

    if(!isset($_SESSION["adatok"])) {
        $olvas = file("tancrend.txt");
        $adatok = [];
        $darabok = [];
        foreach($olvas as $s) {
            $darabok[] = $s;
            if(count($darabok) == 3) {
                $adatok[] = [
                    "tanc"=>$darabok[0], 
                    "lany"=>$darabok[1], 
                    "fiu"=>$darabok[2]];
                $darabok = [];
            }
        }
        $_SESSION["adatok"] = $adatok;
    }

    $adatok = $_SESSION["adatok"];
    $data = json_decode(file_get_contents("php://input"), true);
    $feladat = $data["feladat"] ?? "";
    $tancNev=$data["tancNeve"] ?? "";
    
    if($feladat=="2"){
        $elsoTanc = $adatok[0]["tanc"];
        $utso = $adatok[count($adatok)-1]["tanc"];
        echo json_encode(["elso"=>$elsoTanc, "utso"=>$utso]);

    }
   
    elseif($feladat == "3") {
        $ossz = 0;
        foreach($adatok as $e) {
            if($e["tanc"] == "samba") $ossz++;
        }
        echo json_encode(["ossz" => $ossz]);
    }
    elseif($feladat=="4"){
        $vilamTancai="";
        foreach($adatok as $s){
            if($s["lany"]=="Vilma"){
                $vilamTancai.=$s["tanc"];
            }
        }
        echo json_encode(["vilma"=>$vilamTancai]);
    }
    elseif ($feladat == "5") {
    $kiir = "Vilma nem táncolt ".$tancNev."-t.";
    foreach ($adatok as $s) {
        if ($s["lany"]== "Vilma" && $s["tanc"] == $tancNev) {
            $kiir = "A ".$tancNev." bemutatóján Vilma párja ".$s["fiu"]." volt.";
            break;
        }
    }
    echo json_encode(["kiir" => $kiir]);
}


    exit;
}
?>
