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
        
    
    </section>
    <?php include '../html/Footer.html' ?>
</body>
</html>