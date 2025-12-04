<?php
$adatok = [];

$sorok = file("helsinki.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($sorok as $sor) {
    $darabok = explode(" ", $sor, 4);

    $adatok[] = [
        "hely" => (int)$darabok[0],
        "sportolok" => (int)$darabok[1],
        "sportag" => $darabok[2],
        "szam" => $darabok[3]
    ];
}

$ossz=count($adatok);

$arany=0;
$ezust=0;
$bronz=0;
foreach($adatok as $s){
    if($s["hely"]==1){
        $arany++;
    }
    elseif($s["hely"]==2){
        $ezust++;
    }
    elseif($s["hely"]==3){
        $bronz++;
    }
    
}
$osszesErem=$arany+$ezust+$bronz;

$osszhely=0;
foreach($adatok as $s){
    if($s["hely"]==1){
        $osszhely+=7;
    }
    elseif($s["hely"]==2){
        $osszhely+=5;
    }
    elseif($s["hely"]==3){
        $osszhely+=4;
    }
    elseif($s["hely"]==4){
        $osszhely+=3;
    }
    elseif($s["hely"]==5){
        $osszhely+=2;
    }
    elseif($s["hely"]==6){
        $osszhely+=1;
    }
}

$uszas=0;
$torna=0;
foreach($adatok as $s){
    if($s["sportag"]=="uszas"){
        $uszas++;
    }
    elseif($s["sportag"]=="torna"){
        $torna++;
    }
}
if($uszas>$torna){
    $kiir="Uszas volt tobb";
}
elseif($uszas<$torna){
    $kiir="Torna a tobb";
}
else{
    $kiir="Egyenlo";
}

foreach($adatok as $s){
    $maxsportolo=max($s["szam"]);
   // var_dump($maxsportolo);
    
}
echo json_encode([
    'ossz' => $ossz,
    'arany' => $arany,
    'ezust' => $ezust,
    'bronz' => $bronz,
    "osszesErem"=>$osszesErem,
    "osszhely"=>$osszhely,
    "kiir"=>$kiir

])
?>