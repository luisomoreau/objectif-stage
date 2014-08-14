function verif_nombre(champ) {
    champ.value = champ.value.replace(/[^0-9]/g, '');
}

function loadmap(var1, var2) {
    if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        map.disableScrollWheelZoom();
        var center = new GLatLng(var1, var2);
        map.setCenter(center, 15);
        geocoder = new GClientGeocoder();
        var marker = new GMarker(center, {draggable: true});
        map.addOverlay(marker);
        document.getElementById("lat").value = center.lat().toFixed(7);
        document.getElementById("lng").value = center.lng().toFixed(7);

        GEvent.addListener(marker, "dragend", function () {
            var point = marker.getPoint();
            map.panTo(point);
            document.getElementById("lat").value = point.lat().toFixed(7);
            document.getElementById("lng").value = point.lng().toFixed(7);

        });

        GEvent.addListener(map, "moveend", function () {
            map.clearOverlays();
            var center = map.getCenter();
            var marker = new GMarker(center, {draggable: true});
            map.addOverlay(marker);
            document.getElementById("lat").value = center.lat().toFixed(7);
            document.getElementById("lng").value = center.lng().toFixed(7);

            GEvent.addListener(marker, "dragend", function () {
                var point = marker.getPoint();
                map.panTo(point);
                document.getElementById("lat").value = point.lat().toFixed(7);
                document.getElementById("lng").value = point.lng().toFixed(7);
            });
        });

    }
}

var checkPass = function (mdp, mdp2) {
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    var divMessage = ".confirmMessage";
    if ($(mdp).val() == $(mdp2).val()) {
        $(mdp2).css("background-color", goodColor);
        $(divMessage).css("color", goodColor);
        $(divMessage).html("<p>Mot de passe identiques</p>");
        return true;
    } else {
        $(mdp2).css("background-color", badColor);
        $(divMessage).html("<p>Mot de passe non-identiques</p>");
        $(divMessage).css("color", badColor);
        return false;
    }
};

var checkPatern = function (mdp) {
    var divMessage = ".confirmPatern";
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    var re = /^(?=.*\d)(?=.*[a-zA-z]).{6,50}$/;
    var tester = re.test($(mdp).val());
    if (tester) {
        $(mdp).css("background-color", goodColor);
        $(divMessage).css("color", goodColor);
        $(divMessage).html("<p>Mot de passe correct</p>");
        return true;
    } else {
        $(mdp).css("background-color", badColor);
        $(divMessage).css("color", badColor);
        $(divMessage).html("<p>Mot de passe incorrect, veuillez entrer un mot de passe contenant au moins six caractères dont un chiffre</p>");
        return false;
    }
};