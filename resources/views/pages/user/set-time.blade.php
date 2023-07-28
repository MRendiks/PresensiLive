@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Set Time and Location') }}</div>

                <div class="card-body">
                    <form id="set-time-form">
                        @csrf

                        <!-- Time Section -->
                        <div class="section">
                            <h3>Time</h3>


                            <div class="form-group">
                                <label for="clock_in">Clock In Time</label>
                                <input type="time" class="form-control" id="clock_in" name="clock_in" value="{{ $time->clock_in ?? '' }}" required>
                            </div>

                            <div class="form-group">
                                <label for="clock_in_dispensation">Clock In Dispensation</label>
                                <input type="time" class="form-control" id="clock_in_dispensation" name="clock_in_dispensation" value="{{ $time->clock_in_dispensation ?? '' }}" required>
                            </div>

                            <div class="form-group">
                                <label for="clock_out">Clock Out Time</label>
                                <input type="time" class="form-control" id="clock_out" name="clock_out" value="{{ $time->clock_out ?? '' }}" required>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="section">
                            <h3>Location</h3>
                            <input type="hidden" id="latitude" name="latitude" value="{{ $latitude }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ $longitude }}">
                            <!-- Add a map container -->
                            <div id="map" style="height: 400px;"></div>

                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">
                            <!-- <input type="hidden" id="radius" name="radius"> -->
                        </div>

                        <div class="form-group">
                            <label for="radius">Radius</label>
                            <input type="number" class="form-control" id="radius" name="radius" value="{{ $radius }}" required>
                        </div>


                        <button type="submit" class="btn btn-primary">Save Time and Location</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Leaflet CSS and JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css">
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
    $(document).ready(function() {
        // Retrieve the initial latitude and longitude values
        var initialLatitude = parseFloat($('#latitude').val());
        var initialLongitude = parseFloat($('#longitude').val());
        var initialRadius = parseFloat($('#radius').val());
        console.log("latitude:", initialLatitude);
        console.log("asdasdas:", initialRadius);
        if (isNaN(initialRadius)) {
            initialRadius = 0;
        }

        // Check if initial latitude and longitude values are valid
        if (isNaN(initialLatitude) || isNaN(initialLongitude)) {
            // Default to a fallback latitude and longitude if values are not valid
            initialLatitude = 0;
            initialLongitude = 0;
        }

        // Initialize the map with the initial latitude and longitude values
        const map = L.map('map').setView([initialLatitude, initialLongitude], 2);

        // Add a tile layer to the map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Create a marker with the initial latitude and longitude values
        let marker = L.marker([initialLatitude, initialLongitude]).addTo(map);

        // Add a marker when the user clicks on the map
        let circle = L.circle([initialLatitude, initialLongitude], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: initialRadius
        }).addTo(map);
        map.on('click', function(event) {
            let latitude = event.latlng.lat;
            let longitude = event.latlng.lng;

            // Console log the latitude and longitude values
            console.log('Latitude:', latitude);
            console.log('Longitude:', longitude);

            // Update the marker's position
            marker.setLatLng([latitude, longitude]);



            // Update the hidden input fields with the latitude and longitude values
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
            initialLatitude = latitude;
            initialLongitude = longitude;
            circle.setLatLng([latitude, longitude]);
        });



        $('#radius').on('input', function() {
            let radius = parseFloat($(this).val());

            // Check if the radius value is valid
            if (isNaN(radius)) {
                // Default to a fallback radius if the value is not valid
                radius = 0;
            }

            // Update the circle's radius
            circle.setRadius(radius);
        });


        // Handle form submit using AJAX
        $('#set-time-form').on('submit', function(event) {
            event.preventDefault();
            const clockInValue = $('#clock_in').val();
            const clockOutValue = $('#clock_out').val();
            const clockInDispensationValue = $('#clock_in_dispensation').val();
            const radiusValue = $('#radius').val();
            // Store the form data
            const formData = $(this).serialize();

            // Perform AJAX request to save the time and location
            $.ajax({
                url: '{{ route("save-time") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include the CSRF token
                    clock_in: clockInValue,
                    clock_out: clockOutValue,
                    clock_in_dispensation: clockInDispensationValue,
                    latitude: initialLatitude,
                    longitude: initialLongitude,
                    radius: radiusValue
                },
                success: function(response) {
                    // Show success message
                    toastr.success('Time and location saved successfully');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR); // Log the error response to the console
                    console.log(textStatus); // Log the error status
                    console.log(errorThrown); // Log the specific error message

                    toastr.error('Failed to save time: ' + textStatus);
                }
            });

            // Clear the input fields
            $('#latitude').val('');
            $('#longitude').val('');
        });
    });
</script>
@endsection