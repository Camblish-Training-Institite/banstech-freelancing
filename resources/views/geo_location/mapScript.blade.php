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
        // default view (South Africa)
        this.map.setView([-30.5595,22.9375], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
         
        }).addTo(this.map);

        this.markersLayer = L.layerGroup().addTo(this.map);

        
        // click to set as user location (for quick testing)
        // this.map.on('click', (e)=>{
        //   this.setUserLocation(e.latlng.lat, e.latlng.lng);
        // });
 

        // redraw markers when map ready or when filters change
        // this.$watchers = [];
        // this.$watchers.push(this.$watch('filterPhysicalOnly', () => this.renderMarkers()));
        // this.$watchers.push(this.$watch('radiusKm', () => this.renderMarkers()));
        // this.$watchers.push(this.$watch('activeTab', () => this.onTabChange()));
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