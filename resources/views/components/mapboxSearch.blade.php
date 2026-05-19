<script>
    mapboxgl.accessToken = '{{ config('services.mapbox.token') }}';
    
    const searchInput = document.getElementById('job_address');
    const suggestionsContainer = document.getElementById('suggestions-container');
    
    // NOTE: The original file had a Mapbox Geocoder instance that could conflict
    // with this custom suggestion implementation. The problematic line has been
    // removed to ensure your manual fetch logic works reliably.
    
    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const query = searchInput.value;
        
            if (query.length < 3) { // Only search if 3+ characters are typed
                suggestionsContainer.style.display = 'none';
                return;
            }
    
            fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${mapboxgl.accessToken}&types=country,place,postcode,district,locality,neighborhood,address&limit=5&country=ZA`)
                .then(response => response.json())
                .then(data => {
                    suggestionsContainer.innerHTML = '';
                    console.log(data.features);
        
                    if (data.features.length > 0) {
                        data.features.forEach(feature => {
                            const suggestion = document.createElement('div');
                            suggestion.textContent = feature.place_name;
                            suggestion.classList.add('suggestion');
                            suggestion.addEventListener('click', () => {
                                searchInput.value = feature.place_name;
                                suggestionsContainer.style.display = 'none';
                            });
                            suggestionsContainer.appendChild(suggestion);
                        });
                        suggestionsContainer.style.display = 'block';
                    } else {
                        suggestionsContainer.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching suggestions:', error);
                    suggestionsContainer.style.display = 'none';
                });
        }, 300); // 300ms debounce delay
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', (event) => {
        if (!searchInput.contains(event.target)) {
            suggestionsContainer.style.display = 'none';
        }
    });
</script>