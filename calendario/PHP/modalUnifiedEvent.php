<?php

?>

<div class="modal" id="unifiedEventModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title">Gestión de eventos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form name="formUnifiedEvent" id="formUnifiedEvent" method="POST">
                <div class="modal-body">
                    <!-- Hidden field for event ID (edit mode) -->
                    <input type="hidden" name="event_id" id="event_id" value="">
                    
                    <!-- Event Type Selector -->
                    <div class="form-group">
                        <div id="event-type-selector">
                            <input type="radio" name="event_type" value="event" id="event_type_event" checked>
                            <label for="event_type_event">Evento</label>
                            <input type="radio" name="event_type" value="birthday" id="event_type_birthday">
                            <label for="event_type_birthday">Cumpleaños</label>
                        </div>
                    </div>

                    <!-- Event Fields -->
                    <div id="event-fields">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="evento" class="control-label">Nombre del Evento</label>
                                    <input type="text" class="form-control" name="evento" id="evento" 
                                           placeholder="Nombre del Evento" required/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="hora_inicio" class="control-label">Hora</label>
                                    <input type="time" class="form-control" name="hora_inicio" id="hora_inicio">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="descripcion" class="control-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" 
                                      placeholder="Descripción del evento" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="fecha_inicio" class="control-label">Fecha Inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" 
                                           required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="fecha_fin" class="control-label">Fecha Final</label>
                                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" 
                                           required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recurring Event Options -->
                        <div class="form-group">
                            <label class="recurring-checkbox">
                                <input type="checkbox" name="es_recurrente" id="es_recurrente" value="1">
                                <span>🔄 Evento recurrente (se repite semanalmente)</span>
                            </label>
                        </div>
                        
                        <div id="recurrence-options" style="display: none;">
                            <div class="form-group">
                                <label class="control-label">Repetir los días:</label>
                                <div class="days-selector">
                                    <label class="day-option"><input type="checkbox" name="dias[]" value="1"> L</label>
                                    <label class="day-option"><input type="checkbox" name="dias[]" value="2"> M</label>
                                    <label class="day-option"><input type="checkbox" name="dias[]" value="3"> X</label>
                                    <label class="day-option"><input type="checkbox" name="dias[]" value="4"> J</label>
                                    <label class="day-option"><input type="checkbox" name="dias[]" value="5"> V</label>
                                    <label class="day-option"><input type="checkbox" name="dias[]" value="6"> S</label>
                                    <label class="day-option"><input type="checkbox" name="dias[]" value="0"> D</label>
                                </div>
                                <small class="form-text text-muted">Selecciona los días en los que se repetirá el evento</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="fecha_fin_recurrencia" class="control-label">Repetir hasta:</label>
                                <input type="date" class="form-control" name="fecha_fin_recurrencia" id="fecha_fin_recurrencia">
                                <small class="form-text text-muted">Fecha límite para generar las repeticiones</small>
                            </div>
                        </div>
                    </div>

                    <!-- Birthday Fields (hidden by default) -->
                    <div id="birthday-fields" style="display: none;">
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <label for="birthday_name" class="control-label">Nombre de la Persona</label>
                                    <input type="text" class="form-control" name="birthday_name" id="birthday_name" 
                                           placeholder="Nombre">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="birthday_date" class="control-label">Fecha</label>
                                    <input type="date" class="form-control" name="birthday_date" id="birthday_date">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Birthday Color Selection -->
                        <div class="form-group">
                            <label class="control-label">Color del Cumpleaños</label>
                            <div id="birthday-color-palette" class="color-palette single-row">
                                <input type="radio" name="birthday_color" id="birthday_color_1" value="#FF69B4" checked>
                                <label for="birthday_color_1" class="color-option" style="background-color: #FF69B4;" title="Rosa"></label>

                                <input type="radio" name="birthday_color" id="birthday_color_2" value="#9C27B0">
                                <label for="birthday_color_2" class="color-option" style="background-color: #9C27B0;" title="Púrpura"></label>

                                <input type="radio" name="birthday_color" id="birthday_color_3" value="#E91E63">
                                <label for="birthday_color_3" class="color-option" style="background-color: #E91E63;" title="Rosa intenso"></label>

                                <input type="radio" name="birthday_color" id="birthday_color_4" value="#673AB7">
                                <label for="birthday_color_4" class="color-option" style="background-color: #673AB7;" title="Púrpura profundo"></label>

                                <input type="radio" name="birthday_color" id="birthday_color_5" value="#3F51B5">
                                <label for="birthday_color_5" class="color-option" style="background-color: #3F51B5;" title="Índigo"></label>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Color Selection -->
                    <div class="form-group" id="color-selection-group">
                        <label class="control-label">Color del Evento</label>
                        <div id="color-palette" class="color-palette single-row">
                            <input type="radio" name="color_evento" id="color_1" value="#FF5722" checked>
                            <label for="color_1" class="color-option" style="background-color: #FF5722;" title="Naranja"></label>

                            <input type="radio" name="color_evento" id="color_2" value="#FFC107">
                            <label for="color_2" class="color-option" style="background-color: #FFC107;" title="Amarillo"></label>

                            <input type="radio" name="color_evento" id="color_3" value="#8BC34A">
                            <label for="color_3" class="color-option" style="background-color: #8BC34A;" title="Verde claro"></label>

                            <input type="radio" name="color_evento" id="color_4" value="#009688">
                            <label for="color_4" class="color-option" style="background-color: #009688;" title="Verde azulado"></label>

                            <input type="radio" name="color_evento" id="color_5" value="#2196F3">
                            <label for="color_5" class="color-option" style="background-color: #2196F3;" title="Azul"></label>

                            <input type="radio" name="color_evento" id="color_6" value="#9c27b0">
                            <label for="color_6" class="color-option" style="background-color: #9c27b0;" title="Morado"></label>

                            <input type="radio" name="color_evento" id="color_7" value="#E91E63">
                            <label for="color_7" class="color-option" style="background-color: #E91E63;" title="Rosa"></label>

                            <input type="radio" name="color_evento" id="color_8" value="#795548">
                            <label for="color_8" class="color-option" style="background-color: #795548;" title="Marrón"></label>

                            <input type="radio" name="color_evento" id="color_9" value="#607D8B">
                            <label for="color_9" class="color-option" style="background-color: #607D8B;" title="Gris azulado"></label>

                            <input type="radio" name="color_evento" id="color_10" value="#FF9800">
                            <label for="color_10" class="color-option" style="background-color: #FF9800;" title="Naranja oscuro"></label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" id="save-btn" class="btn btn-success">Guardar</button>
                    <button type="button" id="delete-btn" class="btn btn-danger" disabled>Eliminar</button>
                    <button type="button" class="btn btn-secondary" id="btn-salir" data-dismiss="modal">Salir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions - will be called from index.php
