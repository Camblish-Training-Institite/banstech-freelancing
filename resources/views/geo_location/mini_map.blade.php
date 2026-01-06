<!doctype html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Jobs Map — Physical & Remote</title>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <style>
    /* Basic responsive layout */
    body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; margin:0; }
    .app { display:flex; flex-direction:column; height:100%; }
    .topbar { display:flex; align-items:center; gap:12px; padding:10px; border-bottom:1px solid #eee; background:#fff; }
    .title { font-weight:600; font-size:1rem; }
    .controls { margin-left:auto; display:flex; gap:8px; align-items:center; }
    .map-and-list { display:flex; flex:1; overflow:hidden; flex-direction: column; }
    .map { flex:1; min-height:0; } /* allow flex to shrink on mobile */
    .side { width:360px; max-width:40%; border-left:1px solid #eee; overflow:auto; background:#fafafa; }
    .container {width: 100%; height: 100%; position: relative;}
    /* Mobile layout */
    @media (max-width:900px){
      .map-and-list{ flex-direction:column; }
      .side{ width:100%; max-width:100%; border-left:0; border-top:1px solid #eee; height:40vh; }
    }
.map-widget-container {
    width: 100%; /* Fill the available sidebar width */
    height: 300px; /* Option 1: A fixed height */
    /* OR, a better option for maintaining shape: */
    aspect-ratio: 16 / 9; /* Keeps a widescreen shape */
    
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden; /* Clips the map to the rounded corners */
}
    #map { width:100%; height:100%; }

    .controls .btn { padding:8px 10px; border-radius:8px; border:1px solid #ddd; background:#fff; cursor:pointer; }
    .controls .btn.primary{ background:#0b74de; color:#fff; border-color:transparent; }

    .filter-row{ display:flex; gap:10px; align-items:center; padding:8px; }
    .job-item{ padding:10px; border-bottom:1px solid #eee; cursor:pointer; }
    .job-item:hover{ background:#fff; }
    .badge { display:inline-block; padding:3px 6px; border-radius:6px; font-size:12px; }
    .badge.physical{ background:#e6f4ff; color:#0366d6; }
    .badge.remote{ background:#f3e8ff; color:#6b21a8; }

    /* .range-label{ font-size:13px; } */
  </style>
</head>
<body>
  <div id="app">
    <div class="map-widget-container">
        <div id="map" style="height: 300px; border-radius: 8px;"></div>
    </div>
    
    <div class="flex justify-between items-center mt-2">
        <button @click="findMe" class="btn primary" style="font-size: 12px; padding: 5px 10px;">
            📍 Distance from me
        </button>
        <span v-if="userLocation" class="text-xs text-gray-500">
            Map updated with your location
        </span>
    </div>
</div>

        

  <!-- Vue (CDN) -->
  <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

 @include('geo_location/mapScript', ['job' => $job ?? null])
</body>
</html>
