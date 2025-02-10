document.getElementById("toggle-descripcion-larga").addEventListener("click", function () {
    const filasDescripcionLarga = document.querySelectorAll(".descripcion-completa");
    const filasDescripcionCorta = document.querySelectorAll(".descripcion-corta");

    filasDescripcionCorta.forEach(function (descripcion) {
        if (descripcion.style.display === "none" || descripcion.style.display === "") {
            descripcion.style.display = "inline";
        } else {
            descripcion.style.display = "none";
        }
    });

    filasDescripcionLarga.forEach(function (descripcion) {
        if (descripcion.style.display === "none" || descripcion.style.display === "") {
            descripcion.style.display = "inline";
        } else {
            descripcion.style.display = "none";
        }
    });
});
