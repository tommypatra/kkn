<html lang="en">

<head>
    <title>Multiple Markers</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <style>
        body {
            padding: 0;
            margin: 0;
        }

        #peta {
            height: 100vh;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="peta"></div>
</body>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script>
    var map = L.map('peta').setView([-4.008333, 119.629185], 13)
    L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
        maxZoom: 20,
        attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var sample_json = {
        "type": "FeatureCollection",
        "features": [{
            "type": "Feature",
            "properties": {},
            "geometry": {
                "type": "Point",
                "coordinates": [
                    119.624899,
                    -4.014473
                ]
            }
        }, ]
    }

    L.geoJSON(sample_json).addTo(map)
        .bindPopup('A pretty CSS3 popup.<br> Easily customizable.');

    L.marker([-4.003509, 119.621987]).addTo(map)
        .bindPopup('Mantap')
        .openPopup();
</script>

</html>