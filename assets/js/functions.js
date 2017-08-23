
jQuery(document).ready(function($) {

		var typing_timer;
		
		/*! 
		 * GET call and executes a callback when the promise is fullfilled
		 * @param methodType [GET, POST, PUT, DELETE]
		 * @param endpoint API endpoint to make the call to
		 * @param data JSON encoded data 
		 * @param callback callable 
		 * @return JSON encoded response
		 */
		var _ajaxRequest = function(methodType, endpoint, data, includeHeaders, callback){
			var myHeaders = {};
				myHeaders['Content-Type'] =  'application/x-www-form-urlencoded';
			var myData 	= (!data) ? "" : data;
			var xhr 	= new XMLHttpRequest();
			xhr.open(methodType, endpoint, true);
			for (var property in myHeaders) {
			    if (myHeaders.hasOwnProperty(property))
					xhr.setRequestHeader(property, myHeaders[property]);
			}
			xhr.onreadystatechange = function(){
				if (xhr.readyState === 4 && xhr.status === 200){
					console.log(JSON.parse(xhr.response));
					callback(JSON.parse(xhr.response));
				}
				if (xhr.readyState === 4 && xhr.status === 401){

				}
		   }
		   xhr.send(myData);
		};

		window.searchProjects = function(){
			var term = $('#hashtag').val();
			clearTimeout(typing_timer);
			typing_timer = setTimeout( function(){ loadResults(term); }, 900);
		};

		var loadResults = function (term) {
			term = !term ? " " : term;
			$('#hidden_search').val(term);
			return _ajaxRequest('POST', 'assets/inc/process.php', encodeURI('s='+term), true, loadResults_callback);
		};

		var loadResults_callback = function(response){
			$('#twitContainer').empty();
			response.statuses.forEach(function(element, index){
				// TO DO: Implement handlebars and render templates
				$('#twitContainer').append("\
						<div class='each-twit'>\
							<img src='"+element.user.profile_image_url+"' class='user-profile'/>\
							<div><p class='user-details'>@"+element.user.screen_name+"</p></div>\
							<time>"+element.created_at+"</time>\
							<p>"+element.text+"</p>\
						</div>\
					\ ");
			});
		};

});