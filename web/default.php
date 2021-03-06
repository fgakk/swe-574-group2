<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Cp1254">
<title>Fair Urban</title>
</head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
html {
	height: 100%
}

body {
	height: 100%;
	margin: 0;
	padding: 0
}

#map_canvas {
	height: 100%
}
</style>
<link rel="stylesheet" href="css/baseTheme/style.css" type="text/css"
	media="all" />
<link rel="stylesheet" href="css/basic.css" type="text/css" media="all" />

<script type="text/javascript"
	src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCg0FRVSu5XHjjkG1NV1tev04MaGOTg5Jo&sensor=false"></script>
<script type="text/javascript"
	src="http://code.jquery.com/jquery-1.8.2.min.js"></script>

<script type="text/javascript" src="js/ajaxupload-min.js"></script>

<!-- <script type="text/javascript" src="js/jquery.jsonp.js"></script> -->


<script type="text/javascript">

	var markersArray = [];
	
	function clearOverlays() {
		  for (var i = 0; i < markersArray.length; i++ ) {
		    markersArray[i].setMap(null);
		  }
		}

	function downloadUrl(url, callback) {
		var request = window.ActiveXObject ? new ActiveXObject(
				'Microsoft.XMLHTTP') : new XMLHttpRequest;

		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				request.onreadystatechange = doNothing;
				callback(request, request.status);
			}
		};

		request.open('GET', url, true);
		request.send(null);
	}

	function doNothing() {
	}

	function bindInfoWindow(marker, map, infoWindow, html) {
		markersArray.push(marker);
		google.maps.event.addListener(marker, 'click', function() {
			infoWindow.setContent(html);
			infoWindow.open(map, marker);
		});
	}

	var entryDetailUrl = "entry.php?id=";

	// Set the Map variable
	var map;
	function initialize() {
		var myOptions = {
			zoom : 12,
			mapTypeId : google.maps.MapTypeId.ROADMAP,
			scrollwheel: true
		};

		//Define Marker properties
		var markerImg = new google.maps.MarkerImage(
				'images/markers/marker2.png',
				// This marker is 129 pixels wide by 42 pixels tall.
				new google.maps.Size(22, 32),
				// The origin for this image is 0,0.
				new google.maps.Point(0, 0),
				// The anchor for this image is the base of the flagpole at 18,42.
				new google.maps.Point(18, 42));

		var infoWindow = new google.maps.InfoWindow;
		map = new google.maps.Map(document.getElementById('map_canvas'),
				myOptions);

		// Set the center of the map
		var pos = new google.maps.LatLng(41.08, 29.025);
		//var pos = new google.maps.LatLng(0, 0);
		map.setCenter(pos);
		function infoCallback(infowindow, marker) {
			return function() {
				infowindow.open(map, marker);
			};
		}

		// downloadUrl("markerInfo.php", function(data) {
//		var xml = data.responseXML;
//		var markers = xml.documentElement.getElementsByTagName("marker");
//		for ( var i = 0; i < markers.length; i++) {
//			var comment = markers[i].getAttribute("comment");
//			var entryId = markers[i].getAttribute("id");
//			var point = new google.maps.LatLng(parseFloat(markers[i]
//					.getAttribute("lat")), parseFloat(markers[i]
//					.getAttribute("lng")));
//			var html = "<img src='http://physicsworld.com/blog/Guetamala%20hole.jpg'>"
//					+ "<br/><b>''" + comment + "''</b> <br/> <a style='text-decoration: underline' href='" 
//					+ entryDetailUrl + entryId + "'>Detay...</a>";
//			var marker = new google.maps.Marker({
//				map : map,
//				position : point,
//				icon : markerImg
//			});
//			bindInfoWindow(marker, map, infoWindow, html);
//		}
//
//		$('#divCount').html("Sistemde toplam " + markers.length + " adet ihl�l var.");
//	});

		var getAllEntriesUrl = "http://swe.cmpe.boun.edu.tr:8180/rest/service/entries";
		
		$.getJSON(getAllEntriesUrl, function(data) {
			$.each(data['data'], function(index, element) {
				var comment = element.comment;
				var entryId = element.id;
				var upVote = element.upVoteCount;
				var downVote = element.downVoteCount;
				var image = "http://physicsworld.com/blog/Guetamala%20hole.jpg"; //element.imageMeta;
				var point = new google.maps.LatLng(parseFloat(element.coordX), parseFloat(element.coordY));
				var html = "<img width='70%' height='70%' src='" + image + "'>"
						+ "<p></p><b>''" + comment + "''</b> <p></p>"
						+ upVote + " olumlu oy &nbsp;&nbsp;" 
						+ "<a rel='" + entryId + "' id='lnkUpVote' href='javascript:voteUp(" + entryId + ");'"
						+ "><img width='25' height='25'" 
						+ "src='images/upArrow.png' title='Olumlu oy ver'></a>&nbsp;&nbsp;&nbsp;"
						+ "<a rel='" + entryId + "' id='lnkDownVote' href='javascript:voteDown(" + entryId + ");'>"
						+ "<img width='25' height='25'"
						+ "src='images/downArrow.png' title='Olumsuz oy ver'></a>"
						+ "&nbsp;&nbsp;" + downVote + " olumsuz oy <p></p>" 
						+ "<a style='text-decoration: underline' href='" 
						+ entryDetailUrl + entryId + "'>Detay...</a>";
						
				var marker = new google.maps.Marker({
					map : map,
					position : point,
					icon : markerImg
				});
				bindInfoWindow(marker, map, infoWindow, html);
			 });

			 $('#divCount').html("Sistemde toplam " + data['data'].length + " adet ihl�l var.");
		 });

// 		var marker = new google.maps.Marker({
// 			position : map.getCenter(),
// 			map : map,
// 			title : 'Click to zoom'
// 		});

// 		google.maps.event.addListener(marker, 'click', function() {
// 			map.setZoom(8);
// 			map.setCenter(marker.getPosition());
// 		});
	};

	// Initializes the Google Map
	google.maps.event.addDomListener(window, 'load', initialize);

	var voteUrl = "http://swe.cmpe.boun.edu.tr:8180/rest/service/entries/thumbs";

	function voteUp(entryId) {
		$.ajax({
		    url: voteUrl,
		    type: 'post',
		    data: {entryId: entryId, up: true}
		}).done(function(){
		    var upp = $('#lblUpVote').val();
		    $('#lblUpVote').val(parseInt(upp) + 1);
		});
	};

	function voteDown(entryId) {
		$.ajax({
		    url: voteUrl,
		    type: 'post',
		    data: {entryId: entryId, up: false}
		}).done(function(){
		    var upp = $('#lblUpVote').val();
		    $('#lblUpVote').val(parseInt(upp) + 1);
		});
	};
