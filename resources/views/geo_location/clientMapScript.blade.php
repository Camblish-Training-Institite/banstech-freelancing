<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            map: null,
            // Pass the Job (center point)
            job: {
                lat: @json((float)$job->location->latitude),
                lng: @json((float)$job->location->longitude),
                title: @json($job->title)
            },
            // Pass the geocoded proposals from the controller
            proposals: @json($proposals) 
        }
    },
    methods: {
        initMap() {
            // Initialize map centered on Job
            this.map = L.map('map').setView([this.job.lat, this.job.lng], 12);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(this.map);

            // Job Marker (Red/Center)
            L.marker([this.job.lat, this.job.lng])
                .addTo(this.map)
                .bindPopup(`<b>WORK SITE: ${this.job.title}</b>`)
                .openPopup();

            const bounds = L.latLngBounds([this.job.lat, this.job.lng]);

            // Add Freelancer Markers
            this.proposals.forEach(prop => {
                prop.distance = this.calculateDistance(this.job.lat, this.job.lng, prop.lat, prop.lng);
                
                L.circleMarker([prop.lat, prop.lng], {
                    radius: 8, color: '#0b74de', fillColor: '#0b74de', fillOpacity: 0.7
                })
                .addTo(this.map)
                .bindPopup(`<b>${prop.name}</b><br>Bid: R${prop.bid}<br>${prop.distance}km away<br> <a href="/client/freelancer/${prop.user_id}/profile" style="display:flex; align-items: center; justify-content:center; padding: 0.25rem 0.5rem; background-color: #0b74de; color: #fff; border-radius: 4px; margin: 0.25rem 0; text-decoration: none;">View Profile</a>`);

                bounds.extend([prop.lat, prop.lng]);
            });

            // Zoom out to show all applicants
            if (this.proposals.length > 0) {
                this.map.fitBounds(bounds, { padding: [70, 70] });
            }

            setTimeout(() => { this.map.invalidateSize(); }, 400);
        },

        calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; 
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return (R * c).toFixed(1);
        }
    },
    mounted() {
        this.$nextTick(() => { this.initMap(); });
    }
}).mount('#app');
</script>



{{-- // 1. Add Job Marker (Red)
            const jobIcon = L.divIcon({className: 'job-marker-icon', html: '📍', iconSize: [30, 30]});
            L.marker([this.job.lat, this.job.lng], {icon: jobIcon})
                .addTo(this.map)
                .bindPopup(`<b>Center: ${this.job.title}</b>`)
                .openPopup(); --}}