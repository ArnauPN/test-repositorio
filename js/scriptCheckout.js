// Funcion para recoger las horar de un dia en concreto 
function getHours(event) {
    let merchant_id = $("#info_ids").attr("merchant-id");
    let service_id = $("#info_ids").attr("service-id");

    let textoHora = document.getElementById("h3Horarios");

    let dateId = event.target.getAttribute('data-date');

    date = new Date(dateId);
    let diaSeleccionado = `${(date.getDate())}`.padStart(2,'0');
    let monthSelected2 = `${(date.getMonth()+1)}`.padStart(2,'0');
    let yearSelected2 = date.getFullYear();

    let daySelected = yearSelected2+"-"+monthSelected2+"-"+diaSeleccionado;

    let diaSelected = date.getDate();
    let monthSelected = getMonthSelected(date.getMonth());
    let yearSelected = date.getFullYear();
    textoHora.textContent = "¿Qué hora te viene mejor el  "+diaSelected+" de "+monthSelected+" de "+yearSelected+"?";
    
    let calendar = document.getElementById("listaHoras")
    
    llamadaCheckoutController();

    return date;

    function llamadaCheckoutController() {
        $.post("checkoutController.php", {
            "daySelected": daySelected,
            "merchant_id": merchant_id,
            "service_id": service_id
        },
            function (data){
                let arrayHours = JSON.parse(data);

                // si llega vacio añado clase tachar
                let new_hours = '';
                for (let i = 0; i < arrayHours.length; i++) {
                    new_hours += '<li class="divButton" attr-hour="'+arrayHours[i]+'">'+arrayHours[i]+'</li>';
                }
                clearText();
                calendar.innerHTML = new_hours;
                
                let divHoras = document.getElementById("horarios");
                divHoras.className = "horasOculto";
            }
        )
    }
}



// Funcion para separar con comas el precio
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(?:\d{2})+(?!\d))/g, ",");
}

// Funcion para separar con comas el precio
$('.points').each(function(){
    var v_pound = $(this).html();
    v_pound = numberWithCommas(v_pound);

    $(this).html(v_pound);
})

// Limpiar el texto de un elemento 
function clearText() {
    document.getElementById("listaHoras").innerHTML = "";
}

// Obtener el mes seleccionado con su nombre
function getMonthSelected(date) {
    daysMonth = ['enero','febrero','marzo', 'abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'] ;

    return daysMonth[date];
}

// Obtener el dia de la semana seleccionado por su nombre
function getDaySelected(date) {

    daysWeek = ['sunday','monday','tuesday', 'wednesday','thursday','friday','saturday'] ;

    return daysWeek[date];
}

// Al hacer click en una hora determinada, crear cita
$(document).on("click", ".divButton", function () {
    $('.divButton').each(function () {
        $(this).removeClass("activo");
    })

    $(this).toggleClass("activo");

    let atrHour = $(this).attr('attr-hour');
    let horaSeparada = atrHour.split(":");

    let horaWena = parseInt(horaSeparada[0]);
    let minutosWenos = parseInt(horaSeparada[1]);

    var obtenerDuration = $("#duration").attr("attr-duration");

    const hoy = new Date();
    hoy.setHours(horaWena, minutosWenos);

    const convert_moment = moment(hoy);

    var horaFinal = convert_moment.add(obtenerDuration, 'minutes');
    var weno = moment(horaFinal).format('HH:mm');

    let fechaConfirmacion = document.getElementById("fecha-oculta");
    let horaConfirmacion = document.getElementById("hora-oculta");

    $("#duracion-oculta").css('display','inline-block');
    $("#ocultado").css('display', 'flex');
    
    let fechaRecuperada = date;

    hoy.setDate(fechaRecuperada.getDate());
    hoy.setMonth(fechaRecuperada.getMonth());
    hoy.setFullYear(fechaRecuperada.getFullYear());

    let diaSelected = fechaRecuperada.getDate();
    let monthSelected = getMonthSelected(fechaRecuperada.getMonth());
    let monthSelected2 = fechaRecuperada.getMonth()+1;
    let yearSelected = fechaRecuperada.getFullYear();

    document.getElementById("fechaSeleccionada").value = yearSelected+"-"+monthSelected2+"-"+diaSelected+" "+atrHour;

    
    fechaConfirmacion.textContent = diaSelected+" de "+monthSelected+" de "+yearSelected;
    horaConfirmacion.textContent = atrHour+" a "+weno+" -";

    // Modal
    if($('#fecha-oculta').contents().length > 0){
        $('.btn').attr('id', 'myBtn');
    
        // Get the modal
        var modal = document.getElementById("myModal");
    
        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");
    
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
            modal.style.display = "none";
            }
        }
    }
});