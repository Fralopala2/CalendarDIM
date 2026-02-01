<?php
// PHP/modalUnifiedEvent.php - Rediseñado con pestañas y mejoras de usabilidad
?>

<div class="modal fade" id="unifiedEventModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content premium-modal">
            <div class="modal-header">
                <h5 class="modal-title">Gestión de Calendario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-tabs-wrapper">
                <ul class="nav nav-pills nav-justified" id="eventTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-event" data-toggle="pill" href="#pane-event" role="tab">
                            <span class="tab-icon">📅</span> Evento
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-recurring" data-toggle="pill" href="#pane-recurring" role="tab">
                            <span class="tab-icon">🔄</span> Recurrente
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-birthday" data-toggle="pill" href="#pane-birthday" role="tab">
                            <span class="tab-icon">🎂</span> Cumple
                        </a>
                    </li>
                </ul>
            </div>

            <form name="formUnifiedEvent" id="formUnifiedEvent" method="POST">
                <div class="modal-body custom-scrollbar">
                    <!-- Campos ocultos para el backend -->
                    <input type="hidden" name="event_id" id="event_id" value="">
                    <input type="hidden" name="event_type" id="input_event_type" value="event">
                    <input type="hidden" name="es_recurrente" id="input_es_recurrente" value="0">
                    
                    <div class="tab-content" id="eventTabsContent">
                        
                        <!-- PANEL 1: EVENTO NORMAL -->
                        <div class="tab-pane fade show active" id="pane-event" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group floating-label">
                                        <label for="evento">Nombre del Evento</label>
                                        <input type="text" class="form-control premium-input" name="evento" id="evento" placeholder="¿Qué vamos a hacer?" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <label for="fecha_inicio">Fecha Inicio</label>
                                        <input type="date" class="form-control premium-input" name="fecha_inicio" id="fecha_inicio" required>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="hora_inicio">Hora</label>
                                        <input type="time" class="form-control premium-input" name="hora_inicio" id="hora_inicio">
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="end-date-row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="fecha_fin">Fecha Final</label>
                                        <input type="date" class="form-control premium-input" name="fecha_fin" id="fecha_fin" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control premium-input" name="descripcion" id="descripcion" rows="2" placeholder="Detalles adicionales..."></textarea>
                            </div>

                            <div class="form-group">
                                <label>Color del Evento</label>
                                <div class="premium-color-palette" id="event-color-palette">
                                    <input type="radio" name="color_evento" id="color_1" value="#FF5722" checked>
                                    <label for="color_1" class="color-circle" style="background-color: #FF5722;"></label>
                                    
                                    <input type="radio" name="color_evento" id="color_2" value="#FFC107">
                                    <label for="color_2" class="color-circle" style="background-color: #FFC107;"></label>
                                    
                                    <input type="radio" name="color_evento" id="color_3" value="#8BC34A">
                                    <label for="color_3" class="color-circle" style="background-color: #8BC34A;"></label>
                                    
                                    <input type="radio" name="color_evento" id="color_4" value="#009688">
                                    <label for="color_4" class="color-circle" style="background-color: #009688;"></label>
                                    
                                    <input type="radio" name="color_evento" id="color_5" value="#2196F3">
                                    <label for="color_5" class="color-circle" style="background-color: #2196F3;"></label>
                                    
                                    <input type="radio" name="color_evento" id="color_6" value="#9c27b0">
                                    <label for="color_6" class="color-circle" style="background-color: #9c27b0;"></label>
 
                                    <input type="radio" name="color_evento" id="color_7" value="#E91E63">
                                    <label for="color_7" class="color-circle" style="background-color: #E91E63;"></label>
 
                                    <input type="radio" name="color_evento" id="color_8" value="#3F51B5">
                                    <label for="color_8" class="color-circle" style="background-color: #3F51B5;"></label>
 
                                    <input type="radio" name="color_evento" id="color_9" value="#795548">
                                    <label for="color_9" class="color-circle" style="background-color: #795548;"></label>
 
                                    <input type="radio" name="color_evento" id="color_10" value="#607D8B">
                                    <label for="color_10" class="color-circle" style="background-color: #607D8B;"></label>
 
                                    <input type="radio" name="color_evento" id="color_11" value="#000000">
                                    <label for="color_11" class="color-circle" style="background-color: #000000;"></label>
                                </div>
                            </div>
                        </div>

                        <!-- PANEL 2: EVENTO RECURRENTE -->
                        <div class="tab-pane fade" id="pane-recurring" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="evento_rec">Nombre del Evento Recurrente</label>
                                        <input type="text" class="form-control premium-input sync-title" id="evento_rec" placeholder="Evento que se repite">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <label for="fecha_inicio_rec">Fecha de Primer Día</label>
                                        <input type="date" class="form-control premium-input sync-start" id="fecha_inicio_rec">
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="hora_inicio_rec">Hora</label>
                                        <input type="time" class="form-control premium-input sync-time" id="hora_inicio_rec">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="d-block mb-2 text-muted small uppercase">Repetir estos días:</label>
                                <div class="days-selector-premium">
                                    <label class="day-pill"><input type="checkbox" name="dias[]" value="1"><span>L</span></label>
                                    <label class="day-pill"><input type="checkbox" name="dias[]" value="2"><span>M</span></label>
                                    <label class="day-pill"><input type="checkbox" name="dias[]" value="3"><span>X</span></label>
                                    <label class="day-pill"><input type="checkbox" name="dias[]" value="4"><span>J</span></label>
                                    <label class="day-pill"><input type="checkbox" name="dias[]" value="5"><span>V</span></label>
                                    <label class="day-pill"><input type="checkbox" name="dias[]" value="6" class="weekend"><span>S</span></label>
                                    <label class="day-pill"><input type="checkbox" name="dias[]" value="0" class="weekend"><span>D</span></label>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="fecha_fin_recurrencia">Repetir hasta:</label>
                                <input type="date" class="form-control premium-input" name="fecha_fin_recurrencia" id="fecha_fin_recurrencia">
                                <small class="text-info"><i class="tab-icon">ℹ️</i> Se crearán eventos hasta esta fecha.</small>
                            </div>
                        </div>

                        <!-- PANEL 3: CUMPLEAÑOS -->
                        <div class="tab-pane fade" id="pane-birthday" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="birthday_name">Nombre del cumpleañero/a</label>
                                        <input type="text" class="form-control premium-input" name="birthday_name" id="birthday_name" placeholder="¿Quién cumple años?">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="birthday_date">Fecha</label>
                                        <input type="date" class="form-control premium-input" name="birthday_date" id="birthday_date">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Color para el cumpleaños</label>
                                <div class="premium-color-palette" id="birthday-color-palette">
                                    <input type="radio" name="birthday_color" id="bcolor_1" value="#FF69B4" checked>
                                    <label for="bcolor_1" class="color-circle" style="background-color: #FF69B4;"></label>
                                    
                                    <input type="radio" name="birthday_color" id="bcolor_2" value="#9C27B0">
                                    <label for="bcolor_2" class="color-circle" style="background-color: #9C27B0;"></label>
                                    
                                    <input type="radio" name="birthday_color" id="bcolor_3" value="#E91E63">
                                    <label for="bcolor_3" class="color-circle" style="background-color: #E91E63;"></label>
                                    
                                    <input type="radio" name="birthday_color" id="bcolor_4" value="#673AB7">
                                    <label for="bcolor_4" class="color-circle" style="background-color: #673AB7;"></label>
                                    
                                    <input type="radio" name="birthday_color" id="bcolor_5" value="#F48FB1">
                                    <label for="bcolor_5" class="color-circle" style="background-color: #F48FB1;"></label>
 
                                    <input type="radio" name="birthday_color" id="bcolor_6" value="#00BCD4">
                                    <label for="bcolor_6" class="color-circle" style="background-color: #00BCD4;"></label>
 
                                    <input type="radio" name="birthday_color" id="bcolor_7" value="#4CAF50">
                                    <label for="bcolor_7" class="color-circle" style="background-color: #4CAF50;"></label>
 
                                    <input type="radio" name="birthday_color" id="bcolor_8" value="#FF9800">
                                    <label for="bcolor_8" class="color-circle" style="background-color: #FF9800;"></label>
 
                                    <input type="radio" name="birthday_color" id="bcolor_9" value="#03A9F4">
                                    <label for="bcolor_9" class="color-circle" style="background-color: #03A9F4;"></label>
 
                                    <input type="radio" name="birthday_color" id="bcolor_10" value="#CDDC39">
                                    <label for="bcolor_10" class="color-circle" style="background-color: #CDDC39;"></label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="modal-footer premium-footer">
                    <button type="submit" id="save-btn" class="btn btn-save">
                        <span>Guardar</span>
                    </button>
                    <button type="button" id="delete-btn" class="btn btn-delete" disabled>
                        <span>Eliminar</span>
                    </button>
                    <button type="button" class="btn btn-exit" data-dismiss="modal">
                        <span>Salir</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal Logic
