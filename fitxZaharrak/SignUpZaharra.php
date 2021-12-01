<!DOCTYPE html>
<html>
<head>
    <?php include '../html/Head.html'?>
    <?php include 'DbConfig.php'?>
    <script src="../js/ShowImageInForm.js"></script>

    <?php
    function balidatu($datuak){
        if ($datuak["erabiltzailemota"]==0){
            return 'Mota okerra';
        }
        else if (!preg_match("/^(([a-zA-Z]+[0-9]{3}@ikasle\.ehu\.(eus|es))|([a-zA-Z]+\.[a-zA-Z]+@ehu\.(eus|es)|[a-zA-Z]+@ehu\.(eus|es)))$/i", $datuak["eposta"])){
            return 'Eposta okerra';
        }
        else if (!preg_match("/^[a-zA-ZÀ-ÿ]{2,}(\s+[a-zA-ZÀ-ÿ]{2,})+$/", $datuak["deitura"])){
            return 'Deitura okerra';
        }
        else if (strlen($datuak["pasahitza"]) < 8){
            return 'Pasahitz luzera okerra (gutxienez 8 karaktere)';
        }
        else if ($datuak["pasahitza"] != $datuak["pasahitzaErr"]){
            return 'Bi pasahitzak ez dira berdinak';
        }
        $result = include "ClientVerifyEnrollment.php";
        return $result;    
    }
    ?>
    
</head>
<body>
    <?php include '../php/Menus.php' ?>

    <section class="main" id="SingUp">
        <div>
            <form id="singupF" name="singupF" method="post" onreset="hide_image()"
                  enctype="multipart/form-data">
                <!-- Erabiltzaile mota -->
                <label for="erabiltzailemota">Erabiltzaile mota (*):</label><br/>

                <input type="radio" id="erabiltzailemotaIkaslea" name="erabiltzailemota" value="1">
                <label for="erabiltzailemotaIkaslea">Ikaslea</label>

                <input type="radio" id="erabiltzailemotaIrakaslea" name="erabiltzailemota" value="2">
                <label for="erabiltzailemotaIrakaslea">Irakaslea</label><br/>

                <!-- Eposta -->
                <label for="eposta">Eposta (*):</label>
                <input type="text" id="eposta" name="eposta"><br>

                <!-- Deitura -->
                <label for="deitura">Deitura (*):</label>
                <input type="text" id="deitura" name="deitura"><br>

                <!-- Pasahitza -->
                <label for="pasahitza">Pasahitza (*):</label>
                <input type="password" id="pasahitza" name="pasahitza"><br>

                <!-- PasahitzaErrepikatua -->
                <label for="pasahitzaErr">Pasahitza errepikatu (*):</label>
                <input type="password" id="pasahitzaErr" name="pasahitzaErr"><br>

                <!-- Irudia -->
                <label>Irudia:</label>
                <input type="file" accept="image/*" name="irudia" id="irudia" onchange="show_image(this, 'reset')"><br>

                <!-- Hustu -->
                <input type="reset" name="reset" id="reset"  value="Hustu">

                <!-- Galdera igorri -->
                <input type="submit" name="submit" id="submit" value="Erregistratu"><br>
            </form>
        </div>
    </section>
    <?php include '../html/Footer.html' ?>
</body>
</html>

<?php
include "DbConfig.php";
if (isset($_POST['eposta'])) {
    if (($arazoa = balidatu($_POST)) != ''){
        echo "<script> alert('Arazo bat egon da: $arazoa')</script>";
        return;
    }

    $dir = "";
    if ($_FILES["irudia"]["tmp_name"] != "") {
        $irudia = file_get_contents($_FILES["irudia"]["tmp_name"]);
        $izena = explode(".", $_FILES['irudia']['name']);
        $dir = "../images/erabiltzaileak/" . str_replace("@", ".", $_POST["eposta"]) .".". $izena[sizeof($izena)-1];
        if (!empty($irudia)){
            file_put_contents($dir, $irudia);
        }
    }

    global $zerbitzaria, $erabiltzailea, $gakoa, $db;
    $nireSQLI = new mysqli($zerbitzaria, $erabiltzailea, $gakoa, $db);

    if($nireSQLI->connect_error) {
        echo "<script> alert('DB-ra konexio bat egitean errore bat egon da. Berriro saiatu.')";
        return;
    }

    $irudia = "";
    if ($_FILES["irudia"]["tmp_name"] != "") {
        $irudiaIzen = $_FILES["irudia"]["tmp_name"];
        $irudia = addslashes(file_get_contents($irudiaIzen));
    }

    $encrypted_pwd = crypt($_POST["pasahitza"]);

    $sqlInsertQuestion = "INSERT INTO Erabiltzaileak(eposta, mota, deitura, pasahitza, irudia, irudia_dir) 
                            VALUES ('$_POST[eposta]', '$_POST[erabiltzailemota]', '$_POST[deitura]', '$encrypted_pwd', '$irudia', '$dir')";

    if (!$nireSQLI->query($sqlInsertQuestion)) {
        $mezua = str_replace("'", "\'", $nireSQLI->error);
        echo "<script>alert('Errorea datu-basean: $mezua')</script>";
        return;
    }
    header("location: Layout.php");
}
?>
