<!DOCTYPE html>
<html>
<head>
    <?php include '../html/Head.html'?>
    <?php include 'DbConfig.php'?>
    <script src="../js/ShowImageInForm.js"></script>

</head>
<body>
    <?php include '../php/Menus.php' ?>
    <?php 
    
        $GLOBALS['gaia'] = null;
        $GLOBALS['asmatuak'] = 0;
        $GLOBALS['akatsak'] = 0;   
    
    
    ?>

    <section class="main" id="SetUp">
        <div>
            <form id="gaiaAukeratu" name="gaiaAukeratu" method="post">
                <label for="gaiak">Gaiak (*):</label>
                <input type="submit" name="submit" id="submit" value="gBidali"><br>  
            </form>
        </div>
    </section>
    <?php 
        include "DbConfig.php";
        global $zerbitzaria, $erabiltzailea, $gakoa, $db;
            try {
                $dsn = "mysql:host=localhost;dbname=$db";
                $dbh = new PDO($dsn, $erabiltzailea, $gakoa);
                } catch (PDOException $e){
                alert("Errore bat gertatu da DB-ra konektatzerakoan: ");
                }
            $stmt = $dbh->prepare("SELECT gaia FROM Questions");

            $eposta = $_POST["eposta"];
            $stmt->bindParam(1, $eposta);

            $stmt->execute();

            
            echo '<select name="gaiak" id="gaiak" form="gaiaAukeratu" required>';  
            while ($ema = $stmt->fetch()){
                echo '<option value="'.$ema['gaia'].'">'.$ema['gaia'].'</option>';
                echo '<script> alert("Logeatu egin zara, '.$tabladatuak["eposta"].'") </script>';
            }
                
    ?>
    <?php

        if (!empty($_POST)){
            $GLOBALS['gaia'] = $_POST['gaiak']
            header("location: QuizPlay.php");
        }

    ?>
    <?php include '../html/Footer.html' ?>
</body>
</html>