window.initializeUnifiedModal = function() {
    if ($('#unifiedEventModal').length === 0) {
        return false;
    }
    
    window.unifiedModalMode = 'create';
    
    // Function to open modal in create mode
    window.openUnifiedModalForCreate = function(defaultDate) {
        if ($('#unifiedEventModal').length === 0) {
            alert('Error: Modal no encontrado. Recarga la pagina.');
            return;
        }
        
        window.unifiedModalMode = 'create';
        
        $('#event_id').val('');
        $('#evento').val('');
        $('#hora_inicio').val('');
        $('#descripcion').val('');
        $('#birthday_name').val('');
        
        // Reset recurring event options
        $('#es_recurrente').prop('checked', false);
        $('#recurrence-options').hide();
        $('input[name="dias[]"]').prop('checked', false);
        $('#fecha_fin_recurrencia').val('');
        
        var dateToUse = defaultDate;
        if (!dateToUse) {
            var today = new Date();
            dateToUse = today.getFullYear() + '-' + 
                                String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                                String(today.getDate()).padStart(2, '0');
        }
        
        $('#fecha_inicio').val(dateToUse);
        $('#fecha_fin').val(dateToUse);
        $('#birthday_date').val(dateToUse);
        
        // Make sure fecha_fin is visible and enabled for normal events
        $('#fecha_fin').prop('disabled', false).closest('.form-group').show();
        
        $('input[name="event_type"][value="event"]').prop('checked', true).trigger('change');
        
        $('input[name="color_evento"]:first').prop('checked', true);
        
        $('#save-btn').text('Guardar').prop('disabled', false);
        $('#delete-btn').prop('disabled', true);
        
        try {
            $('#unifiedEventModal').modal({
                backdrop: 'static',
                keyboard: true,
                focus: true,
                show: true
            });
        } catch (error) {
            $('#unifiedEventModal').addClass('show').css('display', 'block');
            $('body').addClass('modal-open');
            
            if ($('.modal-backdrop').length === 0) {
                $('<div class="modal-backdrop fade show"></div>').appendTo('body');
            }
        }
    };
    
    // Function to open modal in edit mode
    window.openUnifiedModalForEdit = function(eventData) {
        window.unifiedModalMode = 'edit';
        
        // Populate form fields with event data
        $('#event_id').val(eventData.id);
        $('#evento').val(eventData.title);
        $('#hora_inicio').val(eventData.time || '');
        $('#descripcion').val(eventData.description || '');
        
        // Convert DD-MM-YYYY to YYYY-MM-DD for date inputs
        if (eventData.start_date) {
            var startParts = eventData.start_date.split('-');
            if (startParts.length === 3) {
                // startParts[0] = DD, startParts[1] = MM, startParts[2] = YYYY
                var convertedStartDate = startParts[2] + '-' + startParts[1] + '-' + startParts[0];
                $('#fecha_inicio').val(convertedStartDate);
            }
        }
        if (eventData.end_date) {
            var endParts = eventData.end_date.split('-');
            if (endParts.length === 3) {
                // endParts[0] = DD, endParts[1] = MM, endParts[2] = YYYY
                var convertedEndDate = endParts[2] + '-' + endParts[1] + '-' + endParts[0];
                $('#fecha_fin').val(convertedEndDate);
            }
        }
        
        // Set to event mode
        $('input[name="event_type"][value="event"]').prop('checked', true).trigger('change');
        
        // Set color selection if available
        if (eventData.color) {
            $('input[name="color_evento"][value="' + eventData.color + '"]').prop('checked', true);
        }
        
        // Update button states for edit mode
        $('#save-btn').text('Actualizar').prop('disabled', false);
        $('#delete-btn').prop('disabled', false);
        
        // Show modal
        $('#unifiedEventModal').modal('show');
    };
    
    // Function to open modal in birthday edit mode
    window.openUnifiedModalForBirthdayEdit = function(birthdayData) {
        window.unifiedModalMode = 'edit';
        
        // Clear all form fields first
        $('#event_id').val('');
        $('#evento').val('');
        $('#hora_inicio').val('');
        $('#descripcion').val('');
        $('#fecha_inicio').val('');
        $('#fecha_fin').val('');
        $('#birthday_name').val('');
        $('#birthday_date').val('');
        
        // Set to birthday mode
        $('input[name="event_type"][value="birthday"]').prop('checked', true).trigger('change');
        
        // Populate birthday fields
        $('#event_id').val(birthdayData.id); // Use the same ID field for birthday ID
        $('#birthday_name').val(birthdayData.name);
        
        // Set the birthday date (format: YYYY-MM-DD for date input)
        var birthdayDateFormatted = birthdayData.date || 
            new Date().getFullYear() + '-' + 
            String(birthdayData.month).padStart(2, '0') + '-' + 
            String(birthdayData.day).padStart(2, '0');
        $('#birthday_date').val(birthdayDateFormatted);
        
        // Set birthday color if available
        if (birthdayData.color) {
            $('input[name="birthday_color"][value="' + birthdayData.color + '"]').prop('checked', true);
        } else {
            // Default to first birthday color
            $('input[name="birthday_color"]:first').prop('checked', true);
        }
        
        // Update button states for edit mode
        $('#save-btn').text('Actualizar').prop('disabled', false);
        $('#delete-btn').prop('disabled', false);
        
        // Show modal
        $('#unifiedEventModal').modal('show');
    };
    
    // Toggle between event and birthday fields
    $('input[name="event_type"]').change(function() {
        if ($(this).val() === 'birthday') {
            $('#event-fields').hide();
            $('#birthday-fields').show();
            $('#color-selection-group').hide(); // Hide event color selection
            
            // Clear event field requirements
            $('#evento').removeAttr('required');
            $('#fecha_inicio').removeAttr('required');
            $('#fecha_fin').removeAttr('required');
            
            // Add birthday field requirements
            $('#birthday_name').attr('required', true);
            $('#birthday_date').attr('required', true);
        } else {
            $('#event-fields').show();
            $('#birthday-fields').hide();
            $('#color-selection-group').show(); // Show event color selection
            
            // Add event field requirements
            $('#evento').attr('required', true);
            $('#fecha_inicio').attr('required', true);
            $('#fecha_fin').attr('required', true);
            
            // Clear birthday field requirements
            $('#birthday_name').removeAttr('required');
            $('#birthday_date').removeAttr('required');
        }
    });
    
    // Toggle recurring event options
    $('#es_recurrente').change(function() {
        if ($(this).is(':checked')) {
            $('#recurrence-options').slideDown(300);
            $('#fecha_fin').prop('disabled', true).closest('.form-group').hide();
            
            // For recurring events, set fecha_fin to same as fecha_inicio
            var startDate = $('#fecha_inicio').val();
            if (startDate) {
                // Set fecha_fin to the day after fecha_inicio (required by backend)
                var nextDay = new Date(startDate);
                nextDay.setDate(nextDay.getDate() + 1);
                $('#fecha_fin').val(nextDay.toISOString().split('T')[0]);
                
                // Set default recurrence end date to 3 months from start date
                var endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + 3);
                $('#fecha_fin_recurrencia').val(endDate.toISOString().split('T')[0]);
            }
        } else {
            $('#recurrence-options').slideUp(300);
            $('#fecha_fin').prop('disabled', false).closest('.form-group').show();
            
            // Reset fecha_fin to same as fecha_inicio for normal events
            var startDate = $('#fecha_inicio').val();
            if (startDate) {
                $('#fecha_fin').val(startDate);
            }
            
            // Clear recurring options
            $('input[name="dias[]"]').prop('checked', false);
            $('#fecha_fin_recurrencia').val('');
        }
    });
    
    // Handle form submission
    $('#formUnifiedEvent').submit(function(e) {
        e.preventDefault();
        
        var eventType = $('input[name="event_type"]:checked').val();
        
        // Validación manual para cumpleaños
        if (eventType === 'birthday') {
            var birthdayName = $('#birthday_name').val().trim();
            var birthdayDate = $('#birthday_date').val();
            
            if (!birthdayName) {
                alert('Por favor, ingresa el nombre de la persona');
                $('#birthday_name').focus();
                return false;
            }
            
            if (!birthdayDate) {
                alert('Por favor, selecciona la fecha de cumpleaños');
                $('#birthday_date').focus();
                return false;
            }
        }
        
        var formData = $(this).serialize();
        
        var targetUrl;
        if (eventType === 'birthday') {
            targetUrl = 'PHP/processBirthday.php';
        } else {
            if (window.unifiedModalMode === 'edit') {
                targetUrl = 'PHP/UpdateEvento.php';
            } else {
                targetUrl = 'PHP/nuevoEvento.php';
            }
        }
        
        $.ajax({
            url: targetUrl,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#unifiedEventModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 500);
            },
            error: function(xhr, status, error) {
                var errorMessage = 'Error al procesar la solicitud: ' + error;
                
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        errorMessage = 'Error: ' + response.error;
                        if (response.details && Object.keys(response.details).length > 0) {
                            errorMessage += '\nDetalles:\n';
                            for (var field in response.details) {
                                errorMessage += '- ' + field + ': ' + response.details[field] + '\n';
                            }
                        }
                    }
                } catch (e) {
                    if (xhr.responseText) {
                        errorMessage += '\nRespuesta del servidor: ' + xhr.responseText.substring(0, 500);
                    }
                }
                
                alert(errorMessage);
            }
        });
    });
    
    // Handle delete button
    $('#delete-btn').click(function() {
        var eventType = $('input[name="event_type"]:checked').val();
        var confirmMessage = eventType === 'birthday' ? 
            '¿Está seguro de que desea eliminar este cumpleaños?' : 
            '¿Está seguro de que desea eliminar este evento?';
            
        if (confirm(confirmMessage)) {
            var eventId = $('#event_id').val();
            
            if (!eventId) {
                alert('No se puede eliminar: ID no encontrado');
                return;
            }
            
            // Determine delete URL based on event type
            var deleteUrl = eventType === 'birthday' ? 
                'PHP/deleteBirthday.php' : 
                'PHP/deleteEvento.php';
            
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: { id: eventId },
                success: function(response) {
                    $('#unifiedEventModal').modal('hide');
                    location.reload(); // Refresh page to show changes
                },
                error: function(xhr, status, error) {
                    alert('Error al eliminar: ' + error);
                }
            });
        }
    });
    
    // Manejar cierre manual del modal (fallback para móvil)
    $('.modal .close, .modal [data-dismiss="modal"]').click(function() {
        try {
            $('#unifiedEventModal').modal('hide');
        } catch (error) {
            // Fallback manual
            $('#unifiedEventModal').removeClass('show').css('display', 'none');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }
    });
    
    // Cerrar modal al hacer click en el backdrop
    $('#unifiedEventModal').click(function(e) {
        if (e.target === this) {
            try {
                $(this).modal('hide');
            } catch (error) {
                // Fallback manual
                $(this).removeClass('show').css('display', 'none');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }
        }
    });
    
    return true;
};
</script>

