$(document).ready(function(){
    let id = null;

    getWorkingList();

    $("#form-work").submit(function(e){
        e.preventDefault();
        let data = {
            name: $('#name').val(),
            start_date: $('#date-picker').data('daterangepicker').startDate.format('YYYY-MM-DD'),
            end_date: $('#date-picker').data('daterangepicker').endDate.format('YYYY-MM-DD'),
            status: $('#status').val()
        };
        save(data, id);
    });
    $('#date-picker').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('#calendar').fullCalendar({
        header: { center: 'month,agendaWeek' },
        eventMouseover:function(event, domEvent, view){
            let el=$(this);
            let layer = `
                <div id="events-layer" class="fc-transparent">
                    <span id="event-edit-${event.id}" class="btn btn-dark btn-sm">Edit</span>
                    <span id="event-delete-${event.id}" class="btn btn-danger btn-sm">Delete</span>
                </div>
            `;
            el.append(layer);
            el.find(".fc-bg").css("pointer-events","none");
            $("#event-edit-"+event.id).click(function(){
                id = event.id;
                $('#name').val(event.title);
                $('#status').val(event.status);
                $('#date-picker').data('daterangepicker').setStartDate(event.start);
                $('#date-picker').data('daterangepicker').setEndDate(event.end);
                $('#cancel').show();
            });
            $("#event-delete-"+event.id).click(function(){
                remove(event.id);
            });
        },
        eventMouseout:function(event){
            $("#events-layer").remove();
        }
    });
    $("#cancel").click(function() {
        id = null;
        $('#name').val('');
        $('#cancel').hide();
    });
});

function showErrors(errors) {
    let html = '';
    errors.map(item => {
        html += `<div class="row alert alert-danger" role="alert">
            ${item}
        </div>`;
    });
    $('#errors').html(html);
    $('#errors').show();
}

function remove(id){
    $.ajax({
        method: 'POST',
        url: `/works/${id}/delete`,
        success: function(res) {
            $("#cancel").click();
            $('#calendar').fullCalendar('removeEvents', id);
        },
        error: function (request, status, error) {
            console.log('Error ', request, error);
        }
    });
}

function save(data, id){
    $('#errors').hide();
    let url = id === null ? '/works/create' : `/works/${id}/update`;
    $.ajax({
        method: 'POST',
        url: url,
        data: data,
        success: function(res) {
            data = {
                id: id || res.data.id,
                title: data.name,
                start: data.start_date,
                end: data.end_date,
                overlap: false,
                backgroundColor: getColor(data.status),
                status: data.status,
                textColor: 'white'
            };
            $('#calendar').fullCalendar('removeEvents', [data.id]);
            $('#calendar').fullCalendar('renderEvent', data, true);
            $('#cancel').click();
        },
        error: function (request, status, error) {
            showErrors(request.responseJSON.data || []);
        }
    });
}

function getWorkingList() {
    $.ajax({
        method: 'GET',
        url: '/works/list',
        success: function (res) {
            let sources = [];
            res.data.map(item => {
                sources.push({
                    id: item.id,
                    title: item.name,
                    start: item.start_date,
                    end: item.end_date,
                    overlap: false,
                    backgroundColor: getColor(item.status),
                    status: item.status,
                    textColor: 'white'
                });
            });
            $("#calendar").fullCalendar('addEventSource', sources)
        },
        error: function (request, status, error) {
            console.log('Error ', request.responseText);
        }
    });
}

function getColor(status) {
    switch (+status) {
        case 2:
            return 'green';
            break;
        case 3:
            return 'pink';
            break;
        default:
            return 'blue';
            break;
    }
}