window.initializeUnifiedModal = function() {
    if ($('#unifiedEventModal').length === 0) return false;
    
    // Sync common fields between Event and Recurring tabs
    $('#evento').on('input', function() { $('#evento_rec').val($(this).val()); });
    $('#evento_rec').on('input', function() { $('#evento').val($(this).val()); });
    
    $('#fecha_inicio').on('change', function() { 
        $('#fecha_inicio_rec').val($(this).val()); 
        if ($('#fecha_fin').val() < $(this).val()) {
            $('#fecha_fin').val($(this).val());
        }
    });
    $('#fecha_inicio_rec').on('change', function() { $('#fecha_inicio').val($(this).val()); });
    
    $('#hora_inicio').on('change', function() { $('#hora_inicio_rec').val($(this).val()); });
    $('#hora_inicio_rec').on('change', function() { $('#hora_inicio').val($(this).val()); });

    // Handle Tab Changes
    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        var targetId = $(e.target).attr('id');
        
        if (targetId === 'tab-event') {
            $('#input_event_type').val('event');
            $('#input_es_recurrente').val('0');
            $('#evento').attr('required', true);
            $('#fecha_inicio').attr('required', true);
            $('#fecha_fin').attr('required', true);
            $('#birthday_name').removeAttr('required');
            $('#birthday_date').removeAttr('required');
        } else if (targetId === 'tab-recurring') {
            $('#input_event_type').val('event');
            $('#input_es_recurrente').val('1');
            $('#evento').attr('required', true);
            $('#fecha_inicio').attr('required', true);
            $('#fecha_fin').removeAttr('required');
            $('#birthday_name').removeAttr('required');
            $('#birthday_date').removeAttr('required');
            
            // Si la fecha fin estaba puesta, la movemos a fin recurrencia si esta vacio
            if ($('#fecha_fin').val() && !$('#fecha_fin_recurrencia').val()) {
                $('#fecha_fin_recurrencia').val($('#fecha_fin').val());
            }
        } else if (targetId === 'tab-birthday') {
            $('#input_event_type').val('birthday');
            $('#input_es_recurrente').val('0');
            $('#evento').removeAttr('required');
            $('#fecha_inicio').removeAttr('required');
            $('#fecha_fin').removeAttr('required');
            $('#birthday_name').attr('required', true);
            $('#birthday_date').attr('required', true);
        }
    });

    window.openUnifiedModalForCreate = function(defaultDate) {
        window.unifiedModalMode = 'create';
        $('#formUnifiedEvent')[0].reset();
        $('#event_id').val('');
        
        var dateToUse = defaultDate || new Date().toISOString().split('T')[0];
        $('#fecha_inicio, #fecha_inicio_rec, #fecha_fin, #birthday_date').val(dateToUse);
        
        // Activar pestaña evento por defecto
        $('#tab-event').tab('show');
        
        $('#save-btn').html('<span>Guardar cambios</span>');
        $('#delete-btn').prop('disabled', true).hide();
        $('#unifiedEventModal').modal('show');
    };

    window.openUnifiedModalForEdit = function(eventData) {
        window.unifiedModalMode = 'edit';
        $('#formUnifiedEvent')[0].reset();
        $('#event_id').val(eventData.id);
        $('#evento, #evento_rec').val(eventData.title);
        $('#hora_inicio, #hora_inicio_rec').val(eventData.time || '');
        $('#descripcion').val(eventData.description || '');
        
        if (eventData.start_date) {
            var parts = eventData.start_date.split('-');
            var isoDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#fecha_inicio, #fecha_inicio_rec').val(isoDate);
        }
        if (eventData.end_date) {
            var parts = eventData.end_date.split('-');
            var isoDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#fecha_fin').val(isoDate);
        }
        
        if (eventData.color) {
            $('input[name="color_evento"][value="' + eventData.color + '"]').prop('checked', true);
        }
        
        $('#tab-event').tab('show');
        $('#save-btn').html('<span>Actualizar</span>');
        $('#delete-btn').prop('disabled', false).show();
        $('#unifiedEventModal').modal('show');
    };

    window.openUnifiedModalForBirthdayEdit = function(birthdayData) {
        window.unifiedModalMode = 'edit';
        $('#formUnifiedEvent')[0].reset();
        $('#event_id').val(birthdayData.id);
        $('#birthday_name').val(birthdayData.name);
        
        var dateStr = birthdayData.date || (new Date().getFullYear() + '-' + String(birthdayData.month).padStart(2, '0') + '-' + String(birthdayData.day).padStart(2, '0'));
        $('#birthday_date').val(dateStr);
        
        if (birthdayData.color) {
            $('input[name="birthday_color"][value="' + birthdayData.color + '"]').prop('checked', true);
        }
        
        $('#tab-birthday').tab('show');
        $('#save-btn').html('<span>Actualizar</span>');
        $('#delete-btn').prop('disabled', false).show();
        $('#unifiedEventModal').modal('show');
    };

    $('#formUnifiedEvent').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var eventType = $('#input_event_type').val();
        var targetUrl = (eventType === 'birthday') ? 'PHP/processBirthday.php' : (window.unifiedModalMode === 'edit' ? 'PHP/UpdateEvento.php' : 'PHP/nuevoEvento.php');

        $.ajax({
            url: targetUrl,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#unifiedEventModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.error : 'Ocurrió un error inesperado'));
            }
        });
    });

    $('#delete-btn').click(function() {
        if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
            var eventType = $('#input_event_type').val();
            var deleteUrl = (eventType === 'birthday') ? 'PHP/deleteBirthday.php' : 'PHP/deleteEvento.php';
            $.ajax({
                url: deleteUrl,
                method: 'POST',
                data: { id: $('#event_id').val() },
                success: function() {
                    $('#unifiedEventModal').modal('hide');
                    location.reload();
                }
            });
        }
    });

    return true;
};
</script>

