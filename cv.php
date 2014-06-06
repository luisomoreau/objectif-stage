<?php
include('all.header.php');
if ($_SESSION['type']!=="etudiants") {
    header('Location: index');
    die(); 
}
?>
            <h1>Mon CV</h1>
            <p>Vous pouvez ici envoyer votre CV pour pouvoir le joindre automatiquement au mails que vous envoyez aux entreprises.</p>
            <div class="cleaner h20"></div>
<?php
if (isset($_POST['cv'])) {
    if ( $_FILES['cv']['type'] != "application/pdf" ) {
        echo '<strong><p>Erreur, le document n\'est pas un fichier PDF</p></strong><div class="cleaner h20"></div>';
    } else {
        move_uploaded_file($_FILES['cv']['tmp_name'], "./fichiers/cv/".md5($_SESSION['identifiant']).".pdf");
    }
}
?>
            <h4>CV actuel:</h4>
<?php
$cv = "./fichiers/cv/".md5($_SESSION['identifiant']).".pdf";
if (file_exists($cv)) {
    echo '<iframe src='.$cv.' style="width:820px; height:700px;"></iframe>';
} else {
    echo '<p>Vous n\'avez pas encore envoyé votre CV !';
}
?>
            <div class="cleaner h20"></div>
            <div id="inscription_form">
                <form action="cv" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="cv" />
                    <label for="cv">Modifier votre CV (format PDF uniquement)</label>
                    <input class="float_l" type="file" name="cv" id="cv"/>
                    <div class="cleaner h10"></div>
                    <button class="float_l" type="submit">Envoyer mon CV</button>
                </form>
            </div>
            <div class="cleaner"></div>
<?php
include('all.footer.php');
?>