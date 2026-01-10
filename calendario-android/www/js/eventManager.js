class EventManager {
    constructor(database) {
        this.db = database;
    }

    saveEvent(data) {
        return new Promise((resolve, reject) => {
            const validationErrors = this.validateEventData(data);
            if (validationErrors.length > 0) {
                reject({
                    success: false,
                    error: 'Validation failed',
                    details: validationErrors
                });
                return;
            }

            const evento = this.capitalizeWords(data.evento.trim());
            const fechaInicio = this.parseDate(data.fecha_inicio);
            const fechaFin = this.calculateEndDate(data.fecha_fin);
            const colorEvento = data.color_evento;
            const horaInicio = data.hora_inicio || null;
            const descripcion = data.descripcion ? data.descripcion.trim() : null;

            if (data.id) {
                this.updateEvent(data.id, evento, fechaInicio, fechaFin, colorEvento, horaInicio, descripcion)
                    .then(resolve)
                    .catch(reject);
            } else {
                this.createEvent(evento, fechaInicio, fechaFin, colorEvento, horaInicio, descripcion)
                    .then(resolve)
                    .catch(reject);
            }
        });
    }

    createEvent(evento, fechaInicio, fechaFin, colorEvento, horaInicio, descripcion) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'INSERT INTO eventoscalendar (evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion) VALUES (?, ?, ?, ?, ?, ?)',
                    [evento, fechaInicio, fechaFin, colorEvento, horaInicio, descripcion],
                    (tx, result) => {
                        resolve({
                            success: true,
                            message: 'Event created successfully',
                            event_id: result.insertId
                        });
                    },
                    (tx, error) => {
                        reject({
                            success: false,
                            error: 'Failed to create event',
                            details: error
                        });
                    }
                );
            });
        });
    }

    updateEvent(id, evento, fechaInicio, fechaFin, colorEvento, horaInicio, descripcion) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'UPDATE eventoscalendar SET evento = ?, fecha_inicio = ?, fecha_fin = ?, color_evento = ?, hora_inicio = ?, descripcion = ? WHERE id = ?',
                    [evento, fechaInicio, fechaFin, colorEvento, horaInicio, descripcion, id],
                    (tx, result) => {
                        resolve({
                            success: true,
                            message: 'Event updated successfully',
                            event_id: id
                        });
                    },
                    (tx, error) => {
                        reject({
                            success: false,
                            error: 'Failed to update event',
                            details: error
                        });
                    }
                );
            });
        });
    }

    getAllEvents() {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion FROM eventoscalendar ORDER BY fecha_inicio ASC, hora_inicio ASC',
                    [],
                    (tx, result) => {
                        const events = [];
                        for (let i = 0; i < result.rows.length; i++) {
                            const row = result.rows.item(i);
                            let title = row.evento;
                            if (row.hora_inicio) {
                                title = row.hora_inicio + ' - ' + title;
                            }

                            events.push({
                                id: row.id,
                                title: title,
                                start: row.fecha_inicio,
                                end: row.fecha_fin,
                                color: row.color_evento,
                                hora_inicio: row.hora_inicio,
                                descripcion: row.descripcion,
                                evento: row.evento
                            });
                        }
                        resolve(events);
                    },
                    (tx, error) => {
                        reject(error);
                    }
                );
            });
        });
    }

    getEventsForDate(date) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion FROM eventoscalendar WHERE fecha_inicio <= ? AND fecha_fin > ? ORDER BY hora_inicio ASC, evento ASC',
                    [date, date],
                    (tx, result) => {
                        const events = [];
                        for (let i = 0; i < result.rows.length; i++) {
                            events.push(result.rows.item(i));
                        }
                        resolve(events);
                    },
                    (tx, error) => {
                        reject(error);
                    }
                );
            });
        });
    }

    deleteEvent(id) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'DELETE FROM eventoscalendar WHERE id = ?',
                    [id],
                    (tx, result) => {
                        if (result.rowsAffected > 0) {
                            resolve({
                                success: true,
                                message: 'Event deleted successfully'
                            });
                        } else {
                            reject({
                                success: false,
                                error: 'Event not found'
                            });
                        }
                    },
                    (tx, error) => {
                        reject({
                            success: false,
                            error: 'Failed to delete event',
                            details: error
                        });
                    }
                );
            });
        });
    }

    getEventById(id) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion FROM eventoscalendar WHERE id = ?',
                    [id],
                    (tx, result) => {
                        if (result.rows.length > 0) {
                            resolve(result.rows.item(0));
                        } else {
                            resolve(null);
                        }
                    },
                    (tx, error) => {
                        reject(error);
                    }
                );
            });
        });
    }

    validateEventData(data) {
        const errors = [];

        if (!data.evento || data.evento.trim() === '') {
            errors.push('Event title is required');
        } else if (data.evento.trim().length > 250) {
            errors.push('Event title must be 250 characters or less');
        }

        if (!data.fecha_inicio) {
            errors.push('Start date is required');
        } else if (!this.parseDate(data.fecha_inicio)) {
            errors.push('Invalid start date format');
        }

        if (!data.fecha_fin) {
            errors.push('End date is required');
        } else {
            const parsedEndDate = this.parseDate(data.fecha_fin);
            const parsedStartDate = this.parseDate(data.fecha_inicio);
            if (!parsedEndDate) {
                errors.push('Invalid end date format');
            } else if (parsedStartDate && new Date(parsedStartDate) > new Date(parsedEndDate)) {
                errors.push('End date must be after start date');
            }
        }

        if (!data.color_evento) {
            errors.push('Event color is required');
        }

        if (data.hora_inicio && !/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(data.hora_inicio)) {
            errors.push('Invalid time format (use HH:MM)');
        }

        if (data.descripcion && data.descripcion.length > 1000) {
            errors.push('Description must be 1000 characters or less');
        }

        return errors;
    }

    parseDate(dateString) {
        if (!dateString) return false;

        if (/^\d{4}-\d{1,2}-\d{1,2}$/.test(dateString)) {
            const parts = dateString.split('-');
            const year = parts[0];
            const month = parts[1].padStart(2, '0');
            const day = parts[2].padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        if (/^\d{1,2}-\d{1,2}-\d{4}$/.test(dateString)) {
            const parts = dateString.split('-');
            const day = parts[0].padStart(2, '0');
            const month = parts[1].padStart(2, '0');
            const year = parts[2];
            return `${year}-${month}-${day}`;
        }

        const timestamp = Date.parse(dateString);
        if (!isNaN(timestamp)) {
            return new Date(timestamp).toISOString().split('T')[0];
        }

        return false;
    }

    calculateEndDate(fechaFin) {
        const parsedDate = this.parseDate(fechaFin);
        const date = new Date(parsedDate);
        date.setDate(date.getDate() + 1);
        return date.toISOString().split('T')[0];
    }

    capitalizeWords(str) {
        return str.replace(/\w\S*/g, (txt) => {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }
}