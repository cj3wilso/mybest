$('#reposHome').bind('pageinit', function(event) {
	loadRepos();
});

function loadRepos() {
    $.ajax("http://mybestapartments.ca/mobile/prov.php").done(function(data) {
        var i, repo;
        $.each(data.repositories, function (i, repo) {
            $("#allProv").append("<li><a href='prov.html?prov=" + repo.prov + "'>"
            + "<h4>" + repo.prov + "</h4></a></li>");
        });
        $('#allProv').listview('refresh');
    });
}

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

$('#provDetail').live('pageshow', function(event) {
    var prov = getUrlVars().prov;
    loadProvDetail(prov);
});

function loadProvDetail(prov) {
	 $.ajax("http://mybestapartments.ca/mobile/city.php?prov="+prov).done(function(data) {
        var i, repo;
		$('h1#provHeader').html(prov);
        $.each(data.repositories, function (i, repo) {
            $("#city").append("<li><a href='city.html?city=" + repo.city + "'>"
            + "<h4>" + repo.city + "</h4></a></li>");
        });
        $('#city').listview('refresh');
    });
}

$('#cityDetail').live('pageshow', function(event) {
    var city = getUrlVars().city;
    loadCityDetail(city);
});

function loadCityDetail(city) {
	 $.ajax("http://mybestapartments.ca/mobile/apt.php?city="+city).done(function(data) {
        var i, repo;
		$('h1#cityHeader').html(city);
        $.each(data.repositories, function (i, repo) {
            $("#apt").append("<li><a href='apt.html?apt=" + repo.id + "&name=" + repo.name + "'>"
            + "<img src='" + repo.photo + "'>"
			+ "<h4>" + repo.name + "</h4>" 
			+ "<p>" + repo.address + "<br />" 
			+ "Price: " + repo.price + ", Beds: " + repo.beds + ", Page ID: " + repo.id + "</p></a></li>");
        });
        $('#apt').listview('refresh');
    });
}

$('#aptDetail').live('pageshow', function(event) {
	var apt = getUrlVars().apt;
	var name = getUrlVars().name;
    loadAptDetail(apt,name);
});


function loadAptDetail(apt,name) {
	 $.ajax("http://mybestapartments.ca/mobile/aptDetail.php?apt="+apt).done(function(data) {
        var i, repo;
		$('h2#aptHeader').html(decodeURIComponent(name));
		$('.check-avail').attr("href", "contact.html?apt="+apt);
        $.each(data.repositories, function (i, repo) {
			$("#aptDetail #address").html(repo.address);
			$("#aptDetail #website").html(repo.website);
			$("#aptDetail #phone").html(repo.phone);
			$("#aptDetail #photos").html(repo.photo);
			$("#units #style").html(repo.style);
			$("#units #beds").html(repo.beds);
			$("#units #baths").html(repo.baths);
			$("#units #ft").html(repo.ft);
			$("#units #rent").html("$"+repo.rent);
			$("#units #dep").html(repo.dep);
			$("#aptDetail #intro").html(repo.intro);
			$("#aptDetail #map").html(repo.map);
        });
    });
}

$('#contactForm').live('pageshow', function(event) {
	$("#contactForm #seller").click(function() {
		var url = "http://mybestapartments.ca/_inc/form_contact_seller.php";
		$.ajax({
			   type: "POST",
			   url: url,
			   data: $("#contactSeller").serialize(),
			   success: function(data){
				   $("#contactSeller").find(".form_result").html(data);
			   }
			 });
		$("#contactSeller")[0].reset();
		return false;
	});
	var apt = getUrlVars().apt;
    loadContactForm(apt);
});

function loadContactForm(apt) {
	 $('#prop').val(apt);
}