<style>
/* PREMIUM MODAL STYLES */
.premium-modal {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    overflow: hidden;
    background: #ffffff;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

/* El formulario debe permitir que sus hijos se comporten como flex */
#formUnifiedEvent {
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
    min-height: 0;
}

.premium-modal .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 25px;
    flex-shrink: 0;
}

.premium-modal .modal-title {
    font-weight: 700;
    letter-spacing: 0.5px;
    font-size: 1.15rem;
    color: white !important;
}

.premium-modal .close {
    color: white;
    opacity: 0.8;
    text-shadow: none;
    outline: none;
}

/* TABS STYLING */
.modal-tabs-wrapper {
    padding: 8px 20px 0;
    background: #f8faff;
    border-bottom: 1px solid #edf2f7;
    flex-shrink: 0;
}

.nav-pills .nav-link {
    border-radius: 12px;
    padding: 8px 5px;
    color: #718096;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.nav-pills .nav-link.active {
    background: white !important;
    color: #667eea !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    border-color: #667eea22;
}

/* FORM ELEMENTS */
.modal-body {
    padding: 15px 25px;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
}

.premium-input {
    border-radius: 10px;
    border: 2px solid #edf2f7;
    padding: 10px 15px;
    height: auto;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

/* COLOR PALETTE */
.premium-color-palette {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    flex-wrap: wrap;
}

.premium-color-palette input {
    display: none !important;
}

.color-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
    display: inline-block;
}

