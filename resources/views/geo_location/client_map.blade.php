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

    <div id="app" class="app">
        <div class="topbar">
            <div class="title">Applicants for: {{ $job->title }}</div>
            <div class="controls">
                <span class="badge physical">{{ $job->proposals->count() }} Proposals</span>
            </div>
        </div>

        <div class="map-and-list" style="display: flex; flex: 1; height: calc(100vh - 60px);">
            <div id="map" style="flex: 3; height: 100%;"></div>

            <div class="side" style="flex: 1; border-left: 1px solid #ddd; overflow-y: auto; background: #f9f9f9;">
                <div v-for="prop in proposals" :key="prop.id" 
                    class="job-item" @click="focusOnFreelancer(prop)">
                    <div style="font-weight:600">@{{ prop.name }}</div>
                    <div style="font-size:12px; color:#666;">Bid: R@{{ prop.bid }}</div>
                    <div style="font-size:11px; color:#0b74de;">Distance: @{{ prop.distance }} km away</div>
                </div>
            </div>
        </div>
    </div>

    @include('geo_location.clientMapScript', ['job' => $job])
</body>
</html>