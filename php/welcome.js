var xhttp = new XMLHttpRequest();

xhttp.onreadystatechange = function() {
    if(this.readyState != 4 || this.status != 200) return;

    let data;
    try {
        data = JSON.parse(xhttp.responseText);
    }
    catch(err) {
        document.getElementById("table").innerHTML = xhttp.responseText;
        return;
    }
    
    table = "<table class=\"center\" style=\"margin-bottom: 30px; transform: scale(1.3);\">"
        + "<tr><th>Usługa</th><th>Data</th><th>Czas</th><th>Lekarz</th><th>Cena</th></tr>\n";

    for(const row of data) {
        table += `<tr><td>${row["service"]}</td><td>${row["date"]}</td><td>${row["time"]}</td>`
            + `<td>${row["doctor"]}</td><td>${row["price"]}</td></tr>`;
    }

    table += "</table>";

    document.getElementById("table").innerHTML = table;
}

xhttp.open("GET", "/future_visits_json.php", true);
xhttp.send();

