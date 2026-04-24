@php
    $miniMapId = 'mini-map-' . ($job->id ?? uniqid());
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    .mini-map-shell {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
    }

    .mini-map-canvas {
        width: 100%;
        height: 260px;
    }

    .mini-map-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        border-top: 1px solid #e5e7eb;
    }

    .mini-map-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .mini-map-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 600;
    }

    .mini-map-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .mini-map-btn {
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .mini-map-btn.primary {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
</style>

<div class="mini-map-shell">
    <div id="{{ $miniMapId }}" class="mini-map-canvas"></div>

    <div class="mini-map-toolbar">
        <div class="mini-map-meta">
            <span class="mini-map-badge">On-site job</span>
            @if ($job->freelancer_radius)
                <span class="mini-map-badge">{{ (int) $job->freelancer_radius }} km radius</span>
            @endif
        </div>

        <div class="mini-map-actions">
            <button type="button" class="mini-map-btn primary" onclick="window.freelanceMiniMaps['{{ $miniMapId }}']?.findMe()">
                Distance from me
            </button>
            <button type="button" class="mini-map-btn" onclick="window.freelanceMiniMaps['{{ $miniMapId }}']?.focusJob()">
                Recenter
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    window.freelanceMiniMaps = window.freelanceMiniMaps || {};

    (function () {
        const mapId = @json($miniMapId);
        const job = {
            id: @json($job->id),
            title: @json($job->title),
            lat: @json($job->location ? (float) $job->location->latitude : 0),
            lng: @json($job->location ? (float) $job->location->longitude : 0),
            budget: @json((float) $job->budget),
            radiusKm: @json((int) ($job->freelancer_radius ?? 0)),
        };

        if (!job.lat || !job.lng) {
            return;
        }

        const map = L.map(mapId, {
            zoomControl: true,
            scrollWheelZoom: false,
        }).setView([job.lat, job.lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
        }).addTo(map);

        const jobMarker = L.marker([job.lat, job.lng]).addTo(map);
        jobMarker.bindPopup(`<strong>${job.title}</strong><br>Budget: R${job.budget.toFixed(2)}`).openPopup();

        let jobCircle = null;
        if (job.radiusKm > 0) {
            jobCircle = L.circle([job.lat, job.lng], {
                radius: job.radiusKm * 1000,
                color: '#2563eb',
                fillColor: '#93c5fd',
                fillOpacity: 0.15,
            }).addTo(map);
        }

        let userMarker = null;

        const focusJob = () => {
            if (jobCircle) {
                map.fitBounds(jobCircle.getBounds(), { padding: [20, 20] });
                return;
            }

            map.setView([job.lat, job.lng], 13);
        };

        const findMe = () => {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported on this device.');
                return;
            }

            navigator.geolocation.getCurrentPosition(position => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                userMarker = L.circleMarker([latitude, longitude], {
                    radius: 8,
                    fillColor: '#7c3aed',
                    color: '#fff',
                    weight: 2,
                    fillOpacity: 1,
                }).addTo(map).bindPopup('You are here').openPopup();

                const bounds = L.latLngBounds([[job.lat, job.lng], [latitude, longitude]]);
                map.fitBounds(bounds, { padding: [40, 40] });
            }, () => {
                alert('We could not access your location.');
            });
        };

        window.freelanceMiniMaps[mapId] = {
            findMe,
            focusJob,
        };

        setTimeout(() => map.invalidateSize(), 250);
    })();
</script>
