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
    .topbar { padding: 15px; background: #fff; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    .title { font-weight: 700; color: #0f172a; }
    .subtitle { font-size: 12px; color: #64748b; margin-top: 4px; }
    .controls { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .badge { display:inline-flex; align-items:center; justify-content:center; padding:8px 12px; border-radius:999px; background:#eef2ff; color:#1d4ed8; font-size:12px; font-weight:600; }
    .close-btn { display:inline-flex; align-items:center; justify-content:center; padding:10px 16px; background:#475569; color:#fff; text-decoration:none; border-radius:4px; }
    .map-and-list { display: flex; flex: 1; height: calc(100vh - 74px); min-height: 0; }
    #map { flex: 3; height: 100%; }
    .side { flex: 1; border-left: 1px solid #ddd; overflow-y: auto; background: #f9fafb; }
    .job-item { padding: 14px 16px; border-bottom: 1px solid #e5e7eb; cursor: pointer; }
    .job-item:hover { background: #eff6ff; }
    @media (max-width: 900px) {
      .map-and-list { flex-direction: column; height: auto; }
      #map { min-height: 52vh; }
      .side { border-left: none; border-top: 1px solid #ddd; }
    }
    @media (max-width: 640px) {
      .topbar { padding: 12px; }
      .controls { width: 100%; display: grid; grid-template-columns: 1fr; }
      .close-btn, .badge { width: 100%; }
      .job-item { padding: 12px; }
    }
  </style>
</head>
<body>

    <div id="app" class="app">
        <div class="topbar">
            <div>
                <div class="title">Applicants for: {{ $job->title }}</div>
                <div class="subtitle">Review proposal locations and close the map when you are done.</div>
            </div>
            <div class="controls">
                <span class="badge physical">{{ $job->proposals->count() }} Proposals</span>
                <a href="{{ route('client.jobs.show', $job) }}" class="close-btn">Close Map</a>
            </div>
        </div>

        <div class="map-and-list">
            <div id="map"></div>

            <div class="side">
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
