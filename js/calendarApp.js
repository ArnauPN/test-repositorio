function CalendarApp(date) {

    if (!(date instanceof Date)) {
        date = new Date();
    }

    this.days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    this.months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    this.aptDates = [new Date(2020, 4, 30).toString(), new Date(2016, 4, 1).toString()];
    this.eles = {};
    this.calDaySelected = null;

    this.calendar = document.getElementById("calendar-app");

    this.calendarView = document.getElementById("dates");

    this.calendarMonthDiv = document.getElementById("calendar-month");
    this.calendarMonthLastDiv = document.getElementById("calendar-month-last");
    this.calendarMonthNextDiv = document.getElementById("calendar-month-next");

    this.todayIsSpan = document.getElementById("footer-date");
    this.dayViewEle = document.getElementById("day-view");
    this.dayViewExitEle = document.getElementById("day-view-exit");
    this.dayViewDateEle = document.getElementById("day-view-date");

    /* Start the app */
    this.showView(date);
    this.addEventListeners();
}

CalendarApp.prototype.addEventListeners = function () {
    this.calendar.addEventListener("click", this.mainCalendarClickClose.bind(this));
    this.calendarMonthLastDiv.addEventListener("click", this.showNewMonth.bind(this));
    this.calendarMonthNextDiv.addEventListener("click", this.showNewMonth.bind(this));
};

// var mes = 1;

CalendarApp.prototype.showView = function (date) {
    if (!date || (!(date instanceof Date))) date = new Date();
    var now = new Date(date),
        y = now.getFullYear(),
        m = now.getMonth();

    var lastDayOfM = new Date(y, m + 1, 0).getDate();
    var startingD = new Date(y, m, 0).getDay();
    var lastM = new Date(y, now.getMonth() - 1, 1);
    var nextM = new Date(y, now.getMonth() + 1, 1);

    this.calendarMonthDiv.classList.remove("cview__month-activate");
    this.calendarMonthDiv.classList.add("cview__month-reset");

    while (this.calendarView.firstChild) {
        this.calendarView.removeChild(this.calendarView.firstChild);
    }

    // build up spacers
    for (var x = 0; x < startingD; x++) {
        var spacer = document.createElement("div");
        spacer.className = "cview--spacer";
        this.calendarView.appendChild(spacer);
    }

    var _that = this;
    setTimeout(function () {
        _that.calendarMonthDiv.classList.add("cview__month-activate");
    }, 50);

    this.calendarMonthDiv.textContent = this.months[now.getMonth()] + " " + now.getFullYear();
    this.calendarMonthDiv.setAttribute("data-date", now);

    var mesActual = now.getMonth()+1;
    var añoActual =  now.getFullYear();

    this.calendarMonthLastDiv.textContent = "← ";
    this.calendarMonthLastDiv.setAttribute("data-date", lastM);

    this.calendarMonthNextDiv.textContent = " →";
    this.calendarMonthNextDiv.setAttribute("data-date", nextM);
    
    for (var z = 1; z <= lastDayOfM; z++) {

        let _date = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + z;

        var day = document.createElement("div");

        day.className = "cview--date";
        

        day.innerHTML = z;

        id = "day" + _date;

        
        day.setAttribute("data-date", _date);
        day.setAttribute("id", id);

        day.setAttribute("onclick", "getHours(event)");

        var today_date = new Date();

        var dia = today_date.getDate();
        var año = today_date.getFullYear();
        var mes = today_date.getMonth()+1;

        var hoy = año + "-" + mes + "-" + dia;

        this.calendarView.appendChild(day);
        date.setDate(date.getDate());

        let fecha2 = new Date(_date);
        let fecha1 = new Date(hoy);

        if (fecha1 > fecha2) {
            $('#'+id).addClass('previous');
        }
    }
    // console.log(hoy);

    let merchant_id = $("#info_ids").attr("merchant-id");
    let service_id = $("#info_ids").attr("service-id");

    $.post("calendarController.php", {
        "merchant_id": merchant_id,
        "service_id": service_id,
        "hoy": hoy,
        "mesActual": mesActual,
        "añoActual": añoActual
        },
        function (data){
            let arrayHours = JSON.parse(data);

            for(i = 0; i < arrayHours.length; i++){
                
                $('#'+arrayHours[i]).addClass('previous');
                $('#'+arrayHours[i]).removeAttr('onclick');
            }
        }
    )

    // Cambiar estilo dia cambiado
    var diaPulsado = document.getElementsByClassName("cview--date");

    for (let i = 0; i < diaPulsado.length; i++) {
        diaPulsado[i].addEventListener("click", function () {
            if ($(diaPulsado[i]).hasClass('previous')) {
                $(".pulsado").removeClass("pulsado");
            }else{
                $(".pulsado").removeClass("pulsado");
                diaPulsado[i].classList.add("pulsado");
            }
        });
    }
}