<style>
/* Estilos para el modal unificado */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9999;
  width: 100%;
  height: 100%;
  overflow: hidden;
  outline: 0;
  display: none;
}

.modal.show {
  display: flex !important;
  align-items: center;
  justify-content: center;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9998;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
  position: relative;
  width: auto;
  max-width: 500px; /* Reduced from 600px */
  min-width: 320px;
  margin: 1.75rem auto;
  pointer-events: none;
}

.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 100%;
  max-height: 95vh;
  pointer-events: auto;
  background-color: #fff;
  background-clip: padding-box;
  border: none;
  border-radius: 12px;
  outline: 0;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.modal-body {
  padding: 15px 20px;
  overflow-y: auto;
}

.modal-header {
    padding: 12px 20px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    color: white;
}

.modal-header .modal-title {
    font-weight: 700;
    font-size: 1.15rem;
    color: white !important;
    width: 100%;
    margin: 0;
}

.modal-header .close {
    padding: 1rem;
    margin: -1rem -1rem -1rem auto;
    color: white !important;
    opacity: 0.9;
    text-shadow: none;
}

.modal-header .close:hover {
    opacity: 1;
}

/* Paleta de colores mejorada - Una sola fila horizontal */
.color-palette {
    display: flex;
    flex-wrap: nowrap; /* Force single row */
    overflow-x: auto; /* Allow scroll if very narrow */
    gap: 12px; /* Increased gap slightly */
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    justify-content: center; /* Center colors when there is space */
    scrollbar-width: none; /* Hide scrollbar for cleaner look */
}

