let events = [];
const dateNow = new Date();

const calendarEl = document.getElementById('calendar');
const calendar = new FullCalendar.Calendar(calendarEl, {
    locale: 'id',
    initialView: 'dayGridMonth',
    eventColor: 'red',
});

calendar.render();

function renderEvents(month, year) {
    $('#calendar-loader').fadeIn(0);

    $.ajax({
        url: `/holidays/json`,
        method: 'POST',
        dataType: 'json',
        data: {
            month, year
        },
        success(res) {
            calendar.removeAllEvents();

            res.map((holiday) => {
                const endDate = moment(holiday.date_end).add(1, 'days');

                calendar.addEvent({
                    title: holiday.information,
                    start: `${holiday.date_start}`,
                    end: endDate.format('YYYY-MM-DD'),
                });
            });

            calendar.refetchEvents();
            $('#calendar-loader').fadeOut();
        },
    });
}

$('body').on('click', '.fc-prev-button, .fc-next-button, .fc-today-button', function() {
    const month = calendar.getDate().getMonth() + 1;
    const year = calendar.getDate().getFullYear();

    renderEvents(month, year);
});

renderEvents(dateNow.getMonth() + 1, dateNow.getFullYear());
