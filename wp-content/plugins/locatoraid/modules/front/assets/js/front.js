(function($) {
var self = this;

var observers = function()
{
	var observers = [];

	this.add = function( item )
	{
		observers.push( item );
	}
	this.notify = function( what, payload )
	{
		for( var ii = 0; ii < observers.length; ii++ ){
			observers[ii].trigger( what, payload );
		}
	}
}

this.form = function( html_id )
{
	var self = this;
	this.observers = new observers;
	var $this = jQuery( '#' + html_id );

	this.search = function( search )
	{
		hc2_set_loader( $this );
		var search_string = search.search;
		search_string = search_string + '';
		var search_url = $this.attr('action');

		for( var k in search ){
			var to_replace = '_' + k.toUpperCase() + '_';
			var replace_to = search[k];
			if( Array.isArray(replace_to) ){
				replace_to = replace_to.join('|');
			}
			search_url = search_url.replace( to_replace, replace_to );
		}

		// search_url = search_url.replace( '_SEARCH_', search_string );

		var where = $this.data('where');
		if( where ){
			search_string = search_string + ' ' + where;
		}

		if( ! search_string.length ){
			search_url = search_url.replace( '_LAT_', '' );
			search_url = search_url.replace( '_LNG_', '' );

			self.do_search( search_url );
		}
		else {
		// now try to geocode the search
			hc2_geocode( 
				search_string,
				function( success, results, return_status )
				{
					if( success ){
						search_url = search_url.replace( '_LAT_', results.lat );
						search_url = search_url.replace( '_LNG_', results.lng );
					}
					else {
						search_url = search_url.replace( '_LAT_', '' );
						search_url = search_url.replace( '_LNG_', '' );
					}
					self.do_search( search_url );
				}
			);
		}
	}

	this.do_search = function( search_url )
	{
		console.log( search_url );

		jQuery.ajax({
			type: 'GET',
			url: search_url,
			dataType: "json",
			success: function(data, textStatus){
				self.observers.notify( 'get-results', data );
				hc2_unset_loader( $this );
			}
			})
			.fail( function(jqXHR, textStatus, errorThrown){
				hc2_unset_loader( $this );
				alert( 'Ajax Error: ' + search_url );
				alert( jqXHR.responseText );
				})
			;
	}

	this.submit = function( event )
	{
		event.stopPropagation();
		event.preventDefault();

		this_data = {};
		var this_form_array = $this.find('select, textarea, input, checkbox').serializeArray();
		for( var ii = 0; ii < this_form_array.length; ii++ ){
			var name = this_form_array[ii]['name'];
			name = name.substr(3); // strip 'hc-'

			if( name.substr(-2) == '[]' ){
				name = name.substr(0, name.length-2);
				if( ! this_data[name] ){
					this_data[name] = [];
				}
				this_data[name].push( this_form_array[ii]['value'] );
			}
			else {
				this_data[name] = this_form_array[ii]['value'];
			}
		}

		self.search( this_data );
	}

	$this.on('submit', this.submit );

	// var default_search = $this.find('input[name=hc-search]').val();
	var where = $this.data('where');
	var start = $this.data('start');

	// if( default_search || where ){
	if( where || (start != null) ){
		this.search( {'search': start} );
	}
}

this.list = function( html_id )
{
	var self = this;
	this.observers = new observers;
	var $this = jQuery( '#' + html_id );

	this.params = {
		'group'		:	$this.data('group'),
		'sort' 		:	$this.data('sort')
	};

	self.template = jQuery( '#' + html_id + '_template' ).html();
	this.entries = {};

	this.trigger = function( what, payload )
	{
		if( ! $this.length ){
			return;
		}

		switch( what ){
			case 'get-results':
				this.render( payload );
				break;
			case 'select-location':
				this.highlight( payload );
				this.scroll_to( payload );
				break;
		}
	}

	this.render = function( results )
	{
		if( ! $this.is(":visible") ){
			$this.show();
		}

		self.entries = {};
		var entries = results['results'];
		$this.html('');

		var group_by = this.params['group'];
		var groups = {};

		if( group_by ){
			for( var ii = 0; ii < entries.length; ii++ ){
				var this_loc = entries[ii];
				var this_group_label = this_loc[group_by];

				if( ! groups.hasOwnProperty(this_group_label) ){
					groups[this_group_label] = [];
				}
				groups[this_group_label].push(ii);
			}
		}
		else {
			var this_group_label = '';
			groups[this_group_label] = [];
			for( var ii = 0; ii < entries.length; ii++ ){
				groups[this_group_label].push(ii);
			}
		}

		var group_labels = Object.keys( groups );
		group_labels.sort(function(a, b){
			return a.localeCompare(b);
		})

		for( var kk = 0; kk < group_labels.length; kk++ ){
			var group_label = group_labels[kk];

			if( group_label.length ){
				var group_label_view = '<h4>' + group_label + '</h4>';
				$this.append( group_label_view );
			}

			for( var jj = 0; jj < groups[group_label].length; jj++ ){
				var ii = groups[group_label][jj];
				var this_loc = entries[ii];

				var template = new Hc2Template( self.template );
				var template_vars = this_loc;
				var this_loc_view = template.render(template_vars);

				// var $this_loc_view = jQuery( this_loc_view );
				var $this_loc_view = jQuery('<div>').html( this_loc_view );
				$this_loc_view
					.data( 'location-id', this_loc['id'] )
					;

				self.entries[ this_loc['id'] ] = $this_loc_view;
				$this.append( $this_loc_view );

				$this_loc_view.on('click', function(e){
					var location_id = jQuery(this).data('location-id');
					self.highlight( location_id );
					self.observers.notify( 'select-location', location_id );
				});
			}
		}
	}

	this.scroll_to = function( id )
	{
		var $container = self.entries[id];
		var new_top = $this.scrollTop() + $container.position().top;
		$this.scrollTop( new_top );
	}

	this.highlight = function( id )
	{
		var hl_class = 'hc-outlined';

		for( var iid in self.entries ){
			self.entries[iid].removeClass( hl_class );
		}

		var container = self.entries[id];
		container.addClass( hl_class );
	}
}

this.map = function( html_id )
{
	var self = this;
	this.observers = new observers;
	var $this = jQuery( '#' + html_id );
	self.template = jQuery( '#' + html_id + '_template' ).html();
	this.markers = {};
	this.entries = {};
	$this.map = null;

	this.infowindow = new google.maps.InfoWindow({
		});

	this.trigger = function( what, payload )
	{
		if( ! $this.length ){
			return;
		}

		switch( what ){
			case 'get-results':
				this.render( payload );
				break;
			case 'select-location':
				this.render_info( payload );
				break;
		}
	}

	this.init_map = function( html_id )
	{
		if( ! this.map ){
			this.map = new google.maps.Map( 
				document.getElementById(html_id), {
					zoom: 12,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					// center: new google.maps.LatLng(39.373147, -104.85964519999999),
				}
			);
			jQuery(document).trigger('hc2-lc-map-init', this.map);
		}
	}

	this.render = function( results )
	{
		if( ! $this.is(":visible") ){
			$this.show();
		}
		this.init_map( html_id );

		var entries = results['results'];
		for( var ii = 0; ii < entries.length; ii++ ){
			var id = entries[ii]['id'];
			self.entries[id] = entries[ii];
		}

		var extend_bound_for = 20; // extend bounds for the first n matches
		var max_zoom = 12;
		var max_zoom_no_entries = 4;

		for( var id in this.markers ){
			this.markers[id].setMap(null);
		}
		this.markers = {};

	// prepare zoom
		var bound = new google.maps.LatLngBounds();
		var bound_extended = false;

		if( ! entries.length ){
			if( results['search_coordinates'] && results['search_coordinates'][0] && results['search_coordinates'][1] ){
				bound_extended = true;
				var search_coordinates = new google.maps.LatLng(results['search_coordinates'][0], results['search_coordinates'][1]);
				bound.extend( search_coordinates );
			}
		}

		var extended_bound_for = 0;
		for( var ii = 0; ii < entries.length; ii++ ){
			var this_loc = entries[ii];
			var id = entries[ii]['id'];

			if( ! this_loc['latitude'] ){
				continue;
			}

			if( extended_bound_for >= extend_bound_for ){
				break;
			}

			bound.extend( new google.maps.LatLng(this_loc['latitude'], this_loc['longitude']) );
			bound_extended = true;

			extended_bound_for++;
		}

		if( bound_extended ){
			this.map.fitBounds( bound );
			this.map.setCenter( bound.getCenter() );
		}

		if( entries.length ){
			if( this.map.getZoom() > max_zoom ){
				this.map.setZoom(max_zoom);
			}
		}
		else {
			if( this.map.getZoom() > max_zoom_no_entries ){
				this.map.setZoom(max_zoom_no_entries);
			}
		}

	// place locations on map
		for( var ii = 0; ii < entries.length; ii++ ){
			var this_loc = entries[ii];
			var id = entries[ii]['id'];

			var location_position = new google.maps.LatLng( this_loc['latitude'], this_loc['longitude'] );

			var location_marker = new google.maps.Marker( {
				map: self.map,
				position: location_position,
				title: this_loc['name'],
				draggable: false,
				visible: true,
				animation: google.maps.Animation.DROP,
				location_id: id,
				});

			if( this_loc['mapicon'] && this_loc['mapicon'].length ){
				location_marker.setIcon( this_loc['mapicon'] );
			}

			location_marker.addListener( 'click', function(){
				self.render_info( this.location_id );
				self.observers.notify( 'select-location', this.location_id );
			});

			self.markers[id] = location_marker;
		}
	}

	this.render_info = function( this_id )
	{
		var this_marker = self.markers[this_id];
		var this_loc = self.entries[this_id];

		var template = new Hc2Template( self.template );
		var template_vars = this_loc;
		var this_loc_view = template.render(template_vars);

		this.infowindow.setContent( this_loc_view );
		this.infowindow.open( self.map, this_marker );
	}
}

jQuery(document).on('hc2-gmaps-loaded', function()
{
	var form = new self.form( 'hclc_search_form' );
	var list = new self.list( 'hclc_list' );
	var map = new self.map( 'hclc_map' );

	form.observers.add( list );
	form.observers.add( map );

	list.observers.add( map );
	map.observers.add( list );
});

}());
