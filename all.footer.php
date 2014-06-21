<div id="footer">
<section class="l-footer-columns">
    <div class="row">
        <div class="footer-first large-3 columns">
            <div class="block block-block block-block-5">

                <h2 class="block-title">Nous trouver</h2>

                <p>Université de la Nouvelle-Calédonie<br/>
                    Campus de Nouville<br/>
                    98851 Nouméa CEDEX<br/>
                    Téléphone: +687 29 02 90<br/>
                    Fax +687 25 48 29<br/><a href="mailto:contact@univ-nc.nc">contact@univ-nc.nc</a></p>

            </div>
        </div>
        <div class="footer-second large-3 columns">
<!--            <div class="block block-block block-block-10">-->
<!---->
<!---->
<!--                <iframe width="250" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"-->
<!--                        src="https://maps.google.fr/maps?f=q&amp;source=s_q&amp;hl=fr&amp;geocode=&amp;q=universit%C3%A9+de+nouvelle+caledonie&amp;aq=&amp;sll=46.75984,1.738281&amp;sspn=8.083679,21.643066&amp;ie=UTF8&amp;hq=&amp;hnear=&amp;ll=-22.26313,166.403155&amp;spn=0.006295,0.006295&amp;t=m&amp;iwloc=A&amp;output=embed"></iframe>-->
<!--                <p>-->
<!--                    <small><a-->
<!--                            href="https://maps.google.fr/maps?f=q&amp;source=embed&amp;hl=fr&amp;geocode=&amp;q=universit%C3%A9+de+nouvelle+caledonie&amp;aq=&amp;sll=46.75984,1.738281&amp;sspn=8.083679,21.643066&amp;ie=UTF8&amp;hq=&amp;hnear=&amp;ll=-22.26313,166.403155&amp;spn=0.006295,0.006295&amp;t=m&amp;iwloc=A"-->
<!--                            style="color:#0000FF;text-align:left">Agrandir le plan</a></small>-->
<!--                </p>-->
<!---->
<!--            </div>-->
        </div>
        <div class="footer-third large-3 columns">
            <div class="block block-block block-block-17">

                <h2 class="block-title">Mentions légales</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br/>
                    Suspendisse ornare convallis orci sit amet lobortis.<br/>
                    Aenean mollis justo massa, ac iaculis lorem malesuada non.<br/>
                    Nulla sed nisi eget eros interdum accumsan in ultricies lectus.<br/>
                    Nulla eget pharetra erat, non hendrerit sapien.</p>

            </div>
        </div>
        <div class="footer-fourth large-3 columns">
            <div class="block block-ent-nodeblock block-ent-nodeblock-ent-nodeblock-8">
                <h2 class="block-title">Infos pratiques</h2>
                <article id="node-8" class="node node-ent-menu view-mode-full" about="/content/infos-pratiques" typeof="sioc:Item foaf:Document">
                    <div class="field field-name-field-ent-menu-links field-type-link-field field-label-hidden field-wrapper">
                        <a href="http://www.univ-nc.nc" target="_blank">Site de l'UNC</a><br/>
                        <a href="http://www.univ-nc.nc/sites/www.univ-nc.nc/files/11-2012_unc_depliant_4_volets_plan_univ.pdf" target="_blank">Plan du campus</a><br/>
                        <a href="https://ent.univ-nc.nc/carte-sup">Carte Sup Multiservices</a><br/>
                        <a href="http://www.karuiabus.nc/PDF/horraires10-11.pdf" target="_blank">Horaires de bus</a><br/>
                        <a href="https://ent.univ-nc.nc/horaires-services">Horaires des services</a><a href="https://ent.univ-nc.nc/contact">Contact</a><br/>
                    </div>
                </article>

            </div>
        </div>
    </div>
</section>
<footer class="l-footer panel" role="contentinfo">
    <div class="row">

        <div class="copyright large-6 text-right columns">
            &copy; 2014 &quot;Espace Numérique de Travail&quot; All rights reserved.
        </div>
    </div>
</footer>
</div>
</div>
<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
    $(document).foundation();
    $(window).bind("load resize", function () {
        var footer = $("#footer");
        var pos = footer.position();
        var height = $(window).height();
        height = height - pos.top;
        height = height - footer.height();
        if (height > 0) {
            footer.css({
                'margin-top': height + 'px'
            });
        }
    });
</script>
</body>
</html>