.premium-color-palette input:checked + .color-circle {
    transform: scale(1.2);
    box-shadow: 0 0 0 2px #667eea;
}

/* DAYS SELECTOR */
.days-selector-premium {
    display: flex;
    justify-content: space-between;
    gap: 5px;
}

.day-pill {
    flex: 1;
    cursor: pointer;
}

.day-pill input {
    display: none !important;
}

.day-pill span {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 36px;
    background: #edf2f7;
    border-radius: 10px;
    font-weight: 700;
    color: #718096;
    transition: all 0.2s ease;
}

.day-pill input:checked + span {
    background: #667eea;
    color: white;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.day-pill .weekend + span {
    color: #e53e3e;
}

.day-pill input.weekend:checked + span {
    background: #e53e3e;
}

/* FOOTER */
.premium-footer {
    border: none;
    padding: 15px 25px 20px;
    background: white;
    gap: 10px;
    flex-shrink: 0;
    display: flex;
}

.btn {
    border-radius: 12px;
    padding: 10px 15px;
    font-weight: 700;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.9rem;
}

.btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    flex: 2;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
}

.btn-delete {
    background: #fff5f5;
    color: #e53e3e !important;
    flex: 1;
}

.btn-delete:hover {
    background: #feb2b2;
    color: #c53030;
}

.btn-exit {
    background: #f7fafc;
    color: #718096 !important;
    flex: 1;
}

/* Responsive adjustments */
@media (max-height: 700px), (max-width: 500px) {
    .premium-modal {
        max-height: 95vh;
    }
    .modal-dialog {
        margin: 10px auto;
    }
    .days-selector-premium { flex-wrap: wrap; }
    .day-pill { flex: 0 0 23%; }
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}
</style>