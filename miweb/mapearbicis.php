<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Mapa de Estaciones Valenbisi JDG</title>
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
 <style>
    #map { height: 600px; width: 100%; margin-top: 20px; }
    body { margin: 0; font-family: Arial, sans-serif; text-align: center; background-color: blue; }
    h1 {
        color: #9b00ff; /* morado claro y legible */
        font-size: 32px;
        font-weight: bold;
        margin-top: 20px;
        font-family: 'Arial', sans-serif;
        text-shadow:
          0 0 2px #e0aaff,
          0 0 4px #c77dff,
          0 0 6px #a23bff,
          0 0 10px #9100ff;
 }
 </style>
</head>
<body>
 <h1>Mapeo de Bicicletas en Valencia</h1>
 <div id="map"></div>
 <script>
 // Inicializa el mapa centrado en Valencia
 var map = L.map('map').setView([39.47, -0.37], 13);

 // Añadir capa base de OpenStreetMap
 L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
   attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
 }).addTo(map);

 // Función para obtener el icono tipo chincheta según disponibilidad
 function getPinIcon(available) {
   let iconUrl;
   if (available < 1) {
     iconUrl = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
   } else if (available >= 1 && available < 7) {
     iconUrl = 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png';
   } else if (available >= 7 && available < 15) {
     iconUrl = 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
   } else {
     iconUrl = 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
   }

   return L.icon({
     iconUrl: iconUrl,
     iconSize: [32, 32],
     iconAnchor: [16, 32],
     popupAnchor: [0, -32]
   });
 }

 // Cargar el archivo data.json
 fetch('data.json')
   .then(response => {
     if (!response.ok) {
       throw new Error(`Error al cargar data.json: ${response.statusText}`);
     }
     return response.json();
   })
   .then(data => {
     // Iterar sobre las estaciones y agregar marcadores al mapa
     Object.values(data).forEach(station => {
       const { lat, lon, address, available, free, total } = station;
       if (lat && lon) {
         L.marker([lat, lon], {
           icon: getPinIcon(available)
         })
         .addTo(map)
         .bindPopup(`
           <strong>${address}</strong><br>
           <b>Disponibles:</b> ${available}<br>
           <b>Libres:</b> ${free}<br>
           <b>Total:</b> ${total}
         `);
       }
     });
   })
   .catch(error => {
     console.error('Error cargando los datos:', error);
   });
 </script>
</body>
</html>
