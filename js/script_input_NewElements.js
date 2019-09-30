function add_mitglied() {
	var vorname = 'Vorname: <input type="text" id="vorname" oninput="eval_mit()"" />  <br>';
	var name = 'Name: <input type="text" id="name" oninput="eval_mit()"/> <br />';
	var jg = 'Jg(z.B. 1993):  <input type="number" id="jg" oninput="activ()"" style="width: 4em" min="1850" max="2050" value="2007"/> <br />';
	var sex = 'Geschlecht (1:W, 2:M):  <input type="number" id="sex" style="width: 4em" min="1" max="5" value="2"/> <br />';
	var activity = 'Aktivität: <input type="number" id="activity" style="width: 4em" oninput="activ()"" value= "1"/> <div id="result_aktiv"></div>';
	var submit = '<input type="button" onclick="insert_mitglied()" value="Mittglied erfassen!" />';
	$('#new_mitglied_input').html(
			"<p>" + vorname + name + jg + sex + activity
					+ "<div id='existing_mitglied'></div>" + submit + "</p>");
	$('#new_mitglied_link').html(
			'<a href="#"onclick="vanish_mitglied()">ausblenden!</a>');
	activ();
}

function activ() {
	var aktiv = document.getElementById('activity').value;
	var jg = document.getElementById('jg').value;
	var end = Number(jg) + Number(aktiv) + 15;
	var txt = "Athlet ist aktiv bis und mit " + end;
	document.getElementById('result_aktiv').innerHTML = txt;
}
function eval_mit() {
	var vorname = document.getElementById('vorname').value;
	var name = document.getElementById('name').value;
	var jg = document.getElementById('jg').value;

	$.post('oop_InputFields.php', {
		type : "similarPerson",
		firstName : vorname,
		lastName : name,
	}, function(data) {
		$('#existing_mitglied').html(data);
	});
}
function vanish_mitglied() {
	$('#new_mitglied_link').html(
			'<a href="#"onclick="add_mitglied()">Mitglied hinzufügen</a>')
	$('#new_mitglied_input').html("");
	$('#insert_mitglied_result').html("");

}
function insert_mitglied() {
	var vorname = document.getElementById('vorname').value;
	var name = document.getElementById('name').value;
	var jg = document.getElementById('jg').value;
	var sex = document.getElementById('sex').value;
	var activity = document.getElementById('activity').value;

	if (name == "" || vorname == "" || jg == "" || sex == "" || activity == "") //  
	{
		alert("Bitte Fülle alle Felder aus");
	} else {
		if (vorname.slice(-1) == " ") {
			alert("Überprüfe deine Eingabe: Der Vorname darf nicht mit einem Leerschlag enden");
		} else if (name.slice(-1) == " ") {
			alert("Überprüfe deine Eingabe: Der Nachname darf nicht mit einem Leerschlag enden");
		} else {
			// insert into db
			$.post('oop_insertToDB.php', {
				type : "person",
				lastName : name,
				firstName : vorname,
				born : jg,
				gender : sex,
				visible : activity
			}, function(data) {
				var data_arr = data.split("-----");
				$('#insert_mitglied_result').html(data_arr[0]);
				loadPerson();
			});
		}
	}
}

function add_disziplin() {
	var name = 'Name der Disziplin: <input type="text" size="25" id="d_name"  value="Drehwurf"/>  <br>';
	var lauf = 'Typ der Disziplin ( 1=Lauf, 2=Sprung 3=Wurf 4=Mehrkampf 5=Staffel 6=Team Mehrkampf (LMM/SVM/...) ): <input type="number" max="7" id="d_lauf" value="3"/> <br />';
	var min = 'Minimalwert: <input type="number" size="25" id="d_min_value" value ="5" /> <br />';
	var max = 'Maximalwert: <input type="number" size="25" id="d_max_value" value ="40" /> <br />';
	var visible = "<table border='1'>sichtbar für: <thead> <th>U10</th><th>U12</th><th>U14</th><th>U16</th><th>U18</th><th>U20</th><th>Frauen</th><th>Männer</th></thead><tr><td><input type='checkbox' name='sich[]' value='U10' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U12' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U14' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U16' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U18' checked='yes'></td><td><input type='checkbox' name='sich[]' value='U20' checked='yes'></td><td><input type='checkbox' name='sich[]' value='wom' checked='yes'></td><td><input type='checkbox' name='sich[]' value='man' checked='yes'></td></tr></table>";
	var submit = '<input type="button" onclick="insert_disziplin();loadDisziplin()" value="Disziplin erfassen!" />';
	$('#new_disziplin_input').html(
			"<p>" + name + lauf + min + max + visible + submit + "</p>");
	$('#new_disziplin_link').html(
			'<a href="#"onclick="vanish_disziplin()">ausblenden!</a>');
}
function vanish_disziplin() {
	$('#new_disziplin_link').html(
			'<a href="#"onclick="add_disziplin()">Disziplin hinzufügen</a>')
	$('#new_disziplin_input').html("");
}
function insert_disziplin() {
	var name = document.getElementById('d_name');
	var lauf = document.getElementById('d_lauf');
	var min = document.getElementById('d_min_value');
	var max = document.getElementById('d_max_value');
	var visible = document.getElementsByName('sich[]');
	var vis_kat = {};
	for (var i = 0; i < visible.length; i++) {
		vis_kat[visible[i].value] = visible[i].checked;
	}

	if (name.value == "" || lauf.value == "" || min.value == ""
			|| max.value == "") //  
	{
		alert("Bitte Fülle alle Felder aus");
	} else {
		// insert into db
		$.post('oop_insertToDB.php', {
			type : "disziplin",
			name : name.value,
			lauf : lauf.value,
			min_value : min.value,
			max_value : max.value,
			vis : vis_kat
		}, function(data) {
			var data_arr = data.split("-----");
			$('#insert_disziplin_result').html(data_arr[0]);
			// $('#disziplin_list').html(data_arr[1]);
		});
	}
}

function add_wettkampf() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1;
	var yyyy = today.getFullYear();
	if (dd < 10) {
		dd = '0' + dd;
	}
	if (mm < 10) {
		mm = '0' + mm;
	}
	var formated_today = yyyy + '-' + mm + '-' + dd;
	var name = 'Name des Wettkampfs: <input type="text" size="25" id="w_name"  value=""/>  <br>';
	var place = 'Ort: <input type="text" size="25" id="w_place" value=""/> <br />';
	var date = 'Datum: <input type="date" size="25" id="w_date" value ="'
			+ formated_today + '" />  <br />';
	var submit = '<input type="button" onclick="insert_wettkampf()" value="Wettkampf erfassen!" />';
	$('#new_wettkampf_input').html(
			"<p>" + name + place + date + submit + "</p>");
	$('#new_wettkampf_link').html("");
}

function insert_wettkampf() {
	var name = document.getElementById('w_name');
	var place = document.getElementById('w_place');
	var date = document.getElementById('w_date');
	if (name.value == "" || place.value == "" || date.value == "") //  
	{
		alert("Bitte Fülle alle Felder aus");
	} else {
		date_array = date.value.split("-");
		if (date.value.split("-").length == 3) {
			// insert into db
			$.post('oop_insertToDB.php', {
				type : "competition",
				name : name.value,
				place : place.value,
				date : date.value
			}, function(data) {
				$('#new_wettkampf_link').html(data);
			});
		} else {
			alert("date format not correct")
		}
	}
	loadCompetitions(0,0);
}
