<?php
/**
 * Unified Event Modal - Single modal for creating and editing events and birthdays
 * Requirements: 3.1, 3.5, 4.5, 4.6, 2.1
 */
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
            
            <form name="formUnifiedEvent" id="formUnifiedEvent" class="form-horizontal" method="POST">
                <!-- Hidden field for event ID (edit mode) -->
                <input type="hidden" name="event_id" id="event_id" value="">
                
                <!-- Event Type Selector -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <div id="event-type-selector">
                            <input type="radio" name="event_type" value="event" id="event_type_event" checked>
                            <label for="event_type_event">Evento</label>
                            <input type="radio" name="event_type" value="birthday" id="event_type_birthday">
                            <label for="event_type_birthday">Cumpleaños</label>
                        </div>
                    </div>
                </div>

                <!-- Event Fields -->
                <div id="event-fields">
                    <div class="form-group">
                        <label for="evento" class="col-sm-12 control-label">Nombre del Evento</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="evento" id="evento" 
                                   placeholder="Nombre del Evento" required/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="hora_inicio" class="col-sm-12 control-label">Hora de Inicio</label>
                        <div class="col-sm-10">
                            <input type="time" class="form-control" name="hora_inicio" id="hora_inicio">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion" class="col-sm-12 control-label">Descripción</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="descripcion" id="descripcion" 
                                      placeholder="Descripción del evento" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_inicio" class="col-sm-12 control-label">Fecha Inicio</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" 
                                   placeholder="Fecha Inicio" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_fin" class="col-sm-12 control-label">Fecha Final</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="fecha_fin" id="fecha_fin" 
                                   placeholder="Fecha Final" required>
                        </div>
                    </div>
                </div>

                <!-- Birthday Fields (hidden by default) -->
                <div id="birthday-fields" style="display: none;">
                    <div class="form-group">
                        <label for="birthday_name" class="col-sm-12 control-label">Nombre de la Persona</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="birthday_name" id="birthday_name" 
                                   placeholder="Nombre de la persona">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="birthday_date" class="col-sm-12 control-label">Fecha de Cumpleaños</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="birthday_date" id="birthday_date">
                        </div>
                    </div>
                </div>

                <!-- Enhanced Color Selection -->
                <div class="form-group" id="color-selection-group">
                    <label class="col-sm-12 control-label">Color del Evento</label>
                    <div class="col-md-12">
                        <div id="color-palette" class="color-palette">
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

                            <input type="radio" name="color_evento" id="color_11" value="#4CAF50">
                            <label for="color_11" class="color-option" style="background-color: #4CAF50;" title="Verde"></label>

                            <input type="radio" name="color_evento" id="color_12" value="#F44336">
                            <label for="color_12" class="color-option" style="background-color: #F44336;" title="Rojo"></label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" id="save-btn" class="btn btn-success">Guardar</button>
                    <button type="button" id="delete-btn" class="btn btn-danger" disabled>Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functions - will be called from index.php
