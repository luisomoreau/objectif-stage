<?php 
    include('all.header.php');
    if (!isset($_POST[remuStage]) or !isset($_POST[typeStage]) or !isset($_POST[latStage]) or !isset($_POST[lngStage]) or!isset($_POST[nomStage]) or !isset($_POST[nomContactStage]) or !isset($_POST[prenomContactStage]) or !isset($_POST[mailContactStage]) or !isset($_POST[lieuStage]) or !isset($_POST[dateJourDebutStage]) or !isset($_POST[dateMoisDebutStage]) or !isset($_POST[dateAnneeDebutStage]) or !isset($_POST[dateJourFinStage]) or !isset($_POST[dateMoisFinStage]) or !isset($_POST[dateAnneeFinStage]) or !isset($_POST[dateJourLimiteStage]) or !isset($_POST[dateMoisLimiteStage]) or !isset($_POST[dateAnneeLimiteStage]) or !isset($_POST[sujetStage]) or !isset($_POST[detailsStage])) {
        header('Location: /');
        die(); 
    }

    /*****************Date debut stage*************************/

    if (($_POST[dateAnneeDebutStage]==="Année") || ($_POST[dateMoisDebutStage]==="Mois") || ($_POST[dateJourDebutStage]==="Jour")) { // à approfondir
        include('all.footer.php');
        ?>
        <script>
            alert('Dates incohérentes');
            history.back();
        </script>
        <?php        
        die();
    } else {
        if (($_POST[dateAnneeDebutStage]>2100) || $_POST[dateAnneeDebutStage]<2000) {
            header('Location: /');
            die(); 
        }
        if (($_POST[dateMoisDebutStage]>12) || $_POST[dateMoisDebutStage]<1) {
            header('Location: /');
            die(); 
        }
        if (($_POST[dateJourDebutStage]>31) || $_POST[dateJourDebutStage]<1) {
            header('Location: /');
            die(); 
        }
    }
    $dateDebutStage = "$_POST[dateAnneeDebutStage]-$_POST[dateMoisDebutStage]-$_POST[dateJourDebutStage]";
    //echo "Date de naissance concat: $dateDebutStage<br /><br />";
    //echo strtotime($dateDebutStage)."<br />";
    
    /********************Date fin stage*********************/
    
    if (($_POST[dateAnneeFinStage]==="Année") || ($_POST[dateMoisFinStage]==="Mois") || ($_POST[dateJourFinStage]==="Jour")) { // à approfondir
        include('all.footer.php');
        ?>
        <script>
            alert('Dates incohérentes');
            history.back();
        </script>
        <?php        
        die();
    } else {
        if (($_POST[dateAnneeFinStage]>2100) || $_POST[dateAnneeFinStage]<2000) {
            header('Location: /');
            die(); 
        }
        if (($_POST[dateMoisFinStage]>12) || $_POST[dateMoisFinStage]<1) {
            header('Location: /');
            die(); 
        }
        if (($_POST[dateJourFinStage]>31) || $_POST[dateJourFinStage]<1) {
            header('Location: /');
            die(); 
        }
    }
    $dateFinStage = "$_POST[dateAnneeFinStage]-$_POST[dateMoisFinStage]-$_POST[dateJourFinStage]";
    // "Date de naissance concat: $dateFinStage<br /><br />";
    //echo strtotime($dateFinStage)."<br />";
    
    /******Durée stage********/
    
    $dureeStage=(strtotime($dateFinStage)-strtotime($dateDebutStage))/3600/24;
    //echo $dureeStage;
    
    /*****************Date limite stage**************************/
    
    if (($_POST[dateAnneeLimiteStage]==="Année") || ($_POST[dateMoisLimiteStage]==="Mois") || ($_POST[dateJourLimiteStage]==="Jour")) { // à approfondir
        include('all.footer.php');
        ?>
        <script>
            alert('Dates incohérentes');
            history.back();
        </script>
        <?php        
        die(); 
    } else {
        if (($_POST[dateAnneeLimiteStage]>2100) || $_POST[dateAnneeLimiteStage]<2000) {
            header('Location: /');
            die(); 
        }
        if (($_POST[dateMoisLimiteStage]>12) || $_POST[dateMoisLimiteStage]<1) {
            header('Location: /');
            die(); 
        }
        if (($_POST[dateJourLimiteStage]>31) || $_POST[dateJourLimiteStage]<1) {
            header('Location: /');
            die(); 
        }
    }
    $dateLimiteStage = "$_POST[dateAnneeLimiteStage]-$_POST[dateMoisLimiteStage]-$_POST[dateJourLimiteStage]";
    //echo "Date de naissance concat: $dateLimiteStage<br /><br />";
    //echo strtotime($dateLimiteStage)."<br />";
    
    /****** Dates non valides ******/
    
    if ((strtotime($dateLimiteStage)>strtotime($dateDebutStage)) || (strtotime($dateDebutStage)>strtotime($dateFinStage))) {
        include('all.footer.php');
        ?>
        <script>
            alert('Dates incohérentes');
            history.back();
        </script>
        <?php        
        die();
    }
    
    // On prépare les variables à insérer dans la BDD, on remplace par NULL si vide. (Entre simples quotes sinon)
    foreach($_POST as $col=>$val) {
            //echo $col." => ".$val."<br />";
            if(empty($val)) {
                $$col = "";
            } else {
                $$col = "$val";
            }
    }   
    
    /*************check erreurs !!!***************/
    
    if($typeStage==="Niveau") {
        include('all.footer.php');
        ?>
        <script>
            alert('Niveau non choisie');
            history.back();
        </script>
        <?php        
        die();
    }
    
    if(($typeStage!=="Stage L2") && ($typeStage!=="Stage L3") && ($typeStage!=="Projet Tuteuré")) {
            header('Location: index');
            die(); 
    }
    
    if (strlen($nomStage)>200) {
            header('Location: index');
            die(); 
    }
    if (strlen($nomContactStage)>50) {
            header('Location: index');
            die(); 
    }
    if (strlen($prenomContactStage)>50) {
            header('Location: index');
            die(); 
    }
    if ((strlen($mailContactStage)>100) || !(filter_var( $mailContactStage, FILTER_VALIDATE_EMAIL ))) {
            header('Location: index');
            die(); 
    }
    if (strlen($lieuStage)>255) {
            header('Location: index');
            die(); 
    }
    if (strlen($sujetStage)>1000) {
            header('Location: index');
            die(); 
    }
    if (strlen($detailsStage)>1000) {
            header('Location: index');
            die(); 
    }
    if ($htmlcssStage!=1) {
        $htmlcssStage=0;
    }
    if ($phpStage!=1) {
        $phpStage=0;
    }
    if ($sqlStage!=1) {
        $sqlStage=0;
    }
    if ($javaStage!=1) {
        $javaStage=0;
    }
    if ($cStage!=1) {
        $cStage=0;
    }
    if ($csStage!=1) {
        $csStage=0;
    }
    if ($remuStage!=1) {
        $remuStage=0;
    }
    if (strlen($langageAutreStage)>255) {
            header('Location: index');
            die(); 
    }
    
    /***********insertion bdd******************/
    
    if (empty($langageAutreStage)) {
        $langageAutreStage = "NULL";
    }

    $idEnt = $_SESSION[idEnt];
    echo "session :".$_SESSION[idEnt];
    
    $lieuStage=str_replace("'","\'",$lieuStage);
    $idEnt=str_replace("'","\'",$idEnt);
    $nomStage=str_replace("'","\'",$nomStage);
    $nomContactStage=str_replace("'","\'",$nomContactStage);
    $prenomContactStage=str_replace("'","\'",$prenomContactStage);
    $mailContactStage=str_replace("'","\'",$mailContactStage);
    $lngStage=str_replace("'","\'",$lngStage);
    $latStage=str_replace("'","\'",$latStage);
    $sujetStage=str_replace("'","\'",$sujetStage);
    $detailsStage=str_replace("'","\'",$detailsStage);
    $typeStage=str_replace("'","\'",$typeStage);
    $langageAutreStage=str_replace("'","\'",$langageAutreStage);
    $dateDebutStage=str_replace("'","\'",$dateDebutStage);
    $dateFinStage=str_replace("'","\'",$dateFinStage);
    $dateLimiteStage=str_replace("'","\'",$dateLimiteStage);
    $dureeStage=str_replace("'","\'",$dureeStage);
    
    // Requète SQL    
    //echo $getident;
    $query = "INSERT INTO stages (idEnt,nomStage, typeStage, nomContactStage, prenomContactStage, mailContactStage, lieuStage, latStage, lngStage, dateDebutStage, dateFinStage, dateLimiteStage, dureeStage, sujetStage, detailsStage,htmlcssStage,phpStage,sqlStage,javaStage,cStage,csStage,langageAutreStage,remuStage)
                        VALUES ('$idEnt','$nomStage', '$typeStage', '$nomContactStage', '$prenomContactStage', '$mailContactStage', '$lieuStage',
                         '$latStage','$lngStage', '$dateDebutStage', '$dateFinStage', '$dateLimiteStage', '$dureeStage', '$sujetStage', '$detailsStage',
                         '$htmlcssStage','$phpStage','$sqlStage','$javaStage','$cStage','$csStage','$langageAutreStage','$remuStage')"; 
    echo "<br />".$query."<br />";  
    // Exécution de la requète
    $result = mysqli_query($dblink, $query);
    if (!$result) {
        echo "Erreur, lors du dépot du stage";
        include('all.footer.php');
        die();
    }   
    header('Location: messtages');
    include('all.footer.php');
    die();
?>