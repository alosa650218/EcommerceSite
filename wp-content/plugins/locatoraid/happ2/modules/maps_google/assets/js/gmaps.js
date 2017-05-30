jQuery(document).ready( function()
{
	var this_js_url = '//maps.googleapis.com/maps/api/js';

	var api_key = hc2_gmaps_vars['api_key'];
	if( api_key ){
		this_js_url += '?&key=' + api_key;
	}

	if( (typeof google === 'object') && (typeof google.maps === 'object') ){
		jQuery(document).trigger('hc2-gmaps-loaded');
		return;
	}
	else {
		jQuery.getScript( this_js_url, function() {
			jQuery(document).trigger('hc2-gmaps-loaded');
		});
	}
});

function hc2_geocode( address, callback ){
	var more_pass_args = [];
	if( arguments.length > 2 ){
		for( var ii = 2; ii < arguments.length; ii++ ){
			more_pass_args.push( arguments[ii] );
		}
	}

	if( ! ((typeof google === 'object') && (typeof google.maps === 'object')) ){
		var is_success = false;
		var status = 0;
		var msg = 'Google Maps Not Loaded!';
		// callback( is_success, msg, status );
		var pass_args = [is_success, msg, status];
		pass_args = pass_args.concat( more_pass_args );
		callback.apply( this, pass_args );
		return false;
	}

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode(
		{ 'address': address },
		function( geo_results, status ){
			var is_success = false;
			var results = '';

			// alert( address );

			switch( status ){
				case google.maps.GeocoderStatus.OVER_QUERY_LIMIT :
					results = 'Daily limit reached, please try tomorrow';
					break;

				case google.maps.GeocoderStatus.ZERO_RESULTS:
					results = 'Address not found';
					break;

				case google.maps.GeocoderStatus.OK:
					is_success = true;
					var this_loc = geo_results[0].geometry.location;
					results = {
						lat: this_loc.lat(),
						lng: this_loc.lng(),
						};
					break;

				default :
					results = '' + status;
					if( geo_results["message"] && geo_results["message"].length ){
						results += ', ' + geo_results["message"];
					}
					results += ', ' + 'you may probably need to check your Google Maps API Key';
					break;
			}

			// callback( is_success, results, status );
			var pass_args = [is_success, results, status];
			pass_args = pass_args.concat( more_pass_args );
			callback.apply( this, pass_args );
		}
	);
}
