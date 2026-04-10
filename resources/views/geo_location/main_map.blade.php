<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Job Location Map</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  
  <style>
    body, html { margin: 0; padding: 0; height: 100%; font-family: sans-serif; }
    #app { display: flex; flex-direction: column; height: 100vh; }
    .header { padding: 15px; background: #fff; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
    .map-container { flex-grow: 1; position: relative; }
    #map { position: absolute; top: 0; bottom: 0; left: 0; right: 0; }
    .btn { padding: 8px 16px; background: #0b74de; color: white; border: none; border-radius: 4px; cursor: pointer; }
  </style>
</head>
<body>

<div id="app">
    <div class="header">
        <strong>Job: {{ $job->title }}</strong>
        <button class="btn" @click="findMe">📍 Distance from me</button>
    </div>

    <div class="map-container">
        <div id="map"></div>
    </div>
</div>

@include('geo_location.mapScript', ['job' => $job])

</body>
</html>