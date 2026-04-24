{{-- Prepare data safely in PHP first --}}
@php
  $jobData = [
      'id' => $job->id,
      'title' => $job->title,
      'lat' => $job->location ? (float)$job->location->latitude : 0,
      'lng' => $job->location ? (float)$job->location->longitude : 0,
      'budget' => $job->budget,
      'radius_km' => (int) ($job->freelancer_radius ?? 0),
  ];
@endphp

<script>
  const { createApp } = Vue;

  createApp({
    data() {
      return {
        map: null,
        userMarker: null,
        jobCircle: null,
        job: @json($jobData)
      }
    },
    methods: {
      initMap() {
        // 1. Check if coordinates exist
        if (this.job.lat === 0) {
            console.error("Map Error: Job has no coordinates.");
            return;
        }

        // 2. Initialize Map
        this.map = L.map('map', {
            zoomControl: true,
        }).setView([this.job.lat, this.job.lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(this.map);

        // 3. Add Job Marker
        L.marker([this.job.lat, this.job.lng])
            .addTo(this.map)
            .bindPopup(`
              <b>${this.job.title}</b><br>
              Budget: R${this.job.budget}<br>
              <a href="https://www.google.com/maps/search/?api=1&query=${this.job.lat},${this.job.lng}" target="_blank" rel="noopener noreferrer">
                Open in Google Maps
              </a>
            `)
            .openPopup();

        if (this.job.radius_km > 0) {
          this.jobCircle = L.circle([this.job.lat, this.job.lng], {
            radius: this.job.radius_km * 1000,
            color: '#2563eb',
            fillColor: '#93c5fd',
            fillOpacity: 0.15,
          }).addTo(this.map);

          this.map.fitBounds(this.jobCircle.getBounds(), { padding: [30, 30] });
        }

        // 4. THE FIX: Force Leaflet to recalculate size after the DOM settles
        setTimeout(() => {
            this.map.invalidateSize();
        }, 300);
      },

      findMe() {
        if (!navigator.geolocation) return alert("Geolocation not supported");

        navigator.geolocation.getCurrentPosition(pos => {
          const { latitude, longitude } = pos.coords;

          if (this.userMarker) this.map.removeLayer(this.userMarker);

          this.userMarker = L.circleMarker([latitude, longitude], {
            radius: 10, fillColor: "#740bde", color: "#fff", weight: 2, fillOpacity: 1
          }).addTo(this.map).bindPopup("You are here").openPopup();

          // Compare both: Fit both markers in view
          const bounds = L.latLngBounds([[this.job.lat, this.job.lng], [latitude, longitude]]);
          this.map.fitBounds(bounds, { padding: [50, 50] });
        });
      }
    },
    mounted() {
      // Use nextTick to ensure Vue has finished rendering the HTML
      this.$nextTick(() => {
        this.initMap();
      });
    }
  }).mount('#app');
</script>
