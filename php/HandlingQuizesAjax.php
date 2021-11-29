<!DOCTYPE html>
<html>
<head>
    <?php include '../html/Head.html'?>
    <script src="../js/ShowImageInForm.js"></script>
    <script src="../js/jquery-3.4.1.min.js"></script>
    <!-- <script src="../js/ValidateFieldsQuestionJS.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="../js/ValidateFieldsQuestionJQ.js"></script>
    <script src="../js/ShowQuestionsAjax.js"></script>
    <script src="../js/AddQuestionAjax.js"></script>
    <script src="../js/JsonQuestionsCounter.js"></script>
    <script src="../js/XmlUserCounter.js"></script>
</head>

<body>
    <?php include '../php/Menus.php' ?>
    <?php
        if (($_SESSION["kautotua"] != "BAI") || ($_SESSION["mota"] == "3")) {
            echo "<script> window.location.href = 'Layout.php';</script>";
            exit();
        }
    ?>
    <section class="main" id="s1">
        <div id="galdetegia">
            <div>
                <b>Nire galderak / galderak guztira</b>
                <div id="galderaKop" style="border:1px solid black; width:300px; margin:auto;">n</div>
            </div>
            <div>
                <b>Kautotutako erabiltzaile kopurua</b>
                <div id="userKop" style="border:1px solid black; width:300px; margin:auto;">n</div>
            </div><br>
            <?php
            $parametroak = "";
            if (isset($_GET['eposta'])) {
                $parametroak = "?eposta=".$_GET['eposta'];
                $parametroak = $parametroak."&irudia=".$_GET['irudia'];
            }
            ?>
            <form id="galderenF" name="galderenF" method="post"
                  action="AddQuestionWithImage.php<?php if (isset($_GET['eposta'])){echo '?eposta='.$_GET['eposta']."&irudia=".$_GET['irudia'];}?>"
                  enctype="multipart/form-data" onreset="hide_image()">
                <!-- Eposta -->
                <label for="frmeposta">Eposta (*):</label>
                <?php
                $eposta = "";
                if (isset($_SESSION['eposta'])) {
                    $eposta = $_SESSION['eposta'];
                    echo '<input type="text" id="frmeposta" name="frmeposta" value="'.$eposta.'" readonly="readonly">';
                } else {
                    echo '<input type="text" id="frmeposta" name="frmeposta" placeholder="EHU eposta">';
                }
                ?>
                <br>

                <!-- Galdera testua -->
                <label for="frmgalderatxt">Galderaren testua (*):</label>
                <input type="text" id="frmgalderatxt" name="frmgalderatxt"><br>

                <!-- Erantzun zuzena -->
                <label for="frmerantzunzuzena">Erantzun zuzena (*):</label>
                <input type="text" id="frmerantzunzuzena" name="frmerantzunzuzena"><br>

                <!-- Erantzun okerra 1 -->
                <label for="frmerantzunokerra1">Erantzun okerra1 (*):</label>
                <input type="text" id="frmerantzunokerra1" name="frmerantzunokerra1"><br>

                <!-- Erantzun okerra 2 -->
                <label for="frmerantzunokerra2">Erantzun okerra2 (*):</label>
                <input type="text" id="frmerantzunokerra2" name="frmerantzunokerra2"><br>

                <!-- Erantzun okerra 3 -->
                <label for="frmerantzunokerra3">Erantzun okerra3 (*):</label>
                <input type="text" id="frmerantzunokerra3" name="frmerantzunokerra3"><br>

                <!-- Zailtasuna -->
                <label>Zailtasuna (*):</label>
                <input type="radio" id="frmrdbtxikia" name="frmzailtasuna" value="1">
                <label for="frmrdbtxikia">Txikia</label>

                <input type="radio" id="frmrdbertaina" name="frmzailtasuna" value="2">
                <label for="frmrdbertaina">Ertaina</label>

                <input type="radio" id="frmrdbhandia" name="frmzailtasuna" value="3">
                <label for="frmrdbhandia">Handia</label><br>

                <!-- Gai arloa -->
                <label for="frmgaiarloa">Gaia (*):</label>
                <input type="text" id="frmgaiarloa" name="frmgaiarloa"><br>

                <!-- Irudia -->
                <label>Irudia:</label>
                <input type="file" accept="image/*" name="frmirudia" id="frmirudia" onchange="show_image(this, 'reset')"><br>

                <!-- Hustu -->
                <input type="reset" name="reset" id="reset"  value="Hustu">

                <!-- Galdera igorri -->
                <input type="button" name="addquestion" form="galdetegia" id="addquestion" onclick="AddQuestionAjax()" value="Galdera igorri">

                <!-- Galderak erakutsi -->
                <input type="button" name="showquestions" form="galdetegia" id="showquestions" onclick="ShowQuestionsAjax()" value="Ikusi JSON galderak">
            </form>

            <br>
            <div id="mezua">Bidalketaren feedbacka...</div>
            <br>
            <center><div id="erakutsi">JSON galderak erakusteko...</div></center>
        </div>
    </section>
    <?php include '../html/Footer.html' ?>
</body>
</html>
