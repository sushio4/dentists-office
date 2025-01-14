var xhttp = new XMLHttpRequest();

xhttp.onreadystatechange = function() {
    if(this.readyState != 4 || this.status != 200) return;

    let data = JSON.parse(xhttp.responseText);

    let result = "<table><tr>";
    for(const h of data.head) {
        result += "<th>" + h + "</th>";
    }
    result += "</tr>";

    for(const row of data.rows) {
        result += "<tr>";

        for(let i = 0; i < row.length; i++) {
            field = row[i];
            day = data.head[i];

            result += `<td><input type="radio" name="time" value="${day} ${field.time}"`;
            if(field.available) {
                result += `>${field.time}</td>`;
            }
            else {
                result += ` disabled="true"><a style="color: grey;">${field.time}</a></td>`
            }
        }

        result += "</tr>";
    }
    result += `</table><input type="hidden" name="service_id" value="${service_id}">`;

    document.getElementById("timetable").innerHTML = result;
}

xhttp.open("GET", "/time_json.php?service_id=" + service_id, true);
xhttp.send();