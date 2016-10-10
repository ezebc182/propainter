/**
 * Created by Ezequiel on 05/10/2016.
 */
/**
 * Created by lukas on 01/02/2016.
 */
$(document).ready(function () {
    $(".modal-trigger").leanModal({
        ready: function () {
            $("#utenti_current_password").focus();
        },
        complete: function () {
            $("#utenti_current_password").val("");
            $("#utenti_new_password").val("");
            $("#utenti_new_password_confirm").val("");
            $("#resultados").html("");
        }, // Callback for Modal open
    });
});
$("#utenti_current_password").on("change", function (e) {
    e.preventDefault();
    $("#resultados").html("");
    $.ajax({
        url: PUBLIC_PATH + "utenti/check_current_password/" + $("#utenti_id").val(),
        type: "post",
        data: {current_password: $("#utenti_current_password").val()}

    }).done(function (data) {
console.log(data);
        if(data.code=="182"){
            $("#utenti_current_password").removeClass("valid").addClass("invalid");
            $("#label_utenti_current_password").attr("data-error", data.mensaje);

        }


    });
});
$("#utenti_new_password").on("change", function (e) {
    e.preventDefault();
    $("#resultados").html("");
    $.ajax({
        url: PUBLIC_PATH + "utenti/checkSecurityPassword/",
        type: "post",
        data: {pwd: $(this).val()}

    }).done(function (data) {
        $("#utenti_current_password").removeClass("valid").addClass("invalid");
        $("#label_utenti_utenti_new_password").attr("data-error", data);
//        $("#resultados").html("").append(data);


    });
});

$("#btnCambiarPassword").on("click", function (e) {
    e.preventDefault();
    $.ajax({
        url: PUBLIC_PATH + "utenti/modificare_password/" + $("#utenti_id").val(),
        type: "post",

    }).done(function (data) {
        $("#resultados").html("").append(data);
    });
});
$("#aggiornare").click(function (event) {
    event.preventDefault();
    var id = $("#id_usuario").val();
    $.ajax({
        url: PUBLIC_PATH + "utenti/modificare_password/" + id,
        type: 'post',
        dataType: 'json',
        data: $('#form-cambiar-pass').serialize(),
        success: function (data) {

            var respuesta = data.respuesta;

            if (respuesta == 1) {
                var exito = "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button><p><i class='fa fa-exclamation fa-fw'></i> Contraseña actualizada </p></div>";
                $("#resultado-ajax").html(exito);
                $("#usuarios_password_actual").val("");
                $("#usuarios_password_nuevo").val("");
                $("#usuarios_password_nuevo_bis").val("");
                $("#actualizar").hide();
                $("#seguridad").html("");
                $("#cancelar").attr('value', 'Aceptar');
                $("#cancelar[value='Aceptar']").on("click", function () {
                    location.reload();
                });

            }
            else {
                var error = "<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>×</button><p><i class='fa fa-exclamation fa-fw'></i> " + respuesta + " </p></div>";
                $("#resultado-ajax").html(error);
            }
        },
        error: function (data, textStatus) {
            //alert(data.responseText); //para control
            //alert(textStatus); //para control
        }
    });
});

$("#cancelar").click(function (event) {

    $("#usuarios_password_actual").val("");
    $("#usuarios_password_nuevo").val("");
    $("#usuarios_password_nuevo_bis").val("");
    $("#resultado-ajax").html("");
    $("#seguridad").html("");
    $("#actualizar").show();
    $("#cancelar").attr('value', 'Cancelar');
});


