var xhttp = new XMLHttpRequest();

xhttp.onreadystatechange = function() {
    if(this.readyState != 4 || this.status != 200) return;

    let data = JSON.parse(xhttp.responseText);

    let result = "<table><tr><th></th><th>Nazwa Usługi</th><th>Opis</th><th>Czas Trwania</th><th>Cena</th></tr>";
    for(const row of data) {
        result += `<tr><td><input type="radio" name="service_id" value="${row["service_id"]}"></td>`
            + `<td>${row["name"]}</td><td>${row["description"]}</td><td>${row["duration"]}</td><td>${row["price"]}</td></tr>`
    }
    result += "</table>";
    
    document.getElementById("timetable").innerHTML = result;
}

xhttp.open("GET", "/services_json.php", true);
xhttp.send();