import {ref} from 'vue';
// AJAX
export function editarTareasUsuario() {
    let editar = ref(false);
    const editarTarea = async (id,nombre,descripcion,fechaVencimiento,prioridad,cat) => {
        document.getElementById("idTarea").value = id;
        document.getElementById("inputNombreTarea").value = nombre;
        document.getElementById("inputDescripcionTarea").value = descripcion;
        document.getElementById("inputPrioridad").value = prioridad;
        const selectCat = document.getElementById("inputCategoria");
        let categoriaIdParaJson = null;

        if (selectCat) {
            // Buscamos entre las opciones la que tenga el texto igual a 'cat'
            const opciones = Array.from(selectCat.options);
            const opcionEncontrada = opciones.find(opt => opt.text === cat);

            if (opcionEncontrada) {
                selectCat.value = opcionEncontrada.value; // Selecciona el ID en el HTML
                categoriaIdParaJson = parseInt(opcionEncontrada.value);
            } else {
                selectCat.value = ""; // Si no hay coincidencia, "Ninguna"
            }
        }

        // Formatear fecha para input type="date" (YYYY-MM-DD)
        if (fechaVencimiento && fechaVencimiento.includes('-')) {
            const partes = fechaVencimiento.split('-');
            const fechaFormateada = `${partes[2]}-${partes[1]}-${partes[0]}`;
            document.getElementById("inputDate").value = fechaFormateada;
        }

        editar.value = true;
    }
    
    return { editarTarea,editar };
}