.color-palette::-webkit-scrollbar {
    display: none;
}

.color-palette input[type="radio"] {
    display: none !important; /* CRITICAL: Hide the actual radio circle */
}

.color-option {
    flex: 0 0 28px; /* Fixed size */
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #fff;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-block;
    position: relative;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin: 0;
}

.color-option:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

input[type="radio"]:checked + .color-option {
    transform: scale(1.25);
    box-shadow: 0 0 0 2px #333, 0 4px 10px rgba(0,0,0,0.2);
}

/* Responsive design para el modal */
@media (max-width: 768px) {
    .modal {
        align-items: flex-start;
        padding-top: 10px;
    }
    
    .modal-dialog {
        margin: 10px !important;
        max-width: calc(100% - 20px) !important;
        width: calc(100% - 20px) !important;
        min-width: auto !important;
    }
    
    .modal-content {
        max-height: calc(100vh - 40px) !important;
    }
    
    .modal-body {
        padding: 10px 15px !important;
        max-height: calc(100vh - 160px) !important;
    }
    
    .color-palette {
        grid-template-columns: repeat(6, 1fr);
        gap: 8px;
        padding: 8px;
        max-width: 100%;
    }
    
    #birthday-color-palette {
        grid-template-columns: repeat(6, 1fr);
        gap: 8px;
        padding: 8px;
        max-width: 100%;
    }
    
    .color-option {
        width: 26px;
        height: 26px;
    }
    
    .modal-header .modal-title {
        font-size: 1rem;
    }
    
    .form-group {
        margin-bottom: 8px;
    }
    
    #unifiedEventModal .col-sm-10, 
    #unifiedEventModal .col-sm-12 {
        width: 100% !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
    
    .form-control {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box;
        padding: 8px 12px;
        height: auto;
    }
    
    .modal-footer {
        flex-direction: row !important;
        justify-content: center !important;
        gap: 6px;
        padding: 10px !important;
    }
    
    .modal-footer .btn {
        flex: 1;
        margin: 0 !important;
        padding: 8px 4px !important;
        font-size: 12px !important;
        min-height: 40px;
    }
}
}

