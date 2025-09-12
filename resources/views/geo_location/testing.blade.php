<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Jobs Map — Physical & Remote</title>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <style>
    /* Basic responsive layout */
    body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; margin:0; }
    .app { display:flex; flex-direction:column; height:100vh; }
    .topbar { display:flex; align-items:center; gap:12px; padding:10px; border-bottom:1px solid #eee; background:#fff; }
    .title { font-weight:600; font-size:1rem; }
    .controls { margin-left:auto; display:flex; gap:8px; align-items:center; }
    .map-and-list { display:flex; flex:1; overflow:hidden; }
    .map { flex:1; min-height:0; } /* allow flex to shrink on mobile */
    .side { width:360px; max-width:40%; border-left:1px solid #eee; overflow:auto; background:#fafafa; }

    /* Mobile layout */
    @media (max-width:900px){
      .map-and-list{ flex-direction:column; }
      .side{ width:100%; max-width:100%; border-left:0; border-top:1px solid #eee; height:40vh; }
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

    .range-label{ font-size:13px; }
  </style>
</head>
<body>
  <div id="app" class="app">
    <div class="topbar">
      <div class="title">Jobs Map</div>

      <div style="display:flex; gap:8px; margin-left:16px;">
        {{-- it has to be Physical Jobs --}}
        <button class="btn" :class="{primary:activeTab==='map'}" @click="activeTab='map'">Map</button> 
        <button class="btn" :class="{primary:activeTab==='remote'}" @click="activeTab='remote'">Remote Jobs</button>
      </div>

      <div class="controls">
        <div style="display:flex;align-items:center;gap:8px;">
          <label style="display:flex;align-items:center;gap:6px;">
            <input type="checkbox" v-model="filterPhysicalOnly" />
            <small>Physical only</small>
          </label>
        </div>

        <div style="display:flex;flex-direction:column;align-items:flex-end;">
          {{-- <div class="range-label">Radius: {{ radiusKm }} km</div> --}}
          <input type="range" min="1" max="200" v-model.number="radiusKm" />
        </div>

        <button class="btn" @click="findMe">Find Me</button>
      </div>
    </div>
{{-- ================================================ --}}
    <div class="map-and-list">
      <div class="map" v-show="activeTab==='map'">
        <div id="map"></div>
      </div>

      <!-- Side panel / list: shows filtered jobs or remote-only when remote tab active -->
      <div class="side">
        <div class="filter-row">
          <div style="font-weight:600">Showing</div>
          {{-- <div style="color:#666">{{ filteredJobs.length }} jobs</div> --}}
        </div>

        <div v-for="job in filteredJobs" :key="job.id" class="job-item" @click="panToJob(job)">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
              {{-- <div style="font-weight:600">{{ job.title }}</div> --}}
              {{-- <div style="font-size:13px;color:#666">{{ job.address || job.type }}</div> --}}
            </div>
            {{-- <div>
              <span :class="['badge', job.type === 'physical' ? 'physical' : 'remote']">{{ job.type }}</span>
              <div style="font-size:12px;color:#333;margin-top:6px;text-align:right;">{{ formatDistance(job.distance) }}</div>
            </div> --}}
          </div>
        </div>

        <div v-if="filteredJobs.length===0" style="padding:12px;color:#666">
          No jobs match the current filters.</div>
      </div>
    </div>
  </div>

  <!-- Vue (CDN) -->
  <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
  const { createApp, ref, reactive, onMounted } = Vue;

  createApp({
    data(){ //this acts as like an app's memory
      return {
        activeTab: 'map',
        filterPhysicalOnly: false,
        radiusKm: 50,
        map: null,
        markersLayer: null,
        userLocation: null,
        jobList: [], //this will be populated from server-side variable
      }
    },
    computed: {
      filteredJobs(){
        // If remote tab, show remote-only list (no map markers)
        if(this.activeTab === 'remote'){
          return this.jobList.filter(j => j.type === 'remote').map(j => ({...j, distance: null}));
        }

        // For map tab: filter by physical toggle and radius
        let list = this.jobList.slice();
        if(this.filterPhysicalOnly){
          list = list.filter(j => j.type === 'physical');
        }

        if(this.userLocation){
          list = list.map(j => ({...j, distance: this.distanceKm(this.userLocation.lat, this.userLocation.lng, j.lat, j.lng)}));
          list = list.filter(j => j.distance <= this.radiusKm);
        } else {
          // If no location, set distance null and only filter by type
          list = list.map(j => ({...j, distance: null}));
        }

        return list.sort((a,b)=> (a.distance ?? 1e9) - (b.distance ?? 1e9));
      }
    },
    methods: {
      initMap(){
        this.map = L.map('map', { zoomControl: true });
        // default view (world)
        this.map.setView([0,0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; OpenStreetMap contributors'
        }).addTo(this.map);

        this.markersLayer = L.layerGroup().addTo(this.map);

        // click to set as user location (for quick testing)
        this.map.on('click', (e)=>{
          this.setUserLocation(e.latlng.lat, e.latlng.lng);
        });

        // redraw markers when map ready or when filters change
        this.$watchers = [];
        this.$watchers.push(this.$watch('filterPhysicalOnly', () => this.renderMarkers()));
        this.$watchers.push(this.$watch('radiusKm', () => this.renderMarkers()));
        this.$watchers.push(this.$watch('activeTab', () => this.onTabChange()));
      },

      onTabChange(){
        if(this.activeTab==='map'){
          // show map container and redraw markers
          setTimeout(()=> this.map.invalidateSize(), 300);
          this.renderMarkers();
        } else {
          // remote tab selected: clear markers
          this.markersLayer.clearLayers();
        }
      },

      renderMarkers(){
        if(!this.map) return;
        this.markersLayer.clearLayers();

        if(this.activeTab !== 'map') return;

        // determine list to show on map (physical filter + radius if user loc exists)
        const jobsToShow = this.filteredJobs;

        jobsToShow.forEach(job => {
          if(job.type === 'remote') return; // do not show remote on map (unless you want a different icon and place)

          const marker = L.marker([job.lat, job.lng], { title: job.title });

          const distanceText = job.distance != null ? `<div><strong>${this.formatDistance(job.distance)}</strong></div>` : '';
          const popup = `<div style="min-width:140px"><div style="font-weight:600">${job.title}</div><div style="font-size:13px;color:#666">${job.address || ''}</div>${distanceText}</div>`;

          marker.bindPopup(popup);
          marker.on('click', ()=> marker.openPopup());
          marker.addTo(this.markersLayer);
        });

        // If we have markers, fit map to bounds (with padding)
        const layers = this.markersLayer.getLayers();
        if(layers.length){
          const group = L.featureGroup(layers);
          this.map.fitBounds(group.getBounds().pad(0.2));
        }
      },

      panToJob(job){
        if(this.map && job.lat && job.lng){
          this.map.setView([job.lat, job.lng], 14);
          // open the marker popup if exists
          const found = this.markersLayer.getLayers().find(m => {
            const latlng = m.getLatLng();
            return Math.abs(latlng.lat - job.lat) < 1e-6 && Math.abs(latlng.lng - job.lng) < 1e-6;
          });
          if(found) found.openPopup();
        }
      },

      findMe(){
        if(!navigator.geolocation){ alert('Geolocation not supported by your browser'); return; }
        navigator.geolocation.getCurrentPosition(pos => {
          const { latitude, longitude } = pos.coords;
          this.setUserLocation(latitude, longitude);
          this.map.setView([latitude, longitude], 13);
        }, err => {
          alert('Unable to retrieve your location. Make sure location is enabled.');
          console.error(err);
        }, { enableHighAccuracy: true, timeout: 10000 });
      },

      setUserLocation(lat, lng){
        this.userLocation = { lat, lng };

        // draw a circle representing the radius
        if(this._userCircle){ this.map.removeLayer(this._userCircle); }
        if(this._userMarker){ this.map.removeLayer(this._userMarker); }

        this._userMarker = L.circleMarker([lat,lng], { radius:8, fill:true, fillOpacity:1 }).addTo(this.map).bindPopup('You are here');
        this._userCircle = L.circle([lat,lng], { radius: this.radiusKm*1000, color:'#0b74de', fill:false, dashArray:'4 6' }).addTo(this.map);

        this.renderMarkers();
      },

      // Utility: Haversine distance in km (digit-by-digit precision requirement observed)
      distanceKm(lat1, lon1, lat2, lon2){
        const toRad = (deg) => deg * Math.PI / 180;
        const R = 6371.0088; // Earth's radius in km (mean)
        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return +(R * c).toFixed(2);
      },

      formatDistance(km){
        if(km == null) return '--';
        if(km < 1) return `${Math.round(km*1000)} m`;
        return `${km.toFixed(2)} km`;
      }
    },
    mounted(){
  
      const serverJobs = window.SERVER_JOBS || [
        { id:1, title:'Cashier(Retail)', type:'physical', lat:-26.2041, lng:28.0473, address:'Johannesburg CBD' },
        { id:2, title:'Frontend Developer (Remote)', type:'remote', address:'Remote Job' },
        { id:3, title:'Warehouse Operative', type:'physical', lat:-25.7479, lng:28.2293, address:'Pretoria' },
        { id:4, title:'Sales Representative', type:'physical', lat:-26.1076, lng:28.0567, address:'Sandton' },
        { id:5, title:'IT Support Technican', type:'physical', lat:-25.9992, lng:28.1260, address:'Midrand'}
      ];

      // normalize lat/lng presence
      this.jobList = serverJobs.map(j=>({ ...j, lat: j.lat ?? null, lng: j.lng ?? null }));

      this.initMap();
      this.renderMarkers();
    }
  }).mount('#app');
  </script>
</body>
</html>
