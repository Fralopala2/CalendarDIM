class UnifiedModal {
    constructor() {
        this.eventManager = null;
        this.birthdayManager = null;
        this.currentEventId = null;
        this.currentBirthdayId = null;
        this.isEditMode = false;
        this.selectedDate = null;
    }

    init(eventManager, birthdayManager) {
        this.eventManager = eventManager;
        this.birthdayManager = birthdayManager;
        this.createModalHTML();
        this.bindEvents();
    }

    createModalHTML() {
        const modalHTML = `
            <div class="modal" id="unifiedEventModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h5 class="modal-title">Gestión de eventos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        
                        <form id="formUnifiedEvent" class="form-horizontal">
                            <div class="modal-body">
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

                                <div id="event-fields">
                                    <div class="form-group">
                                        <label for="evento" class="col-sm-12 control-label">Nombre del Evento</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="evento" id="evento" 
                                                   placeholder="Nombre del Evento" required/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="hora_inicio" class="col-sm-12 control-label">Hora de Inicio</label>
                                        <div class="col-sm-12">
                                            <input type="time" class="form-control" name="hora_inicio" id="hora_inicio">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="descripcion" class="col-sm-12 control-label">Descripción</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" name="descripcion" id="descripcion" 
                                                      placeholder="Descripción del evento" rows="3"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="fecha_inicio" class="col-sm-12 control-label">Fecha Inicio</label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" 
                                                   placeholder="Fecha Inicio" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="fecha_fin" class="col-sm-12 control-label">Fecha Final</label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" 
                                                   placeholder="Fecha Final" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">Color del Evento</label>
                                        <div class="col-md-12">
                                            <div id="event-color-palette" class="color-palette">
                                                <input type="radio" name="color_evento" id="color_1" value="#007bff" checked>
                                                <label for="color_1" class="color-option" style="background-color: #007bff;"></label>
                                                
                                                <input type="radio" name="color_evento" id="color_2" value="#28a745">
                                                <label for="color_2" class="color-option" style="background-color: #28a745;"></label>
                                                
                                                <input type="radio" name="color_evento" id="color_3" value="#dc3545">
                                                <label for="color_3" class="color-option" style="background-color: #dc3545;"></label>
                                                
                                                <input type="radio" name="color_evento" id="color_4" value="#ffc107">
                                                <label for="color_4" class="color-option" style="background-color: #ffc107;"></label>
                                                
                                                <input type="radio" name="color_evento" id="color_5" value="#6f42c1">
                                                <label for="color_5" class="color-option" style="background-color: #6f42c1;"></label>
                                                
                                                <input type="radio" name="color_evento" id="color_6" value="#fd7e14">
                                                <label for="color_6" class="color-option" style="background-color: #fd7e14;"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="birthday-fields" style="display: none;">
                                    <div class="form-group">
                                        <label for="birthday_name" class="col-sm-12 control-label">Nombre de la Persona</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="birthday_name" id="birthday_name" 
                                                   placeholder="Nombre de la persona">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="birthday_day" class="col-sm-12 control-label">Día</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="birthday_day" id="birthday_day">
                                                <option value="">Seleccionar día</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="birthday_month" class="col-sm-12 control-label">Mes</label>
                                        <div class="col-sm-12">
                                            <select class="form-control" name="birthday_month" id="birthday_month">
                                                <option value="">Seleccionar mes</option>
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" id="deleteEventBtn" class="btn btn-danger" style="display: none;">Eliminar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalHTML);
        this.populateDayOptions();
    }

    populateDayOptions() {
        const daySelect = $('#birthday_day');
        for (let i = 1; i <= 31; i++) {
            daySelect.append(`<option value="${i}">${i}</option>`);
        }
    }

    bindEvents() {
        $('input[name="event_type"]').on('change', () => {
            this.toggleEventType();
        });

        $('#formUnifiedEvent').on('submit', (e) => {
            e.preventDefault();
            this.saveEvent();
        });

        $('#deleteEventBtn').on('click', () => {
            this.deleteEvent();
        });

        $('.close, [data-dismiss="modal"]').on('click', () => {
            this.closeModal();
        });
    }

    toggleEventType() {
        const eventType = $('input[name="event_type"]:checked').val();
        
        if (eventType === 'event') {
            $('#event-fields').show();
            $('#birthday-fields').hide();
        } else {
            $('#event-fields').hide();
            $('#birthday-fields').show();
        }
    }

    openModal(date = null, eventData = null) {
        this.selectedDate = date;
        this.resetForm();
        
        if (eventData) {
            this.isEditMode = true;
            this.populateForm(eventData);
            $('#deleteEventBtn').show();
        } else {
            this.isEditMode = false;
            $('#deleteEventBtn').hide();
            if (date) {
                $('#fecha_inicio').val(date);
                $('#fecha_fin').val(date);
            }
        }
        
        $('#unifiedEventModal').modal('show');
    }

    populateForm(eventData) {
        if (eventData.type === 'birthday') {
            $('input[name="event_type"][value="birthday"]').prop('checked', true);
            this.toggleEventType();
            
            $('#birthday_name').val(eventData.nombre);
            $('#birthday_day').val(eventData.dia_nacimiento);
            $('#birthday_month').val(eventData.mes_nacimiento);
            this.currentBirthdayId = eventData.birthday_id;
        } else {
            $('input[name="event_type"][value="event"]').prop('checked', true);
            this.toggleEventType();
            
            $('#evento').val(eventData.evento || eventData.title);
            $('#fecha_inicio').val(eventData.start);
            $('#fecha_fin').val(eventData.end);
            $('#hora_inicio').val(eventData.hora_inicio || '');
            $('#descripcion').val(eventData.descripcion || '');
            $(`input[name="color_evento"][value="${eventData.color}"]`).prop('checked', true);
            this.currentEventId = eventData.id;
        }
    }

    resetForm() {
        $('#formUnifiedEvent')[0].reset();
        $('input[name="event_type"][value="event"]').prop('checked', true);
        this.toggleEventType();
        this.currentEventId = null;
        this.currentBirthdayId = null;
        this.isEditMode = false;
    }

    saveEvent() {
        const eventType = $('input[name="event_type"]:checked').val();
        
        if (eventType === 'event') {
            this.saveRegularEvent();
        } else {
            this.saveBirthday();
        }
    }

    saveRegularEvent() {
        const eventData = {
            id: this.currentEventId,
            evento: $('#evento').val(),
            fecha_inicio: $('#fecha_inicio').val(),
            fecha_fin: $('#fecha_fin').val(),
            color_evento: $('input[name="color_evento"]:checked').val(),
            hora_inicio: $('#hora_inicio').val(),
            descripcion: $('#descripcion').val()
        };

        this.eventManager.saveEvent(eventData)
            .then((result) => {
                this.closeModal();
                this.refreshCalendar();
                this.showSuccess('Evento guardado correctamente');
            })
            .catch((error) => {
                this.showError('Error al guardar el evento: ' + error.error);
            });
    }

    saveBirthday() {
        const birthdayData = {
            id: this.currentBirthdayId,
            nombre: $('#birthday_name').val(),
            dia_nacimiento: $('#birthday_day').val(),
            mes_nacimiento: $('#birthday_month').val()
        };

        this.birthdayManager.saveBirthday(birthdayData)
            .then((result) => {
                this.closeModal();
                this.refreshCalendar();
                this.showSuccess('Cumpleaños guardado correctamente');
            })
            .catch((error) => {
                this.showError('Error al guardar el cumpleaños: ' + error.error);
            });
    }

    deleteEvent() {
        if (!confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
            return;
        }

        const eventType = $('input[name="event_type"]:checked').val();
        
        if (eventType === 'event' && this.currentEventId) {
            this.eventManager.deleteEvent(this.currentEventId)
                .then(() => {
                    this.closeModal();
                    this.refreshCalendar();
                    this.showSuccess('Evento eliminado correctamente');
                })
                .catch((error) => {
                    this.showError('Error al eliminar el evento: ' + error.error);
                });
        } else if (eventType === 'birthday' && this.currentBirthdayId) {
            this.birthdayManager.deleteBirthday(this.currentBirthdayId)
                .then(() => {
                    this.closeModal();
                    this.refreshCalendar();
                    this.showSuccess('Cumpleaños eliminado correctamente');
                })
                .catch((error) => {
                    this.showError('Error al eliminar el cumpleaños: ' + error.error);
                });
        }
    }

    closeModal() {
        $('#unifiedEventModal').modal('hide');
        this.resetForm();
    }

    refreshCalendar() {
        if (window.calendarInstance) {
            window.calendarInstance.refetchEvents();
        }
    }

    showSuccess(message) {
        alert(message);
    }

    showError(message) {
        alert(message);
    }
}

window.unifiedModal = new UnifiedModal();