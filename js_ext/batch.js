function myFunction() {
  // Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("select_date");
	filter = input.value.toUpperCase();
	table = document.getElementById("myTable");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[4];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		} 
	}
}

function myFunction2() {
  // Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("select_date2");
	filter = input.value.toUpperCase();
	table = document.getElementById("myTable2");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[5];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		} 
	}
}

function myFunction3() {
  // Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("select_date3");
	filter = input.value.toUpperCase();
	table = document.getElementById("myTable3");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[0];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		} 
	}
}

function myFunction4() {
  // Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("select_date4");
	filter = input.value.toUpperCase();
	table = document.getElementById("myTable4");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[0];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		} 
	}
}

function myFunction5() {
  // Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("select_date5");
	filter = input.value.toUpperCase();
	table = document.getElementById("myTable5");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[0];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		} 
	}
}

function myFunction6() {
  // Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("select_date6");
	filter = input.value.toUpperCase();
	table = document.getElementById("myTable6");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[0];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		} 
	}
}
