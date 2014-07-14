<?php
include('all.header.php');
?>
    <form method="POST" action="./inscriptiondb" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEnt') && checkPass('mdpEnt','mdpEnt2'))">
        <section class="row">
            <div class="small-12 columns">
                <h1>Inscription entreprise</h1>
            </div>
        </section>
        <div class="row">
            <div class="large-3 columns">
                <label for="profilpic" class="text-center">Ajouter votre logo (facultatif)<br/>
                    <img src="fichiers/profile/default.png" alt="Logo de l'entreprise"/>
                </label>
                <input class="float_l" type="file" name="profilpic" id="profilpic"/>
            </div>
            <div class="large-9 columns">
                <div class="row collapse">
                    <div class="small-6 large-3 columns">
                        <span class="prefix">Nom de l'entreprise</span>
                    </div>
                    <div class="small-6 large-9 columns">
                        <input type="text" name="nomEnt" id="nomEnt" maxlength="100" required="required"/>
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-6 large-3 columns">
                        <span class="prefix">Mail (Il sera votre identifiant)</span>
                    </div>
                    <div class="small-6 large-9 columns">
                        <input type="email" name="mailEnt" id="mailEnt" maxlength="100" required="required"/>
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-6 large-3 columns">
                        <span class="prefix">Mot de passe</span>
                    </div>
                    <div class="small-6 large-9 columns">
                        <input type="password" name="mdpEnt" id="mdpEnt" maxlength="25" required="required" onkeyup="checkPatern('mdpEnt'); return false;"/>
                        <span id="confirmPatern" class="confirmPatern"></span>
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-6 large-3 columns">
                        <span class="prefix">Confirmer mot de passe</span>
                    </div>
                    <div class="small-6 large-9 columns">
                        <input type="password" name="mdpEnt2" id="mdpEnt2" maxlength="25" required="required"
                               onkeyup="checkPass('mdpEnt','mdpEnt2'); return false;"/>
                        <span id="confirmMessage" class="confirmMessage"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <div class="row collapse">
                            <div class="small-6 large-5 columns">
                                <span class="prefix">Prenom de contact</span>
                            </div>
                            <div class="small-6 large-7 columns">
                                <input type="text" name="prenomContactEnt" id="prenomContactEnt" maxlength="50" required="required"/>
                            </div>
                        </div>
                    </div>
                    <div class="large-6 columns">
                        <div class="row collapse">
                            <div class="small-6 large-5 columns">
                                <span class="prefix">Nom de contact</span>
                            </div>
                            <div class="small-6 large-7 columns">
                                <input type="text" name="nomContactEnt" id="nomContactEnt" maxlength="50" required="required"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <div class="row collapse">
                            <div class="small-6 large-5 columns">
                                <span class="prefix">Tel principale</span>
                            </div>
                            <div class="small-6 large-7 columns">
                                <input type="tel" maxlength="6" name="telEnt" id="telEnt" value=""
                                       onkeyup="verif_nombre(this)" required="required"/>
                            </div>
                        </div>
                    </div>
                    <div class="large-6 columns">
                        <div class="row collapse">
                            <div class="small-6 large-5 columns">
                                <span class="prefix">Tel secondaire</span>
                            </div>
                            <div class="small-6 large-7 columns">
                                <input type="tel" maxlength="6" name="telSecEnt" id="telSecEnt" value=""
                                       onkeyup="verif_nombre(this)"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="small-12 columns">
                        <textarea placeholder="Adresse" name="adresseEnt" id="adresseEnt" maxlength="255" required="required"></textarea>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <label>Veuillez localiser votre entreprise :</label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <input id="lat" type="hidden" name="latEnt" value=""/>
                <input id="lng" type="hidden" name="lngEnt" value=""/>
                <script
                    src="https://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU"
                    type="text/javascript"></script>
                <div id="map" style="height: 320px;"><br/></div>
                <script type="text/javascript">
                    //<![CDATA[
                    loadmap(-22.2630844, 166.4031372);
                    //]]>
                </script>
            </div>
        </div>
        <br/>

        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input class="large button expand" id="envoyer" name="submit" type="submit" value="S'inscrire"/>
            </div>
        </div>
    </form>


<?php
include('all.footer.php');
?>