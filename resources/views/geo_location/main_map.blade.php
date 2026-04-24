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
    .header { padding: 15px; background: #fff; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    .map-container { flex-grow: 1; position: relative; }
    #map { position: absolute; top: 0; bottom: 0; left: 0; right: 0; }
    .btn { padding: 10px 16px; background: #0b74de; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
    .btn.secondary { background: #475569; }
    .header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    @media (max-width: 640px) {
      .header { padding: 12px; }
      .header-actions { width: 100%; display: grid !important; grid-template-columns: 1fr; }
      .btn { width: 100%; text-align: center; }
    }
  </style>
</head>
<body>

<div id="app">
    <div class="header">
        <div>
            <strong>Job: {{ $job->title }}</strong>
            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                On-site location preview
                @if ($job->freelancer_radius)
                    • {{ (int) $job->freelancer_radius }} km preferred radius
                @endif
            </div>
        </div>
        <div class="header-actions">
            <a class="btn secondary" href="{{ route('freelancer.jobs.show', $job->id) }}">
                Close Map
            </a>
            <a class="btn" href="https://www.google.com/maps/search/?api=1&query={{ $job->location ? $job->location->latitude : 0 }},{{ $job->location ? $job->location->longitude : 0 }}" target="_blank" rel="noopener noreferrer">
                Open in Google Maps
            </a>
            <button class="btn" @click="findMe">Distance from me</button>
        </div>
    </div>

    <div class="map-container">
        <div id="map"></div>
    </div>
</div>

@include('geo_location.mapScript', ['job' => $job])

</body>
</html>