</script>

<script type="text/javascript">
	$(document).ready(function() {
		//testuser - swe574TEST!
		

		$("#submit").click(function() {
			$('#frmlogin').submit(function() {
				alert($(this).serialize());
				$.ajax({
					type: 'POST',
		            url: 'http://swe.cmpe.boun.edu.tr:8180/rest/service/login/sigin',
		          	//data: "{'username': 'testuser', 'password': 'swe574TEST!'}",
		          	//data: "{}",
			   		//contentType: "application/json; charset=utf-8",
		    		//dataType: 'json',
		          	//username: 'testuser',
		          	//password: 'swe574TEST!',
		            success: function(msg) {
		                  alert(msg.loggedIn);
		            },
		            error:function(e){
			            alert(e.message);
		                //alert(e.message);
		            }
		        });
				  return false;
				});
			});
		
				//jQuery.support.cors = true;

    //var getCategoriesUrl = 'http://172.20.2.5:8080/RestAccessibilty/service/categories';
		//var getCategoriesUrl = 'http://testpalette.com:8080/RestAccessibilty/service/categories';
		var getCategoriesUrl = 'http://swe.cmpe.boun.edu.tr:8180/rest/service/categories';
		var getEntriesByCategoryUrl = 'http://swe.cmpe.boun.edu.tr:8180/rest/service/entries?categoryId=';

		var selectParent = $('#ddlCategories');
		var selectChild = $('#ddlChildren');
		$.getJSON(getCategoriesUrl, function(data) {
// 			$.each(data['data'], function(index, element) {
// 		        $('#feed').append(element.id + " " + element.title + " " + " " + element.parentReasonId + "</br>");
// 		        $.each(element.violationTypes, function(index, element2)
// 		        {
// 		        	$('#feed').append(element2.id + " " + element2.title + " " + " " + element2.description + " " + element2.imageMeta);
// 		        });
// 		    });
			$.each(data['data'], function(index, element) {
		        $(selectParent).append(
		        	$('<option></option>').val(element.id).html(element.title)
		        );
		    });
		 });
		
		selectParent.change(function() {
			if(selectParent.find(":selected").val() == "0")
			{
				selectChild.hide();
			}
			else
			{
				selectChild.empty();
				selectChild.append(
			        $('<option></option>').val(0).html("Tamam�")
			    );
				$.getJSON(getCategoriesUrl + "/" + selectParent.find(":selected").val(), function(data) {
					$.each(data['data'], function(index, element) {
				        selectChild.append(
				        	$('<option></option>').val(element.id).html(element.title)
				        );
				    });
					selectChild.show();
				 });
			}
		});
		
		selectChild.change(function() {

		});
			
		$('#btnSearch').click(function() {
			var valToSearch = 0;
			if(selectChild.val() == "0"){
				valToSearch = selectParent.val();
			}
			else{
				valToSearch = selectChild.val();
			}
			
			
				var scriptUrl = getEntriesByCategoryUrl + valToSearch;
			    $.ajax(
			    {
			        url: scriptUrl,
			        type: 'get',
			        dataType: 'json',
			        beforeSend: function(){
			        	$("#spinner").show();
					},
					complete: function(){
						$("#spinner").hide();
					},
			        success: function(data)
			        {
			        	var dataS = data['data'];
		        
			        	$('#divCount').html("Filtreleme sonucu " + dataS.length + " adet ihl�l bulundu.");
				        
			        	var pinImg = new google.maps.MarkerImage(
			    				'images/markers/wheelchair.png',
			    				// This marker is 129 pixels wide by 42 pixels tall.
			    				new google.maps.Size(129, 42),
			    				// The origin for this image is 0,0.
			    				new google.maps.Point(0, 0),
			    				// The anchor for this image is the base of the flagpole at 18,42.
			    				new google.maps.Point(18, 42));
			        	
			        	var infoWindow = new google.maps.InfoWindow;
			        	
			    		clearOverlays();

			    		$.each(dataS, function(index, element) {
			    			var comment = element.comment;
		    				var entryId = element.id;
		    				var point = new google.maps.LatLng(parseFloat(element.coordX), parseFloat(element.coordY));
		    				var image = "http://physicsworld.com/blog/Guetamala%20hole.jpg"; //element.imageMeta;
		    				var html = "<img width='70%' height='70%' src='" + image + "'>"
		    						+ "<br/><b>''" + comment + "''</b> <br/> <a style='text-decoration: underline' href='" 
		    						+ entryDetailUrl + entryId + "'>Detay...</a>";
		    				var marker = new google.maps.Marker({
		    					map : map,
		    					position : point,
		    					icon : pinImg
		    				});
		    				
		    				bindInfoWindow(marker, map, infoWindow, html);

					    });	
			        },
			        error: function(data){ alert("Bir hata olu�tu, l�tfen tekrar deneyin.");}
			    });
			
		});

		var voteUrl = "http://swe.cmpe.boun.edu.tr:8180/rest/service/entries/thumbs";
		
		$("#lnkUpVote").click(function() {
			alert($("#lnkUpVote").attr('rel'));
			$.ajax({
			    url: voteUrl,
			    type: 'post',
			    data: {entryId: $("#lnkUpVote").attr('rel'), up: true}
			}).done(function(){
			    alert($("#lnkUpVote").attr('rel'));
			    var upp = $('#lblUpVote').val();
			    $('#lblUpVote').val(parseInt(upp) + 1);
			});
		});
		
		$("#lnkDownVote").click(function() {
			if ($('#lnkDownVote').hasClass('disabled')) return;
			$('#lblVote').html('Down vote verildi');
			$('#msgVote').hide().fadeIn("slow", "linear");
			var upp = $('#hdnDownVote').val();
		    $('#lblDownVote').html(parseInt(upp) - 1);
		    $('#lnkDownVote').addClass('disabled');    
		});

		function voteUp(entryId) {
			alert(entryId);
			$.ajax({
			    url: voteUrl,
			    type: 'post',
			    data: {entryId: $("#lnkUpVote").attr('rel'), up: true}
			}).done(function(){
			    alert($("#lnkUpVote").attr('rel'));
			    var upp = $('#lblUpVote').val();
			    $('#lblUpVote').val(parseInt(upp) + 1);
			});
		};

		
		
	});
