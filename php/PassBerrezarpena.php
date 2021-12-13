<!DOCTYPE html>
<html>
<head>

    <?php include '../html/Head.html'?>
    <?php include 'DbConfig.php'?>
</head>
<body>
    <?php include '../php/Menus.php' ?>
    <?php
        function kodeaSortu($luzera) {
          $karaktereak = '0123456789abcdefghijklmnopqrs092u3tuvwxyzaskdhfhf9882323ABCDEFGHIJKLMNksadf9044OPQRSTUVWXYZ';
          $karaktereKop = strlen($karaktereak);
          $kodea = '';
          for ($i = 0; $i < $luzera; $i++) {
            $kodea .= $karaktereak[rand(0, $karaktereKop - 1)];
          }
          return $kodea;
        }
        $GLOBALS['kodea'] = kodeaSortu(8);
        $GLOBALS['epostaKod'] = "";
        
        function kodeaBidali(){
          $email = $_POST['eposta'];
            $mezua = "Hemen duzu quiz aplikazioko zure kontuaren pasahitza berrezartzeko beharrezko kodea: ";
            $GLOBALS['kodea'] = kodeaSortu(8);
            $GLOBALS['epostaKod'] = $email;
            $mezua .= $GLOBALS['kodea'];
            $mezua = wordwrap($mezua,70);
            mail($email,"Pasahitzaren berrezarpena",$mezua);
            echo '<p style="color: blue"> Kodea bidali da.</p>';
          }



    ?>
    <section class="main" id="s1" style="display: flex">
          <div>
              <h5>Idatzi zure kontuaren eposta, pasahitza berrezartzeko kodea jasotzeko</h5>
              <form id="bidalketa" name="bidalketa" method="post">
                <!-- Kodea jasoko duen eposta -->
                <label for="eposta">Kontuaren eposta (*):</label>
                <input type="text" id="eposta" name="eposta" required><br>
                <input type="submit" name="bidali" id="bidali" value="Kodea bidali"><br>
              </form>
              <br>
            <h5>Idatzi zure eposta-ra iritsi den kodea eta pasahitz berria</h5>
            <form id="berrezarpena" name="berrezarpena" method="post">
                <!-- Epostako kodea -->
                <label for="kodea">Kodea (*):</label>
                <input type="password" id="kodea" name="kodea" required><br>

                <!-- Pasahitza -->
                <label for="pasahitza">Pasahitza (*):</label>
                <input type="password" id="pasahitza" name="pasahitza" required><br>

                <!-- Pasahitza berriro idatzi -->
                <label for="pasahitzaB">Pasahitza Berriro idatzi (*):</label>
                <input type="password" id="pasahitzaB" name="pasahitzaB" required><br>

                <!-- Pasahitz berria bidali -->
                <input type="submit" name="submit" id="submit" value="Berrezarri"><br>
            </form>
            <?php

            global $zerbitzaria, $erabiltzailea, $gakoa, $db;

            try {
                $dsn = "mysql:host=localhost;dbname=$db";
                $dbh = new PDO($dsn, $erabiltzailea, $gakoa);
                } catch (PDOException $e){
                alert("Errore bat gertatu da DB-ra konektatzerakoan: ");
                // . echo($e->getMessage())
                }

              if (isset($_POST['eposta'])) {

                  $stmt = $dbh->prepare("SELECT pasahitza FROM Erabiltzaileak WHERE eposta = ? AND blokeatuta = 0");
                  $eposta = $_POST["eposta"];
                  $stmt->bindParam(1, $eposta);
                  $stmt->execute();
                  $ema=$stmt->fetch();
                  if($ema != null){
                    kodeaBidali();
                  }
                  else{
                    echo '<p style="color: red"> Erabiltzailea ez da existitzen.</p>';
                  }
              }
              if (isset($_POST['pasahitza'], $_POST['pasahitzaB'], $_POST['kodea'])){
                if($_POST['kodea'] == $GLOBALS['kodea'] && $eposta == $GLOBALS['epostaKod']){
                  if($_POST['pasahitza'] == $_POST['pasahitzaB']){
                    if(crypt($_POST['pasahitza']) != $ema['pasahitza']){
                      $stmt = $dbh->prepare("UPDATE Erabiltzaileak SET pasahitza = ? WHERE eposta = ?");
                      $pasahitza = $_POST["pasahitza"];
                      $stmt->bindParam(1, $eposta);
                      $stmt->bindParam(2, $pasahitza);
                      $stmt->execute();
                      $dbh = null;
                      echo '<script> alert("Pasahitza ongi berrezarri da.") </script>';
                      header("location: Layout.php");
                    }
                    else{
                      echo '<p style="color: red"> Pasahitz berria ezin da zaharraren berdina izan.</p>';
                    }
                  }
                  else{
                    echo '<p style="color: red"> Pasahitza berridaztean akats bat egin duzu.</p>';
                  }
                }
                else{
                  echo '<p style="color: red"> Idatzitako kodea ez da zuzena.</p>';
                }
              }
              
            ?>
        </div>
    </section>
    <?php include '../html/Footer.html' ?>
</body>
</html>
