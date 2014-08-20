<?php
include('all.header.php');
include('logincheck.php');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
if ($_SESSION['connected'] !== "etud") {
    header('Location: ./');
    die();
}
echo '<section class="row">
            <div class="small-12 columns">
                <div class="panel">
                    <p>Vous pouvez ici envoyer votre CV pour pouvoir le joindre automatiquement au mails que vous envoyez aux entreprises.</p>
                    <p>Modification du CV en bas de la page</p>
                </div>
            </div>
         </section>';
if (isset($_POST['cv'])) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if (finfo_file($finfo, $_FILES['cv']['tmp_name']) != "application/pdf") {
        echo '<section class="row">
            <div class="small-12 columns">
                <strong>Erreur, le document n\'est pas un fichier PDF</strong>
            </div>
         </section>';
    } else {
        if ($_FILES['cv']['size'] <= $tailleMax) {
            move_uploaded_file($_FILES['cv']['tmp_name'], "./fichiers/cv/" . md5($_SESSION['identifiant']) . ".pdf");
        } else {
            echo '<section class="row">
            <div class="small-12 columns">
                <strong>Erreur, le document est trop lourd (taille max : ' . $tailleMax . ' octets)</strong>
            </div>
         </section>';
        }
    }
}
echo '<section class="row">
            <div class="small-12 columns">
                 <h4>CV actuel:</h4>
            </div>
         </section>';
$cv = "./fichiers/cv/" . md5($_SESSION['identifiant']) . ".pdf";
if (file_exists($cv)) {
    echo '<section class="row">
            <div class="small-12 columns">
                 <iframe src=' . $cv . ' style="width:100%; height:700px;"></iframe>
            </div>
         </section>';
} else {
    echo '<section class="row">
            <div class="small-12 columns">
                 <p>Vous n\'avez pas encore uploadé votre cv !</p>
            </div>
         </section>';
}
?>
    <br/>
    <form action="cv" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input type="hidden" name="cv"/>
                <label for="cv"><h4>Modifier votre CV (format PDF uniquement)</h4></label>
                <input type="file" name="cv" id="cv"/>
            </div>
        </div>
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <label for="envoyer"></label>
                <input class="large button expand" name="envoyer" type="submit" value="Uploader mon CV"/>
            </div>
        </div>
    </form>
<?php
include('all.footer.php');
?>