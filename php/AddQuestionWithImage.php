<!DOCTYPE html>
<html>
<head>
    <?php include '../html/Head.html'?>
    <?php include 'DbConfig.php'?>
</head>
<body>
    <?php include '../php/Menus.php' ?>
    <section class="main" id="s1">
        <div>
            <?php
            function eremuakKonprobatu($datuak) {
                if (!preg_match("/^(([a-zA-Z]+[0-9]{3}@ikasle\.ehu\.(eus|es))|([a-zA-Z]+@gmail\.(eus|es|com))|([a-zA-Z]+\.[a-zA-Z]+@ehu\.(eus|es)|[a-zA-Z]+@ehu\.(eus|es)))$/i", $datuak["frmeposta"])) {
                    return 'Eposta okerra';
                } else if (strlen($datuak["frmgalderatxt"]) < 10 || !ezHutsaVal($datuak["frmgalderatxt"])) {
                    return 'Galdera testua oso motza';
                } else if (!ezHutsaVal($datuak["frmerantzunzuzena"])) {
                    return 'Erantzun zuzena hutsa da';
                } else if (!ezHutsaVal($datuak["frmerantzunokerra1"])) {
                    return '1. erantzun okerra hutsa da';
                } else if (!ezHutsaVal($datuak["frmerantzunokerra2"])) {
                    return '2. erantzun okerra hutsa da';
                } else if (!ezHutsaVal($datuak["frmerantzunokerra3"])) {
                    return '3. erantzun okerra hutsa da';
                } else if (!(1 <= $datuak["frmzailtasuna"] && $datuak["frmzailtasuna"] <= 5)) {
                    return 'Zailtasuna okerra da';
                } else if (!ezHutsaVal($datuak["frmgaiarloa"])) {
                    return 'Gaia okerra da';
                }
                return '';
            }

            function ezHutsaVal($text){
                return preg_match("/^((\S)+( )*)+$/", $text);
            }

            function galderaGehitu() {
                if ($_SERVER['REQUEST_METHOD'] == 'GET') $aldagaiak = $_GET;
                else if ($_SERVER['REQUEST_METHOD'] == 'POST') $aldagaiak = $_POST;
                else die("Konekzioa egitean datuak ezin izan dira lortu");

                if (($konprobazioa = eremuakKonprobatu($aldagaiak)) != '') {
                    return "<b style='color: red'>Galdera ez da ondo bete: ".$konprobazioa."</b>
                            <button onclick='window.history.back()'>Berriro saiatu</button>";
                }

                $emaitza = '';
                // DB-n gorde
                $emaitza .= db_gorde($aldagaiak);

                // XML-n gorde
                $emaitza .= xml_gorde($aldagaiak);

                // JSON-en gorde
                $emaitza .= json_gorde($aldagaiak);

                if ($emaitza != ''){
                    return $emaitza."<button onclick='window.history.back()'>Berriro saiatu</button>";
                }

                //Eposta URL-an badago orduan eposta defektuz hau izango da
                $parametroak = "";
                if (isset($_GET['eposta'])) {
                    $parametroak = "?eposta=".$_GET['eposta'];
                    $parametroak = $parametroak."&irudia=".$_GET['irudia'];
                }
                return "<p>Galdera ondo gorde da DB-n, XML-n eta JSON-en</p>
                        <p><a href='ShowQuestionsWithImage.php".$parametroak."'>Galdera guztiak ikusi</a></p>
                        <p><a href='QuestionFormWithImage.php".$parametroak."'>Beste galdera bat gehitu</a></p>";
            }

            function db_gorde(array $aldagaiak)
            {
                global $zerbitzaria, $erabiltzailea, $gakoa, $db;
                $nireSQLI = new mysqli($zerbitzaria, $erabiltzailea, $gakoa, $db);

                if ($nireSQLI->connect_error) {
                    return "<b style='color: red'>DB-ra konexio bat egitean errore bat egon da: " . $nireSQLI->connect_error . "</b><br/>";
                }

                $irudia = "";
                if ($_FILES["frmirudia"]["tmp_name"] != "") {
                    $irudiaIzen = $_FILES["frmirudia"]["tmp_name"];
                    $irudia = addslashes(file_get_contents($irudiaIzen));
                }

                $sqlInsertQuestion = "INSERT INTO Questions(eposta, galdera, eZuzen, eOker1, eOker2, eOker3, zailtasuna, gaia, argazkia)
                VALUES ('$aldagaiak[frmeposta]', '$aldagaiak[frmgalderatxt]', '$aldagaiak[frmerantzunzuzena]', '$aldagaiak[frmerantzunokerra1]', '$aldagaiak[frmerantzunokerra2]',
                        '$aldagaiak[frmerantzunokerra3]', '$aldagaiak[frmzailtasuna]', '$aldagaiak[frmgaiarloa]', '$irudia')";

                if (!$nireSQLI->query($sqlInsertQuestion)) {
                    return '<b style="color: red">Errorea: ' . $nireSQLI->error . "</b><br/>";
                }

                return "";
            }

            function xml_gorde(array $aldagaiak){
                $fitxategia = "../xml/Questions.xml";
                $xml = simplexml_load_file($fitxategia);

                // Konprobatu fitxategia ondo kargatu dela
                if (!$xml) return "<b style='color: red'>Ez da Questions.xml fitxategia aurkitu, ezin izan da XML bezala gorde.</b><br/>";

                // Galdetegia bete
                $galdetegiaItem = $xml->addChild('assessmentItem');
                $galdetegiaItem->addAttribute('author', $aldagaiak["frmeposta"]);
                $galdetegiaItem->addAttribute('subject', $aldagaiak["frmgaiarloa"]);
                $galdetegiaItem->addChild('itemBody')->addChild('p', $aldagaiak["frmgalderatxt"]);
                $galdetegiaItem->addChild('correctResponse')->addChild('response', $aldagaiak["frmerantzunzuzena"]);
                $erantzunOkerrak = $galdetegiaItem->addChild('incorrectResponses');
                $erantzunOkerrak->addChild('response', $aldagaiak["frmerantzunokerra1"]);
                $erantzunOkerrak->addChild('response', $aldagaiak["frmerantzunokerra2"]);
                $erantzunOkerrak->addChild('response', $aldagaiak["frmerantzunokerra3"]);

                $domxml = new DOMDocument('1.0');
                $domxml->preserveWhiteSpace = false;
                $domxml->formatOutput = true;
                $domxml->loadXML($xml->asXML());
                $gordeketa = $domxml->save($fitxategia);
                if (!$gordeketa) return "<b style='color: red'>Ezin izan da XML fitxategia gorde.</b><br/>";
                return "";
            }

            function json_gorde(array $aldagaiak) {
                $fitxategia = "../json/Questions.json";
                $json_raw = file_get_contents($fitxategia);
                if (!$json_raw) return "<b style='color: red'>Ez da Questions.json fitxategia aurkitu, ezin izan da JSON bezala gorde.</b><br/>";
                $json =json_decode($json_raw);

                $galdetegia = new stdClass();
                $galdetegia->subject = $aldagaiak["frmgaiarloa"];
                $galdetegia->author = $aldagaiak["frmeposta"];
                $galdetegia->itemBody = new stdClass();
                $galdetegia->itemBody->p = $aldagaiak["frmgalderatxt"];
                $galdetegia->correctResponse = new stdClass();
                $galdetegia->correctResponse->response = $aldagaiak["frmerantzunzuzena"];
                $erantzunoker = array($aldagaiak["frmerantzunokerra1"],
                    $aldagaiak["frmerantzunokerra2"],
                    $aldagaiak["frmerantzunokerra3"]);
                $galdetegia->incorrectResponses = new stdClass();
                $galdetegia->incorrectResponses->response = $erantzunoker;

                array_push($json->assessmentItems, $galdetegia);
                $jsonBerria = json_encode($json, JSON_PRETTY_PRINT);

                $gordeketa = file_put_contents($fitxategia,$jsonBerria);
                if (!$gordeketa || $gordeketa === 0) return "<b style='color: red'>Ezin izan da JSON fitxategia gorde.</b><br/>";
            }

            echo "<p>".galderaGehitu()."</p>"
            ?>


        </div>
    </section>
    <?php include '../html/Footer.html' ?>
</body>
</html>
