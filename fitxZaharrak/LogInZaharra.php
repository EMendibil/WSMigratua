<!DOCTYPE html>
<html>
<head> 

    <?php include '../html/Head.html'?>
    <?php include 'DbConfig.php'?>
</head>
<body>
    <?php include '../php/Menus.php' ?>
    <?php
        if (isset($_SESSION["kautotua"]) && $_SESSION["kautotua"] == "BAI") {
            echo "<script> window.location.href = 'Layout.php';</script>";
            exit();
        }
    ?>
    <section class="main" id="s1" style="display: flex">
        <div>
            <form id="loginF" name="loginF" method="post">
                <!-- Eposta -->
                <label for="eposta">Eposta (*):</label>
                <input type="text" id="eposta" name="eposta"><br>

                <!-- Pasahitza -->
                <label for="pasahitza">Pasahitza (*):</label>
                <input type="password" id="pasahitza" name="pasahitza"><br>

                <!-- Galdera igorri -->
                <input type="submit" name="submit" id="submit" value="Logeatu"><br>
            </form>
            <?php
            
            if (!empty($_POST)){
                $datuak = $_POST;
                global $zerbitzaria, $erabiltzailea, $gakoa, $db;
                
                $nireSQLI = new mysqli($zerbitzaria, $erabiltzailea, $gakoa, $db);

                if($nireSQLI->connect_error) {
                    die("DB-ra konexio bat egitean errore bat egon da: " . $nireSQLI->connect_error);
                }



                $ema = $nireSQLI->query("SELECT eposta, pasahitza, irudia_dir, mota FROM Erabiltzaileak WHERE eposta = '".$_POST["eposta"]."' AND blokeatuta = 0");

                if (($tabladatuak = $ema->fetch_row()) != null) {
                    if ($datuak["eposta"] == $tabladatuak[0] && hash_equals($tabladatuak[1], crypt($datuak["pasahitza"], $tabladatuak[1]))) {
                        include 'IncreaseGlobalCounter.php';
                        $_SESSION["kautotua"]= "BAI";
                        $_SESSION["eposta"] = $tabladatuak[0];
                        $_SESSION["irudia"] = $tabladatuak[2];
                        $_SESSION["mota"] = $tabladatuak[3];
                        echo '<script> alert("Logeatu egin zara, '.$tabladatuak["eposta"].'") </script>';
                        if($_SESSION["mota"] == 1){
                            header("location: HandlingQuizesAjax.php");
                        }else if($_SESSION["mota"] == 2){
                            header("location: Layout.php");
                        }else{
                            header("location: Layout.php");
                        }
                    } else {
                        echo '<p style="color: red"> Zure erabiltzailea edo pasahitza ez dira zuzenak. </p>';
                    }
                } else {
                    echo '<p style="color: red"> Erabiltzailea ez da existitzen.</p>';
                }
                

            }
            ?>
        </div>
    </section>
    <?php include '../html/Footer.html' ?>
</body>
</html>
