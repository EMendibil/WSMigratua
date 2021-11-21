<!DOCTYPE html>
<html>
<head>
    <?php include '../html/Head.html'?>
</head>
<body>
<?php include '../php/Menus.php' ?>
<?php
        if ($_SESSION["kautotua"] != "BAI") {
            header("Location: layout.php");
            exit();
        }
    ?>
<section class="main" id="s1" style="display: flex">
    <div style="overflow-y: scroll; overflow-x: scroll">
        <table style="width:100%">
            <thead>
                <th>Egilea</th>
                <th>Enuntziatua</th>
                <th>Erantzun zuzena</th>
            </thead>
            <tbody>
                <?php
                $xml = simplexml_load_file("../xml/Questions.xml");

                foreach ($xml->children() as $galdetegi){
                    $eposta = $galdetegi->attributes()->author;
                    $enuntziatua = $galdetegi->itemBody->p;
                    $erantzun_z = $galdetegi->correctResponse->response;

                    echo '<tr>';
                    echo "<td>" . $eposta . "</td>";
                    echo "<td>" . $enuntziatua . "</td>";
                    echo "<td>" . $erantzun_z . "</td>";
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>
<?php include '../html/Footer.html' ?>
</body>
</html>
