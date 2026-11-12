// CivicMind X - Leaflet GIS Heatmap Controller
document.addEventListener('DOMContentLoaded', () => {
    const mapElement = document.getElementById('civic-gis-map');
    if (!mapElement || typeof L === 'undefined') return;

    // Initialize Map centered on Chennai / Metro City
    const map = L.map('civic-gis-map').setView([13.0827, 80.2707], 12);

    // Free Dark Basemap Layer (No API Key Required)
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; CivicMind X GIS Engine & Esri, OpenStreetMap',
        maxZoom: 18
    }).addTo(map);

    let markersLayer = L.layerGroup().addTo(map);

    async function loadGeoJson(category = '') {
        try {
            markersLayer.clearLayers();
            const res = await fetch(`/api/heatmaps/geojson?category=${encodeURIComponent(category)}`);
            const geojson = await res.json();

            if (geojson.features) {
                geojson.features.forEach(feature => {
                    const [lng, lat] = feature.geometry.coordinates;
                    const props = feature.properties;

                    // Color coding by severity
                    const color = props.severity >= 4 ? '#f43f5e' : (props.severity >= 3 ? '#f59e0b' : '#3b82f6');

                    const circle = L.circleMarker([lat, lng], {
                        radius: 10 + (props.severity * 2),
                        fillColor: color,
                        color: '#ffffff',
                        weight: 1.5,
                        opacity: 1,
                        fillOpacity: 0.75
                    });

                    circle.bindPopup(`
                        <div style="font-family: sans-serif; color: #0f172a; padding: 4px;">
                            <span style="background: ${color}; color: #fff; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: bold;">
                                ${props.category} (Severity ${props.severity}/5)
                            </span>
                            <h4 style="margin: 8px 0 4px 0; font-size: 14px;">${props.title}</h4>
                            <div style="font-size: 12px; color: #64748b;">Status: <strong>${props.status}</strong></div>
                        </div>
                    `);

                    markersLayer.addLayer(circle);
                });
            }
        } catch (err) {
            console.error('Heatmap load error:', err);
        }
    }

    loadGeoJson();

    // Category Filter Buttons
    const filterBtns = document.querySelectorAll('.heatmap-filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active', 'btn-primary'));
            btn.classList.add('active', 'btn-primary');
            const cat = btn.getAttribute('data-category') || '';
            loadGeoJson(cat);
        });
    });
});
