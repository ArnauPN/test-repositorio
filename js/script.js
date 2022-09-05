var pulsado = [];

$(document).ready(function() {
    $("#desplegable").on('click', function () {
        if ($('.menu-float').hasClass('menu-float-trigger')) {
            $('.menu-float').removeClass('menu-float-trigger');
        }else{
            $('.menu-float').addClass('menu-float-trigger');
        }
    });

    $("#cruz").on("click", function () {
        $("#banner").css("display", "none");
    })
});

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(?:\d{2})+(?!\d))/g, ",");
}

function mostrarTiendas(params) {
    pulsado = [];
    
    let divOculto = document.getElementById("oculto");
    let shopName = document.getElementById("name-tienda");
    let streetName = document.getElementById("calle-tienda");
    let direccionMaps = document.getElementById("maps");
    let divFeedback = document.getElementById("li-feedback");
    let divEvaluacion = document.getElementById("evaluacion");

    let atr_id = params;

    $.post("peluqueriaController.php", {
        "atr_id": atr_id,
    },
    function (data) {
        shopName.style.display = "inline-block";

        let infoData = JSON.parse(data);

        let category = infoData[0];
        let name = infoData[1];
        let info_comments = infoData[2];

        if (info_comments.length == 1) {
            var infoComentarios = info_comments.length+" opinión";
        }else{
            var infoComentarios = info_comments.length+" opiniones";
        }

        divEvaluacion.innerHTML = infoComentarios;

        var infoHtml2 = '';
        if (info_comments.length >= 1) {
            
            infoHtml2 += `
                    <li class="li-feedback">
                        <div>
                            <div class="container-feedback">
                                <h3 class="h3Footer">`+infoComentarios+`</h3>
                                <ul class="ul-feedback">
                                    <li class="sc-epGmkI cVekhz">`;
                                        for (z = 0; z < info_comments.length; z++) {
                                            
            infoHtml2 += `
                                        <div class="sc-dphlzf ikKFIJ">
                                            <div class="sc-fCPvlr bjHvbX">
                                                <div class="sc-gAmQfK czadeg">
                                                    <div class="sc-hAXbOi euMTAB">`+info_comments[z].nombre_cliente+`</div>
                                                    <div class="sc-cfWELz grjEhe">publicado el `+info_comments[z].fecha_comentario+`</div>
                                                </div>
                                            </div>
                                            <div class="sc-kAdXeD bAqZxr">`
                                                    +info_comments[z].comentario+`
                                            </div>
                                        </div>
                                        `}`
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
            `;
        }
        

        divFeedback.innerHTML = infoHtml2;

        let contador = 1;
        var infoHtml = '';

        for(i = 0; i < category.length; i++){
            if (category[i].idService != '48c95b15-d0cb-4737-beef-d015e119adc5' && category[i].idService != 'd12f08e2-a478-4aaa-90c5-5edbc5184929' && category[i].idService != '5d1d3686-b233-4a02-a3a7-970796df14fe') {
                infoHtml += `

                    <hr>
                    
                    <div class='titulo-style'>
                        <h4 class='pulsador' data-id=`+contador+` data-idservice='`+category[i].idService+`'>`+category[i].titleService+`</h4>
                        <svg aria-hidden='true' focusable='false' data-prefix='fas' data-icon='angle-down' class='svg-inline--fa fa-angle-down fa-w-10 sc-ivVeuv feQvTV' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512' up='false'><path fill='currentColor' d='M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z'></path></svg>
                    </div>

                    <div style='display:none; padding-top: 10px' data-id=`+category[i].idService+`>`;

                    infoHtml += `</div>`;
                    contador++;
            }
        }
        divOculto.innerHTML = infoHtml;

        // Nombre de la tienda seleccionada
        let nombreTienda = name;

        // Direccion de la tienda
    
        let nombreCalle = category[0].direccionShore.line1;
        let numPostal = category[0].direccionShore.postal_code;
        let ciudadShop = category[0].direccionShore.city;

        let fullStretName = nombreCalle+", "+numPostal+" "+ciudadShop;

        direccionMaps.setAttribute("src", "https://www.google.com/maps/embed/v1/place?key=AIzaSyDdN0jti71h62shAZnI3J-Jta76j451pmo&q="+fullStretName);

        shopName.innerHTML = nombreTienda;
        streetName.innerHTML = fullStretName;

        $(".pulsador").on('click', function () {
            let infoHtml = '';
            var elementoPadre = $(this);

            let el = elementoPadre.parent().next();

            el.toggleClass('classOculto');

            let idServicio = elementoPadre.attr("data-idservice");
            if (typeof pulsado[idServicio] !== 'undefined'){
                return;
            }else{
                elementoPadre.parent().append('<div class="spinner" style="display:inline-block"></div>')
            }

            pulsado[idServicio] = true;

            $.post("serviciosController.php", {
                "idServicio": idServicio,
            },
            function (data) {
                $(".spinner").css("display","none");
                let infoData = JSON.parse(data);

                for(a = 0; a < infoData.length; a++){
                
                    infoHtml += `
                    
                    <div class='divsitos' data-idaumentada=`+infoData[a].id+`>
                            <div style='color:#3D3D3D; font-size:16px;'>`
                                +infoData[a].name+
                            `</div>
                            <div style='color: rgb(118,118,118); font-size:13px'>`
                                +infoData[a].description+
                            `</div>

                            <div style='margin-top: 15px; color: #0099FF'>
                                <div style='display:inline-block; margin-right:20px'>`
                                    +infoData[a].duration+`
                                </div>

                                <div class='points' style='display:inline-block'>`+infoData[a].amount+` €<br>
                                </div>
                            </div>
                    </div>`
                    
                }

                el.html(infoHtml);

                $(".divsitos").on('click', function () {
                    location.href = 'checkout.php?token=' + $(this).attr("data-idaumentada");
                });
                
                $('.points').each(function(){
                    var v_pound = $(this).html();
                    v_pound = numberWithCommas(v_pound);
            
                    $(this).html(v_pound)
                    
                })
            })
        });

        
    })

}