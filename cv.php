<?php
include('all.header.php');
if ($_SESSION['connected']!=="etud") {
    header('Location: ./');
    die(); 
}
echo '<section class="row">
            <div class="small-12 columns">
                <h1>Mon CV</h1>
                <p>Vous pouvez ici envoyer votre CV pour pouvoir le joindre automatiquement au mails que vous envoyez aux entreprises.</p>
            </div>
         </section>';
if (isset($_POST['cv'])) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ( finfo_file($finfo,$_FILES['cv']['tmp_name'])!= "application/pdf" ) {
        echo '<section class="row">
            <div class="small-12 columns">
                <strong>Erreur, le document n\'est pas un fichier PDF</strong>
            </div>
         </section>';
    } else {
        move_uploaded_file($_FILES['cv']['tmp_name'], "./fichiers/cv/".md5($_SESSION['identifiant']).".pdf");
    }
}
echo '<section class="row">
            <div class="small-12 columns">
                 <h4>CV actuel:</h4>
            </div>
         </section>';
$cv = "./fichiers/cv/".md5($_SESSION['identifiant']).".pdf";
if (file_exists($cv)) {
    echo '<section class="row">
            <div class="small-12 columns">
                 <iframe src='.$cv.' style="width:100%; height:700px;"></iframe>
            </div>
         </section>';
} else {
    echo '<p>Vous n\'avez pas encore envoyé votre CV !';
}
?>

    <form action="cv" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input type="hidden" name="cv" />
                <label for="cv">Modifier votre CV (format PDF uniquement)</label>
                <input class="float_l" type="file" name="cv" id="cv"/>
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