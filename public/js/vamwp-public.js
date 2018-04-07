(function( $ ) {
	'use strict';

	/**
	 * JavaScript to define rendering of DataTable tables.
	 */

  /*

  VAMwp 1.0.0-2.6.2 (https://github.com/likeablegeek/vamwp)
  WORDPRESS PLUGIN for VAM 2.6.2 (http://virtualairlinesmanager.net/)

  	By: Arman Danesh

  	License:

  	Copyright (c) 2018 Arman Danesh

  	Licensed under the Apache License, Version 2.0 (the "License");
  	you may not use this file except in compliance with the License.
  	You may obtain a copy of the License at

      http://www.apache.org/licenses/LICENSE-2.0

  	Unless required by applicable law or agreed to in writing, software
  	distributed under the License is distributed on an "AS IS" BASIS,
  	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  	See the License for the specific language governing permissions and
  	limitations under the License.

  */

	$(document).ready(function(){

	    $('#vam-routes').DataTable();

	    $('#vam-tours').DataTable();

	    $('#vam-ranks').DataTable({
        	"order": [[ 2, "asc" ]]
    	});

	    $('#vam-awards').DataTable();

	    $('#vam-hubs').DataTable();

	    $('#vam-fleet').DataTable({
	    	"columnDefs":	[
	    		{
	    			"targets":	4,
	    			"render":	$.fn.dataTable.render.percentBar( 'round','#FFF', '#269ABC', '#31B0D5', '#286090', 1, 'groove' )
	    		}
	    	]
	    });

	    $('#vam-latestflights').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
 		});

	    $('#vam-newestpilots').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
    	});

	    $('#vam-pilotsroster').DataTable({
    	});

	    $('#vam-runway-info').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
    	});

	    $('#vam-airport-frequencies').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
    	});

	    $('#vam-airport-navaids').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
    	});

	    $('#vam-aircraft-flights').DataTable({
    	});

	    $('#vam-hub-routes').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
   		 });

	    $('#vam-aircraft-maintenance').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
   		 });

	    $('#vam-aircraft-details').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false,
        	"ordering":	false
   		 });

	    $('#vam-hub-pilots').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
   		 });

	    $('#vam-hub-fleet').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false,
	    	"columnDefs":	[
	    		{
	    			"targets":	4,
	    			"render":	$.fn.dataTable.render.percentBar( 'round','#FFF', '#269ABC', '#31B0D5', '#286090', 1, 'groove' )
	    		}
	    	]
   		 });

	    $('#vam-hub-details').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false,
					"ordering": false
   		 });

	    $('#vam-hub-routes-map').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
   		 });

	    $('#vam-airport-map').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false
   		 });

	    $('#vam-stats').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false,
        	"ordering":	false
   		 });

	    $('#vam-pilot-profile').DataTable({
        	"paging":   false,
        	"info":     false,
        	"searching":	false,
        	"ordering":	false
   		 });

	});

})( jQuery );
