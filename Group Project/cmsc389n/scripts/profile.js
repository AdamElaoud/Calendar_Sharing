
//global schedule variable
let schedule = null;

// initial data load
window.onload = function() {
    // verify on correct page
    let pageCheck = document.getElementById("profile-page");
    if (pageCheck !== null) {
        // gather and display all data already in server
        gatherData();
    }
};

function gatherData(){
    console.log("gathering data from server.");
    $.get({ url: 'api/users.php', dataType: 'json'})
     .done((response) => {
         $('#profileHeader').text("Welcome " + response[0].name + "!");
         console.log(response);
         response.forEach(parse);
     });
}

function parse(json) {
    // if the user already has a schedule, draw it
    if (json.schedule !== null && json.schedule !== "") {
        schedule = JSON.parse(json.schedule);

        // parse through events
        console.log("displaying events.");
        console.log(schedule);
        schedule.forEach(drawEvent);
    }
}

function drawEvent(event) {
    let table = document.getElementById(event.day);
    //inserting new event in table
    let row = table.insertRow(-1);
    let cell = row.insertCell(-1);
    cell.innerHTML = event.name + "<br/>Start: " + event.start + "<br/>End: " + event.end;
}

function addEvent(id) {
    // set event name
    let eventName = prompt("Enter event name:");

    if (eventName === null) {
        alert("Event addition cancelled.");
    } else if (eventName === "") {
        alert("Event name cannot be empty.");
    } else {
        // set start time
        let startTime = prompt("Enter event start time (in military time):");

        if (startTime === null) {
            alert("Event addition cancelled.");
        } else if (startTime === "") {
            alert("Event start time cannot be empty.");
        } else if (startTime < 0 || startTime > 2359) {
            alert("Please enter a correct military time value (0000 - 2359).");
        } else {
            // set end time
            let endTime = prompt("Enter event end time (in military time):");

            if (endTime === null) {
                alert("Event addition cancelled.");
            } else if (endTime === "") {
                alert("Event end time cannot be empty.");
            } else if (endTime < 0 || endTime > 2359) {
                alert("Please enter a correct military time value (0000 - 2359).");
            } else if (endTime < startTime) {
                alert("Event end time must come after start time.");
            } else {
                alert("Event sucessfully added.");

                // creating object to send to server
                let event = {
                    day: id,
                    name: eventName,
                    start: startTime,
                    end: endTime
                };

                // drawing event
                drawEvent(event);

                // adding event to schedule
                if (schedule !== null) {
                    schedule.push(event);
                } else {
                    // if schedule is empty, create schedule
                    schedule = [event];
                }

                // sending
                sendData();
            }
        }
    }
}

function sendData() {
    let json = schedule;
    console.log("sending data to server");

    // send data to server
    $.post({ url: 'api/users.php', dataType: 'json'}, {'schedule' : json})
     .done((response) => {
         location.reload();
     })
     .fail((response) => {
         console.log(response);
     })
}
