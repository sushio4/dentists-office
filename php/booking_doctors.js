var xhttp = new XMLHttpRequest();

xhttp.onreadystatechange = function() {
    if(this.readyState != 4 || this.status != 200) return;

    let data;
    try {
        data = JSON.parse(xhttp.responseText);
    }
    catch(err) {
        document.getElementById("timetable").innerHTML = `<h1 style="color: red;">${xhttp.responseText}</h1>`;
        return;
    }

    if(!data.success) {
        document.getElementById("timetable").innerHTML = `<h1 style="color: red;">${data.content}</h1>`;
        return;
    }

    let table = "<table>";

    for(const row of data.content) {
        table += `<tr><td><input type="radio" name="doctor" value="${row.staff_id}">${row.name}</td></tr>`;
    }

    table += `</table><input type="hidden" name="service_id" value="${service_id}">`
        + `<input type="hidden" name="time" value="${time}">`;

    document.getElementById("timetable").innerHTML = table;
}

xhttp.open("GET", `/doctors_json.php?service_id=${service_id}&time=${encodeURI(time)}`);
xhttp.send();