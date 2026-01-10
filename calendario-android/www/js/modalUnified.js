// Modal functions - will be called from index.html
window.initializeUnifiedModal = function() {
    
    // Global variable to track modal mode
    window.unifiedModalMode = 'create';
    
    // Function to open modal in create mode
    window.openUnifiedModalForCreate = function() {
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
        
        // Show modal
        $('#unifiedEventModal').modal({
            backdrop: 'static',
            keyboard: true,
            focus: true,
            show: true
        });
    };
    
    // Function to open modal in edit mode
    window.openUnifiedModalForEdit = function(eventData) {
        window.unifiedModalMode = 'edit';
        
        // Populate form fields with event data
        $('#event_id').val(eventData.id);
        $('#evento').val(eventData.evento || eventData.title);
        $('#hora_inicio').val(eventData.hora_inicio || '');
        $('#descripcion').val(eventData.descripcion || '');
        
        // Set dates
        $('#fecha_inicio').val(eventData.fecha_inicio || eventData.start);
        $('#fecha_fin').val(eventData.fecha_fin || eventData.end);
        
        // Set to event mode
        $('input[name="event_type"][value="event"]').prop('checked', true).trigger('change');
        
        // Set color selection if available
        if (eventData.color_evento || eventData.color) {
            $('input[name="color_evento"][value="' + (eventData.color_evento || eventData.color) + '"]').prop('checked', true);
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
        $('#event_id').val(birthdayData.birthday_id || birthdayData.id);
        $('#birthday_name').val(birthdayData.nombre || birthdayData.name);
        
        // Set the birthday date
        var birthdayDateFormatted = birthdayData.date || 
            new Date().getFullYear() + '-' + 
            String(birthdayData.mes_nacimiento || birthdayData.month).padStart(2, '0') + '-' + 
            String(birthdayData.dia_nacimiento || birthdayData.day).padStart(2, '0');
        $('#birthday_date').val(birthdayDateFormatted);
        
        // Set birthday color if available
        if (birthdayData.color) {
            $('input[name="birthday_color"][value="' + birthdayData.color + '"]').prop('checked', true);
        } else {
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
            $('#color-selection-group').hide();
            
            $('#evento').removeAttr('required');
            $('#fecha_inicio').removeAttr('required');
            $('#fecha_fin').removeAttr('required');
            
            $('#birthday_name').attr('required', true);
            $('#birthday_date').attr('required', true);
        } else {
            $('#event-fields').show();
            $('#birthday-fields').hide();
            $('#color-selection-group').show();
            
            $('#evento').attr('required', true);
            $('#fecha_inicio').attr('required', true);
            $('#fecha_fin').attr('required', true);
            
            $('#birthday_name').removeAttr('required');
            $('#birthday_date').removeAttr('required');
        }
    });
    
    // Handle form submission
    $('#formUnifiedEvent').submit(function(e) {
        e.preventDefault();
        
        var eventType = $('input[name="event_type"]:checked').val();
        
        if (eventType === 'birthday') {
            var birthdayName = $('#birthday_name').val().trim();
            var birthdayDate = $('#birthday_date').val();
            var birthdayColor = $('input[name="birthday_color"]:checked').val();
            
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
            
            var birthdayDateObj = new Date(birthdayDate);
            var birthdayData = {
                nombre: birthdayName,
                dia_nacimiento: birthdayDateObj.getDate(),
                mes_nacimiento: birthdayDateObj.getMonth() + 1,
                color_cumpleanos: birthdayColor
            };
            
            if (window.unifiedModalMode === 'edit') {
                birthdayData.id = $('#event_id').val();
            }
            
            if (window.birthdayManager) {
                window.birthdayManager.saveBirthday(birthdayData)
                    .then(function(response) {
                        $('#unifiedEventModal').modal('hide');
                        window.refreshCalendar();
                    })
                    .catch(function(error) {
                        alert('Error al guardar cumpleaños: ' + (error.error || error));
                    });
            }
        } else {
            var eventData = {
                evento: $('#evento').val().trim(),
                fecha_inicio: $('#fecha_inicio').val(),
                fecha_fin: $('#fecha_fin').val(),
                color_evento: $('input[name="color_evento"]:checked').val(),
                hora_inicio: $('#hora_inicio').val() || null,
                descripcion: $('#descripcion').val().trim() || null
            };
            
            if (window.unifiedModalMode === 'edit') {
                eventData.id = $('#event_id').val();
            }
            
            if (window.eventManager) {
                window.eventManager.saveEvent(eventData)
                    .then(function(response) {
                        $('#unifiedEventModal').modal('hide');
                        window.refreshCalendar();
                    })
                    .catch(function(error) {
                        alert('Error al guardar evento: ' + (error.error || error));
                    });
            }
        }
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
            
            if (eventType === 'birthday' && window.birthdayManager) {
                window.birthdayManager.deleteBirthday(eventId)
                    .then(function(response) {
                        $('#unifiedEventModal').modal('hide');
                        window.refreshCalendar();
                    })
                    .catch(function(error) {
                        alert('Error al eliminar cumpleaños: ' + (error.error || error));
                    });
            } else if (window.eventManager) {
                window.eventManager.deleteEvent(eventId)
                    .then(function(response) {
                        $('#unifiedEventModal').modal('hide');
                        window.refreshCalendar();
                    })
                    .catch(function(error) {
                        alert('Error al eliminar evento: ' + (error.error || error));
                    });
            }
        }
    });
    
    // Handle modal close
    $('.modal .close, .modal [data-dismiss="modal"]').click(function() {
        $('#unifiedEventModal').modal('hide');
    });
    
    $('#unifiedEventModal').click(function(e) {
        if (e.target === this) {
            $(this).modal('hide');
        }
    });
    
    return true;
};