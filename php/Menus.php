<div id='page-wrap'>
<header class='main' id='h1'>
    <?php
    session_start();
    if (isset($_SESSION['kautotua']) &&  ($_SESSION['kautotua']) == "BAI") {
        echo '<span class="right"><a href="LogOut.php">Logout</a></span> &nbsp;';
        echo '<span class="right"><a href="PassBerrezarpena.php">Pasahitza berrezarri</a></span> &nbsp;';
        echo $_SESSION['eposta'].'&nbsp;';

        if (isset($_SESSION['irudia']) && file_exists($_SESSION['irudia'])) {
            $irudia = file_get_contents($_SESSION['irudia']);
            echo '<img src="data:image/*;base64,' . base64_encode($irudia) . '" height=50 width=50"/>';
        } else {
            echo '<img src="../images/default_erabiltzailea.png" height=50 width=50"/>';
        }
    } else {
        echo '<span class="right"><a href="SignUp.php">Erregistratu</a></span> &nbsp;';
        echo '<span class="right"><a href="LogIn.php">Login</a></span> &nbsp;';
        echo '<span class="right"><a href="LoginSoziala.php">Login Soziala</a></span> &nbsp;';
        echo '<span class="right"><a href="PassBerrezarpena.php">Pasahitza berrezarri</a></span> &nbsp;';
        echo 'Anonimoa &nbsp;';
        echo '<img src="../images/erabiltzaile_anonimoa.png" height=50 width=50"/>';
    }
    ?>
</header>

<nav class='main' id='n1' role='navigation'>
    <?php
    echo '<span><a href="Layout.php">Hasiera</a></span>';
    if (isset($_SESSION['kautotua']) && ($_SESSION['kautotua'] == "BAI") && ($_SESSION['mota'] != "3")) {
        echo '<span><a href="QuestionFormWithImage.php">Galderak gehitu</a></span>';
        echo '<span><a href="HandlingQuizesAjax.php">Galderak gehitu AJAX</a></span>';
        echo '<span><a href="ShowQuestionsWithImage.php">Galderak ikusi</a></span>';
        echo '<span><a href="ShowXmlQuestions.php">Galderak (XML)</a></span>';
        echo '<span><a href="ShowJsonQuestions.php">Galderak (JSON)</a></span>';
    }
    if (isset($_SESSION['kautotua']) && ($_SESSION['kautotua']) == "BAI" && ($_SESSION['mota']) == "2"){
        echo '<span><a href="IsVip.php">VIPa da?</a></span>';
        echo '<span><a href="AddVip.php">Gehitu VIPa</a></span>';
        echo '<span><a href="DeleteVip.php">Ezabatu VIPa</a></span>';
        echo '<span><a href="ShowVips.php">Ikusi VIPak</a></span>';
    }
    if (isset($_SESSION['kautotua']) && ($_SESSION['kautotua']) == "BAI" && ($_SESSION['mota']) == "3"){
        echo '<span><a href="HandlingAccounts.php">Erabiltzaileak kudeatu</a></span>';
    }
    echo '<span><a href="Credits.php">Kredituak</a></span>';
    ?>
</nav>