</script>

<body>
	<div>
		<?php include('master.php');?>
	</div>
	<div>
		<h4>Kategori filtreleme:</h4>
		<select id="ddlCategories">
			<option value="0">Tamam�</option>
		</select> <select id="ddlChildren" style="display: none">
			<!--<option value="0">Tamam�</option> -->
		</select> <input type="button" id="btnSearch" name="formSearch"
			value="Ara" /> <span id="spinner" class="spinner"
			style="display: none;"><img id="img-spinner" src="images/spinner.gif"
			alt="Yükleniyor" /> </span>
		<p style="height: 7px"></p>
		<div id="divCount" style="font-weight: bold;"></div>
	</div>
	<p style="height: 20px"></p>
	<div id="map_canvas"
		style="width: 900px; height: 700px; margin-left: 190px;"></div>
	<p style="height: 20px;"></p>
	<div>
		<a href="new.php"
			style="font-weight: bold; text-decoration: underline;">Bulundu�um
			lokasyona yeni giri�</a> &nbsp;&nbsp;||&nbsp;&nbsp; <a href="new_manual.php"
			style="font-weight: bold; text-decoration: underline;">Se�ece�im
			lokasyona yeni giri�</a>
	</div>
	<p style="height: 20px"></p>
	<form id="frmlogin" action="http://swe.cmpe.boun.edu.tr:8180/rest/service/login/sigin" method="post">
		<table>
	        <tr><td>User:</td><td><input type='text' name='username' value=''></td></tr>
	        <tr><td>Password:</td><td><input type='password' name='password'></td></tr>
	        <tr><td colspan='2'><input name="submit" type="submit" id ="submit"></td></tr>
	        <tr><td colspan='2'><input name="reset" type="reset"></td></tr>
	      </table>
	</form>
</body>


</html>
