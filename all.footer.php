<script>
    $('.date_picker').fdatepicker({
        format: 'dd/mm/yyyy'
    });
</script>
<div id="footer">
<footer class="l-footer panel" role="contentinfo">
    <div class="row">

        <div class="copyright large-8 large-centered text-center columns">
            &copy; 2014 &quot;Objectif stage&quot; All rights reserved. Developped by <?php if(mt_rand(0,1)) echo "Bruno CREMADES && Nicolas BRENGARD"; else echo "Nicolas BRENGARD && Bruno CREMADES";?>
        </div>
    </div>
</footer>
</div>
</div>
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