CalendarApp.prototype.showDay = function (e, dayEle) {
    e.stopPropagation();
    if (!dayEle) {
        dayEle = e.currentTarget;
    }
    var dayDate = new Date(dayEle.getAttribute('data-date'));

    this.calDaySelected = dayEle;

    this.openDayWindow(dayDate);


};
// CalendarApp.prototype.closeDayWindow = function () {
//     this.dayViewEle.classList.remove("calendar--day-view-active");
//     this.closeNewEventBox();
// };
CalendarApp.prototype.mainCalendarClickClose = function (e) {
    if (e.currentTarget != e.target) {
        return;
    }

    this.dayViewEle.classList.remove("calendar--day-view-active");
    this.closeNewEventBox();
};
CalendarApp.prototype.addNewEventBox = function (e) {
    var target = e.currentTarget;
    this.dayEventBoxEle.setAttribute("data-active", "true");
    this.dayEventBoxEle.setAttribute("data-date", target.getAttribute("data-date"));

};
CalendarApp.prototype.closeNewEventBox = function (e) {

    if (e && e.keyCode && e.keyCode != 13) return false;

    this.dayEventBoxEle.setAttribute("data-active", "false");
    // reset values
    this.resetAddEventBox();

};
CalendarApp.prototype.saveAddNewEvent = function () {
    var saveErrors = this.validateAddEventInput();
    if (!saveErrors) {
        this.addEvent();
    }
};

CalendarApp.prototype.convertTo23HourTime = function (stringOfTime, AMPM) {
    // convert to 0 - 23 hour time
    var mins = stringOfTime.split(":");
    var hours = stringOfTime.trim();
    if (mins[1] && mins[1].trim()) {
        hours = parseInt(mins[0].trim());
        mins = parseInt(mins[1].trim());
    } else {
        hours = parseInt(hours);
        mins = 0;
    }
    hours = (AMPM == 'am') ? ((hours == 12) ? 0 : hours) : (hours <= 11) ? parseInt(hours) + 12 : hours;
    return [hours, mins];
};
var timeOut = null;
var activeEle = null;
CalendarApp.prototype.inputChangeLimiter = function (ele) {

    if (ele.currentTarget) {
        ele = ele.currentTarget;
    }
    if (timeOut && ele == activeEle) {
        clearTimeout(timeOut);
    }

    var limiter = CalendarApp.prototype.textOptionLimiter;

    var _options = ele.getAttribute("data-options").split(",");
    var _format = ele.getAttribute("data-format") || 'text';
    timeOut = setTimeout(function () {
        ele.value = limiter(_options, ele.value, _format);
    }, 600);
    activeEle = ele;

};
CalendarApp.prototype.resetAddEventBox = function () {
    this.dayEventAddForm.nameEvent.value = '';
    this.dayEventAddForm.nameEvent.classList.remove("add-event-edit--error");
    this.dayEventAddForm.endTime.value = '';
    this.dayEventAddForm.startTime.value = '';
    this.dayEventAddForm.endAMPM.value = '';
    this.dayEventAddForm.startAMPM.value = '';
};
CalendarApp.prototype.showNewMonth = function (e) {
    var date = e.currentTarget.dataset.date;
    var newMonthDate = new Date(date);
    this.showView(newMonthDate);
    // this.closeDayWindow();
    return true;
};

var calendar = new CalendarApp();
