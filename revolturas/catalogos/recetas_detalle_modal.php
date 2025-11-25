<?php
/*Desarrollado por: CCA Consultores TI */
/*Contacto: contacto@ccaconsultoresti.com */
/*Actualizado: Noviembre-2024*/
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="detalleRecetaModalLabel">Detalle de la Receta: <span id="nombre_receta"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <input type="text" value="<?= $_POST['id_receta'] ?>" id="id_receta" class="d-none" name="rre_id">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="detalleRecetaTabla">
                    <thead>
                        <tr>
                            <th>No. Tarima</th>
                            <th>Parámetro</th>
                            <th>Comparación</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        obtener_detalle();
    });

    async function validarTarimaValor(tarima) {
        if (tarima.Parametro === 'tar_fino') {
            tarima.Nombre_valor = (tarima.Valor === 'F') ? 'Sí' : 'No';
        } else if (tarima.Parametro === 'cal_id') {
            try {
                tarima.Nombre_valor = await obtener_calidad(tarima.Valor);
            } catch (error) {
                console.error('Error al obtener la calidad:', error);
                tarima.Nombre_valor = 'Error al obtener la calidad';
            }
        } else if(tarima.Parametro === 'tar_rechazado'){
            tarima.Nombre_valor = (tarima.Valor === 'C') ? 'Sí' : 'No';
        }else{
            tarima.Nombre_valor = tarima.Valor;
        }
        
        return tarima;
    }

    // Mostrar en la tabla después de validar
    async function mostrarDetallesEnTabla(detalles) {
        $('#nombre_receta').text(`${detalles[0].Cliente} - ${detalles[0].Descripcion_Receta}`);
        const tablaBody = $('#detalleRecetaTabla tbody');
        tablaBody.empty();

        for (const detalle of detalles) {
            const tarimaValidada = await validarTarimaValor(detalle);
            tablaBody.append(`
            <tr>
                <td>${tarimaValidada.No_Tarima}</td>
                <td>${tarimaValidada.Nombre_parametro}</td>
                <td>${tarimaValidada.Comparacion}</td>
                <td>${tarimaValidada.Nombre_valor}</td>
            </tr>
        `);
        }
    }

    async function obtener_detalle() {
        const id_receta = $('#id_receta').val();

        if (!id_receta) {
            alert('ID de receta no válido.');
            return;
        }

        try {
            const response = await $.ajax({
                type: 'POST',
                url: 'catalogos/recetas_detalle_listado.php',
                data: {
                    'id_receta': id_receta
                }
            });

            try {
                const detalles = JSON.parse(response);
                await mostrarDetallesEnTabla(detalles);
            } catch (error) {
                console.error('Error al parsear los detalles de la receta:', error);
                alert('Error al procesar los datos recibidos.');
            }

        } catch (error) {
            console.error('Error al obtener el detalle de la receta:', error);
            alert('Error al obtener el detalle de la receta.');
        }
    }

    function obtener_calidad(cal_id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: 'catalogos/recetas_actualizar_modal.php',
                data: {
                    action: 'obtener_calidad',
                    cal_id: cal_id
                },
                success: function(response) {
                    try {
                        let calidad = JSON.parse(response);
                        resolve(calidad); // Devuelve la calidad obtenida
                    } catch (error) {
                        reject('Error al parsear la respuesta del servidor.');
                    }
                },
                error: function() {
                    reject('Error al obtener la calidad desde el servidor.');
                }
            });
        });
    }
</script>