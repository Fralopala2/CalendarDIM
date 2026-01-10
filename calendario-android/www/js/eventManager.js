class EventManager {
    constructor(database) {
        this.db = database;
        this.useSQLite = window.databaseManager && window.databaseManager.isUsingSQLite();
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
            if (this.useSQLite) {
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
            } else {
                try {
                    const eventos = JSON.parse(localStorage.getItem('calendario_eventos') || '[]');
                    const newId = Date.now();
                    const newEvent = {
                        id: newId,
                        evento: evento,
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin,
                        color_evento: colorEvento,
                        hora_inicio: horaInicio,
                        descripcion: descripcion
                    };
                    eventos.push(newEvent);
                    localStorage.setItem('calendario_eventos', JSON.stringify(eventos));
                    resolve({
                        success: true,
                        message: 'Event created successfully',
                        event_id: newId
                    });
                } catch (error) {
                    reject({
                        success: false,
                        error: 'Failed to create event',
                        details: error
                    });
                }
            }
        });
    }

    updateEvent(id, evento, fechaInicio, fechaFin, colorEvento, horaInicio, descripcion) {
        return new Promise((resolve, reject) => {
            if (this.useSQLite) {
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
            } else {
                try {
                    const eventos = JSON.parse(localStorage.getItem('calendario_eventos') || '[]');
                    const eventIndex = eventos.findIndex(e => e.id == id);
                    if (eventIndex !== -1) {
                        eventos[eventIndex] = {
                            id: id,
                            evento: evento,
                            fecha_inicio: fechaInicio,
                            fecha_fin: fechaFin,
                            color_evento: colorEvento,
                            hora_inicio: horaInicio,
                            descripcion: descripcion
                        };
                        localStorage.setItem('calendario_eventos', JSON.stringify(eventos));
                        resolve({
                            success: true,
                            message: 'Event updated successfully',
                            event_id: id
                        });
                    } else {
                        reject({
                            success: false,
                            error: 'Event not found'
                        });
                    }
                } catch (error) {
                    reject({
                        success: false,
                        error: 'Failed to update event',
                        details: error
                    });
                }
            }
        });
    }

    getAllEvents() {
        return new Promise((resolve, reject) => {
            if (this.useSQLite) {
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
            } else {
                try {
                    const eventos = JSON.parse(localStorage.getItem('calendario_eventos') || '[]');
                    const events = eventos.map(row => {
                        let title = row.evento;
                        if (row.hora_inicio) {
                            title = row.hora_inicio + ' - ' + title;
                        }

                        return {
                            id: row.id,
                            title: title,
                            start: row.fecha_inicio,
                            end: row.fecha_fin,
                            color: row.color_evento,
                            hora_inicio: row.hora_inicio,
                            descripcion: row.descripcion,
                            evento: row.evento
                        };
                    });
                    
                    events.sort((a, b) => {
                        if (a.start !== b.start) {
                            return a.start.localeCompare(b.start);
                        }
                        return (a.hora_inicio || '').localeCompare(b.hora_inicio || '');
                    });
                    
                    resolve(events);
                } catch (error) {
                    reject(error);
                }
            }
        });
    }

    getEventsForDate(date) {
        return new Promise((resolve, reject) => {
            if (this.useSQLite) {
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
            } else {
                try {
                    const eventos = JSON.parse(localStorage.getItem('calendario_eventos') || '[]');
                    const events = eventos.filter(event => {
                        return event.fecha_inicio <= date && event.fecha_fin > date;
                    });
                    
                    events.sort((a, b) => {
                        if (a.hora_inicio && b.hora_inicio) {
                            return a.hora_inicio.localeCompare(b.hora_inicio);
                        }
                        if (a.hora_inicio && !b.hora_inicio) return -1;
                        if (!a.hora_inicio && b.hora_inicio) return 1;
                        return a.evento.localeCompare(b.evento);
                    });
                    
                    resolve(events);
                } catch (error) {
                    reject(error);
                }
            }
        });
    }

    deleteEvent(id) {
        return new Promise((resolve, reject) => {
            if (this.useSQLite) {
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
            } else {
                try {
                    const eventos = JSON.parse(localStorage.getItem('calendario_eventos') || '[]');
                    const eventIndex = eventos.findIndex(e => e.id == id);
                    if (eventIndex !== -1) {
                        eventos.splice(eventIndex, 1);
                        localStorage.setItem('calendario_eventos', JSON.stringify(eventos));
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
                } catch (error) {
                    reject({
                        success: false,
                        error: 'Failed to delete event',
                        details: error
                    });
                }
            }
        });
    }

    getEventById(id) {
        return new Promise((resolve, reject) => {
            if (this.useSQLite) {
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
            } else {
                try {
                    const eventos = JSON.parse(localStorage.getItem('calendario_eventos') || '[]');
                    const event = eventos.find(e => e.id == id);
                    resolve(event || null);
                } catch (error) {
                    reject(error);
                }
            }
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
        }

        if (!data.fecha_fin) {
            errors.push('End date is required');
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

        // Formato YYYY-MM-DD (formato del input date)
        if (/^\d{4}-\d{1,2}-\d{1,2}$/.test(dateString)) {
            const parts = dateString.split('-');
            const year = parts[0];
            const month = parts[1].padStart(2, '0');
            const day = parts[2].padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Formato DD-MM-YYYY
        if (/^\d{1,2}-\d{1,2}-\d{4}$/.test(dateString)) {
            const parts = dateString.split('-');
            const day = parts[0].padStart(2, '0');
            const month = parts[1].padStart(2, '0');
            const year = parts[2];
            return `${year}-${month}-${day}`;
        }

        // Intentar parsear con Date
        const timestamp = Date.parse(dateString);
        if (!isNaN(timestamp)) {
            return new Date(timestamp).toISOString().split('T')[0];
        }

        return dateString; // Devolver tal como está si no se puede parsear
    }

    calculateEndDate(fechaFin) {
        const parsedDate = this.parseDate(fechaFin);
        if (!parsedDate) {
            return fechaFin; // Si no se puede parsear, devolver tal como está
        }
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