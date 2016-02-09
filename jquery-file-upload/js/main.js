/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';
	
	// ascending order
	function SortByOrder(x,y) {
		return x.order - y.order; 
	}
	
	//Show files
	$('.fileupload').each(function () {
		var $this = $(this);
		$(this).fileupload({
        	//dropZone: $this.parents(".dropzone"),
			url: $this.data("submit")
    	}).on('fileuploadsubmit', function (e, data) {
			data.formData = data.context.find(':input').serializeArray();
			//$.each(data.formData, function(i, field){
				//alert(field.name + ":" + field.value );
			//});
		});
		
		// Upload in order
		$this.fileupload('option', 'sequentialUploads', true);
		
		// Load existing files:
		$this.addClass('fileupload-processing');
		$.ajax({
			// Uncomment the following to send cross-domain cookies:
			//xhrFields: {withCredentials: true},
			url: $this.fileupload('option', 'url'),
			dataType: 'json',
			context: $this[0]
		}).always(function () {
			$this.removeClass('fileupload-processing');
		}).done(function (result) {
			result.files.sort(SortByOrder);
			$this.fileupload('option', 'done')
			.call(this, $.Event('done'), {result: result});
			console.log(result);
		});
	});
	
	//Sort and save sort to database
	$(".files").sortable({ opacity: 0.6, cursor: 'move', update: function() {
		var $this = $(this);
		var order = $this.sortable("serialize") + '&action=updateRecordsListings';
		var action = "/jquery-file-upload/area/order.php";
		//var action = $this.parents(".fileupload").attr("action");
		$.ajax({
			url: action,
			type: "POST",
			//dataType: 'json',
			data: order
		}).done(function (result) {
		});
	}
	});
});