$(document).ready(function(e) {


//Aqui lleno el data Combo de Rutas
$.post("/rutasMapa",
    function(data) {
        var p = JSON.parse(data);
        $.each(p, function (i, item) {
            $('#obtenerRutas').append('<option value="'+item.id_ruta+'">'+item.ruta+'</option>'

            );
        });
});


});
