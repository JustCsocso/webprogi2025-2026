<?php

if (isset($_GET["path"])) {
    $apiParts = explode("/", $_GET["path"]);
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "todo";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($apiParts[0] == "todo") {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $query = "SELECT id, szoveg, datum, vege FROM todolista";
            $result = $conn->query($query);
            $jsonTomb = [];
            while ($row = $result->fetch_assoc()) {
                $jsonTomb[] = $row;
            }
            $conn->close();
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
            $json = file_get_contents("php://input");
            $data = json_decode($json);
            //d($data);
            phpinfo(32);
        }
        $json = json_encode($jsonTomb);
        echo $json;
    }
} else {
     ?>
<h3>API help</h3>
<?php
} ?>