@media (max-width: 480px) {
    .color-palette {
        grid-template-columns: repeat(5, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: 8px;
        padding: 10px;
        max-width: 220px;
    }
    
    #birthday-color-palette {
        grid-template-columns: repeat(5, 1fr);
        grid-template-rows: 1fr;
        gap: 8px;
        padding: 10px;
        max-width: 220px;
    }
    
    .color-option {
        width: 28px;
        height: 28px;
    }
}

/* Mejoras para los campos del formulario */
.form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    width: 100%;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Estilos para los radio buttons de tipo de evento */
#event-type-selector {
    display: flex;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 4px;
    margin: 10px 0 15px 0;
    justify-content: center;
}

#event-type-selector input[type="radio"] {
    display: none;
}

#event-type-selector label {
    flex: 1;
    text-align: center;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 13px;
    margin: 0 4px;
}

#event-type-selector input[type="radio"]:checked + label {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

#event-type-selector label:hover {
    background: rgba(102, 126, 234, 0.1);
}

/* Animaciones suaves */
#event-fields, #birthday-fields {
    transition: all 0.3s ease;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 12px 12px;
    display: flex !important;
    justify-content: center !important;
    gap: 10px !important;
    padding: 15px !important;
    flex-wrap: wrap;
    background: #f8f9fa;
}

.modal-footer .btn {
    flex: 1;
    max-width: 120px;
    min-width: 90px;
    margin: 0 !important;
    padding: 8px 12px;
    font-size: 14px;
}
/* Estilos para eventos recurrentes */
.recurring-checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: background 0.2s;
}

.recurring-checkbox:hover {
    background: #e9ecef;
}

.recurring-checkbox input[type="checkbox"] {
    margin-right: 8px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.recurring-checkbox span {
    font-weight: 500;
    color: #495057;
}

.days-selector {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.day-option {
    flex: 0 0 auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 2px solid #dee2e6;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 600;
    font-size: 14px;
    background: white;
    margin: 0;
}

.day-option:hover {
    border-color: #667eea;
    background: #f0f3ff;
}

.day-option input[type="checkbox"] {
    display: none;
}

.day-option input[type="checkbox"]:checked + * {
    /* This won't work, need different approach */
}

/* When checkbox is checked, style the label */
.day-option:has(input:checked) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
    transform: scale(1.1);
}

#recurrence-options {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
    border: 1px solid #dee2e6;
}

.form-text.text-muted {
    font-size: 12px;
    color: #6c757d;
    margin-top: 5px;
}

</style>