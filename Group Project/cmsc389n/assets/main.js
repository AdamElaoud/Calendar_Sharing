(function (globals) {
    'use strict';
    const {
        $,
        groupListView
    } = globals;
    let activeGroup = {};

    if ($.isEmptyObject(activeGroup)) {
        $('.group-view-container').css('display', 'none');
    }

    initEventHandlers();

    $.get({ url: 'api/groups.php', dataType: 'json'})
     .done((response) => {
         response.forEach(renderGroupItem);
         console.log('active group' + activeGroup.id);
         $('#' + activeGroup.id).click();
     });

    function initEventHandlers() {

        $(groupListView).on('click', 'a', function onGroupClick(evt) {
            const groupId = Number(this.href.slice(1 + this.href.indexOf('#')));
            openGroup(groupId);
        });

        $('.group-list-container > button').on('click', function onGroupClick(evt) {
            this.disabled = true;
            $.post({ url: 'api/groups.php', dataType: 'json'})
             .done((response) => {
                 this.disabled = false;
                 openGroup(response.id);
                 window.location.href = window.location.origin + '/cmsc389n/main.php#' + response.id;
                 location.reload();
             })
             .fail((response) => {
                 this.disabled = false;
                 this.focus();
             });
        });

        $('#submitNameChangeButton').on('click', function onGroupRename(evt) {
            $.post({ url: 'api/groups.php', dataType: 'json'}, { 'id' : activeGroup.id, 'name' : $('#name').val()})
             .done((response) => {
                 location.reload();
             })
        });

        $('#addGroupMemberButton').on('click', function onGroupMemberAdd(evt) {
            $.post({url: 'api/users_groups.php', dataType: 'json'}, { 'email' : $('#email').val(), 'g_id' : activeGroup.id})
             .done((response) => {
                 $.get({url: 'api/users.php', dataType: 'json'}, {'email': response.email})
                  .done((response2) => {
                    $('#groupMembers').prepend($(`
                       <li class="list-group-item" style="height: 50px">
                            <div class="float-left">` + response2[0].name + `</div>
                            <div class="float-right">` + response.email + `</div>
                       </li>
                    `));
                    drawSchedules(response2[0]);
                  })
             })
             .fail((response) => {
                 console.log(activeGroup.id);
             })
        });

        $('#leaveGroupButton').on('click', function onGroupMemberLeave(evt) {
            $.ajax({
                url: 'api/users_groups.php',
                method: 'DELETE',
                contentType: 'application/json',
                data: {'g_id' : activeGroup.id},
                success: function(result) {
                    console.log(result);
                    location.reload();
                },
                error: function(request,msg,error) {
                    console.log(request);
                    console.log(msg);
                    console.log(error);
                }
            })
        })
    }


    function renderGroupItem(group) {
        const [path] = location.href.split('#');
        const groupName = group.name;
        $(groupListView).prepend($(`
            <li class="list-group-item">
                <a class="media-body" id="${group.id}" href="${path}#${group.id}">
                    <h4 class="list-group-item-heading">${$('<span>').text(groupName).html()}</h4>
                </a>
            </li>
        `));
    }

    function openGroup(groupId) {
        $('.group-view-container').css('display', 'table');
        $('#rightSide').css('display', 'flex');
        $('#group-schedules').empty();
        activeGroup.id = groupId;
        activeGroup.name = $('#'+groupId).text().trim();
        $.get({url: 'api/users_groups.php', dataType: 'json'}, {'g_id': activeGroup.id})
         .done((response) => {
            $('#groupMembers').empty();
             response.forEach((email) => {
                 $.get({url: 'api/users.php', dataType: 'json'}, {'email': email.email})
                  .done((response) => {
                    $('#groupMembers').prepend($(`
                       <li class="list-group-item" style="height: 50px">
                            <div class="float-left">` + response[0].name + `</div>
                            <div class="float-right">` + email.email + `</div>
                       </li>
                    `));
                  })
             })
         })
         .fail((response) => {
             console.log(response);
         });

         // Display Schedules
         $.get({url: 'api/users_groups.php', dataType: 'json'}, {'g_id': activeGroup.id})
          .done((response) => {
              // gather group members
              let groupMembers = response;
              console.log(groupMembers);

              // for each member in group
              response.forEach((email) => {
                  $.get({url: 'api/users.php', dataType: 'json'}, {'email': email.email})
                   .done((response2) => {
                       // gather member info
                       let member = response2;
                       console.log(member);
                       drawSchedules(member[0]);
                   })
              })
          });

        $('#name').val(activeGroup.name);
        $('#groupNameHeader').text(activeGroup.name);
    }

    function drawSchedules(member) {
        let display = document.getElementById("group-schedules");
        let schedule;

        if (member.schedule !== null && member.schedule !== "") {
            schedule = JSON.parse(member.schedule);
        }

        let table =
        "<li class='list-group-item' style = 'clear: left; display: flow-root' id = " + member.name + "-schedule" + ">" +
            "<h2>" + member.name + "'s Schedule </h2>" +
            `<!-- Monday -->
            <table class = "schedule" id = "` + member.name + `monday">
                <tr>
                    <th>Monday</th>
                </tr>
            </table>

            <!-- Tuesday -->
            <table class = "schedule" id ="` + member.name + `tuesday">
                <tr>
                    <th>Tuesday</th>
                </tr>
            </table>

            <!-- Wednesday -->
            <table class = "schedule" id ="` + member.name + `wednesday">
                <tr>
                    <th>Wednesday</th>
                </tr>
            </table>

            <!-- Thursday -->
            <table class = "schedule" id ="` + member.name + `thursday">
                <tr>
                    <th>Thursday</th>
                </tr>
            </table>

            <!-- Friday -->
            <table class = "schedule" id ="` + member.name + `friday">
                <tr>
                    <th>Friday</th>
                </tr>
            </table>

            <!-- Saturday -->
            <table class = "schedule" id ="` + member.name + `saturday">
                <tr>
                    <th>Saturday</th>
                </tr>
            </table>

            <!-- Sunday -->
            <table id ="` + member.name + `sunday">
                <tr>
                    <th>Sunday</th>
                </tr>
            </table>
        </li>`;

        console.log(member);
        console.log(schedule);

        // display table
        display.innerHTML += table;
        // add schedule data
        if (schedule !== undefined && schedule !== null) {
            schedule.forEach((event) => drawEvent(member.name, event));
        }

    }

    function drawEvent(name, event) {
        let table = document.getElementById(name + event.day);
        //inserting new event in table
        let row = table.insertRow(-1);
        let cell = row.insertCell(-1);
        cell.innerHTML = event.name + "<br/>Start: " + event.start + "<br/>End: " + event.end;
    }
})(this);
