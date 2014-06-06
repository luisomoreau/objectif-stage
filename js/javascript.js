function verif_nombre(champ) {
    champ.value=champ.value.replace(/[^0-9]/g,'');
}

function loadmap(var1,var2) 
{
    if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        var center = new GLatLng(var1, var2);
        map.setCenter(center, 15);
        geocoder = new GClientGeocoder();
        var marker = new GMarker(center, {draggable: true});  
        map.addOverlay(marker);
        document.getElementById("lat").value = center.lat().toFixed(7);
        document.getElementById("lng").value = center.lng().toFixed(7);
        
        GEvent.addListener(marker, "dragend", function() {
        var point = marker.getPoint();
        map.panTo(point);
        document.getElementById("lat").value = point.lat().toFixed(7);
        document.getElementById("lng").value = point.lng().toFixed(7);
        
        });
        
        
        GEvent.addListener(map, "moveend", function() 
        {
            map.clearOverlays();
            var center = map.getCenter();
            var marker = new GMarker(center, {draggable: true});
            map.addOverlay(marker);
            document.getElementById("lat").value = center.lat().toFixed(7);
            document.getElementById("lng").value = center.lng().toFixed(7);            
            
            GEvent.addListener(marker, "dragend", function() 
            {
            var point =marker.getPoint();
            map.panTo(point);
            document.getElementById("lat").value = point.lat().toFixed(7);
            document.getElementById("lng").value = point.lng().toFixed(7);            
            });            
        });
        
    }
}

function checkPass(mdp,mdp2) {
    var pass1 = document.getElementById(mdp);
    var pass2 = document.getElementById(mdp2);
    var message = document.getElementById('confirmMessage');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    if(pass1.value == pass2.value){
        pass2.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "Mot de passe correct";
        return true;
    }else{
        pass2.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Non-identiques";
        return false;
    }
} 

function checkPatern(mdp) {
    var pass1 = document.getElementById(mdp);
    var message = document.getElementById('confirmPatern');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    var re = /^(?=.*\d)(?=.*[a-zA-z]).{6,16}$/;
    var tester = re.test(pass1.value);
    if(tester) {
        pass1.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "Mot de passe correct";
        return true;
    } else {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Mot de passe incorrect";
        return false;
    }
}