window.initializeUnifiedModal = function() {
    // Global variable to track modal mode
    window.unifiedModalMode = 'create';
    
    // Function to open modal in create mode
    window.openUnifiedModalForCreate = function() {
        console.log('Opening modal for CREATE mode');
        
        // Check if modal exists
        if ($('#unifiedEventModal').length === 0) {
            console.error('ERROR: unifiedEventModal not found in DOM!');
            alert('Error: Modal no encontrado. Verifica que modalUnifiedEvent.php esté incluido.');
            return;
        }
        
        window.unifiedModalMode = 'create';
        
        // Clear all form fields
        $('#event_id').val('');
        $('#evento').val('');
        $('#hora_inicio').val('');
        $('#descripcion').val('');
        $('#fecha_inicio').val('');
        $('#fecha_fin').val('');
        $('#birthday_name').val('');
        $('#birthday_date').val('');
        
        // Set to event mode by default
        $('input[name="event_type"][value="event"]').prop('checked', true).trigger('change');
        
        // Reset color selection to first option
        $('input[name="color_evento"]:first').prop('checked', true);
        
        // Update button states for create mode
        $('#save-btn').text('Guardar').prop('disabled', false);
        $('#delete-btn').prop('disabled', true);
        
        console.log('About to show modal...');
        // Show modal
        $('#unifiedEventModal').modal('show');
    };
    
    // Function to open modal in edit mode
    window.openUnifiedModalForEdit = function(eventData) {
        console.log('Opening modal for EDIT mode', eventData);
        window.unifiedModalMode = 'edit';
        
        // Populate form fields with event data
        $('#event_id').val(eventData.id);
        $('#evento').val(eventData.title);
        $('#hora_inicio').val(eventData.time || '');
        $('#descripcion').val(eventData.description || '');
        $('#fecha_inicio').val(eventData.start_date);
        $('#fecha_fin').val(eventData.end_date);
        
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
        console.log('Opening modal for BIRTHDAY EDIT mode', birthdayData);
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
            $('#color-selection-group').hide(); // Hide color selection for birthdays
            
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
            $('#color-selection-group').show(); // Show color selection for events
            
            // Add event field requirements
            $('#evento').attr('required', true);
            $('#fecha_inicio').attr('required', true);
            $('#fecha_fin').attr('required', true);
            
            // Clear birthday field requirements
            $('#birthday_name').removeAttr('required');
            $('#birthday_date').removeAttr('required');
        }
    });
    
    // Handle form submission
    $('#formUnifiedEvent').submit(function(e) {
        e.preventDefault();
        
        var eventType = $('input[name="event_type"]:checked').val();
        var formData = $(this).serialize();
        
        // Determine the target PHP file based on event type and mode
        var targetUrl;
        if (eventType === 'birthday') {
            targetUrl = '../PHP/processBirthday.php';
        } else {
            if (window.unifiedModalMode === 'edit') {
                targetUrl = '../PHP/UpdateEvento.php';
            } else {
                targetUrl = '../PHP/nuevoEvento.php';
            }
        }
        
        console.log('Submitting to:', targetUrl);
        console.log('Form data:', formData);
        
        // Submit form via AJAX
        $.ajax({
            url: targetUrl,
            method: 'POST',
            data: formData,
            success: function(response) {
                console.log('Success response:', response);
                // Close modal and refresh calendar
                $('#unifiedEventModal').modal('hide');
                location.reload(); // Refresh page to show changes
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                alert('Error al procesar la solicitud: ' + error + '\nRespuesta: ' + xhr.responseText);
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
                '../PHP/deleteBirthday.php' : 
                '../PHP/deleteEvento.php';
            
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: { id: eventId },
                success: function(response) {
                    console.log('Delete success:', response);
                    $('#unifiedEventModal').modal('hide');
                    location.reload(); // Refresh page to show changes
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', xhr.responseText);
                    alert('Error al eliminar: ' + error);
                }
            });
        }
    });
};
</script>

<style>
/* Estilos para el modal unificado */
.modal-header .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
    width: 100%;
    text-align: center;
}

/* Paleta de colores mejorada */
.color-palette {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.color-palette input[type="radio"] {
    display: none;
}

.color-option {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.2s ease;
    display: inline-block;
    position: relative;
}

.color-option:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.color-palette input[type="radio"]:checked + .color-option {
    border-color: #333;
    transform: scale(1.15);
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #333;
}

/* Responsive design para el modal */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    .color-palette {
        gap: 6px;
    }
    
    .color-option {
        width: 28px;
        height: 28px;
    }
    
    .modal-header .modal-title {
        font-size: 1.1rem;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .col-sm-10 {
        padding-left: 15px;
        padding-right: 15px;
    }
}

@media (max-width: 480px) {
    .color-option {
        width: 24px;
        height: 24px;
    }
    
    .color-palette {
        gap: 4px;
        padding: 8px;
    }
    
    .modal-footer {
        flex-direction: column;
        gap: 10px;
    }
    
    .modal-footer .btn {
        width: 100%;
        margin: 0;
    }
}

/* Mejoras para los campos del formulario */
.form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Estilos para los radio buttons de tipo de evento */
#event-type-selector {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin: 10px 0;
}

#event-type-selector input[type="radio"] {
    display: none;
}

#event-type-selector label {
    cursor: pointer;
    padding: 8px 16px;
    border: 2px solid #dee2e6;
    border-radius: 20px;
    transition: all 0.2s ease;
    background-color: #fff;
    font-weight: 500;
}

#event-type-selector input[type="radio"]:checked + label {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

#event-type-selector label:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

/* Animaciones suaves */
#event-fields, #birthday-fields {
    transition: all 0.3s ease;
}

.modal-content {
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 10px 10px;
}
</style>