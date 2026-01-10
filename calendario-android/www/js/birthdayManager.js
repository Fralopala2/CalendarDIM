class BirthdayManager {
    constructor(database) {
        this.db = database;
    }

    saveBirthday(data) {
        return new Promise((resolve, reject) => {
            const validationErrors = this.validateBirthdayData(data);
            if (validationErrors.length > 0) {
                reject({
                    success: false,
                    error: 'Validation failed',
                    details: validationErrors
                });
                return;
            }

            const nombre = this.capitalizeWords(data.nombre.trim());
            const diaNacimiento = parseInt(data.dia_nacimiento);
            const mesNacimiento = parseInt(data.mes_nacimiento);

            if (data.id) {
                this.updateBirthday(data.id, nombre, diaNacimiento, mesNacimiento)
                    .then(resolve)
                    .catch(reject);
            } else {
                this.createBirthday(nombre, diaNacimiento, mesNacimiento)
                    .then(resolve)
                    .catch(reject);
            }
        });
    }

    createBirthday(nombre, diaNacimiento, mesNacimiento) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'INSERT INTO cumpleanos (nombre, dia_nacimiento, mes_nacimiento) VALUES (?, ?, ?)',
                    [nombre, diaNacimiento, mesNacimiento],
                    (tx, result) => {
                        resolve({
                            success: true,
                            message: 'Birthday created successfully',
                            birthday_id: result.insertId
                        });
                    },
                    (tx, error) => {
                        reject({
                            success: false,
                            error: 'Failed to create birthday',
                            details: error
                        });
                    }
                );
            });
        });
    }

    updateBirthday(id, nombre, diaNacimiento, mesNacimiento) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'UPDATE cumpleanos SET nombre = ?, dia_nacimiento = ?, mes_nacimiento = ? WHERE id = ?',
                    [nombre, diaNacimiento, mesNacimiento, id],
                    (tx, result) => {
                        resolve({
                            success: true,
                            message: 'Birthday updated successfully',
                            birthday_id: id
                        });
                    },
                    (tx, error) => {
                        reject({
                            success: false,
                            error: 'Failed to update birthday',
                            details: error
                        });
                    }
                );
            });
        });
    }

    getAllBirthdays() {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'SELECT id, nombre, dia_nacimiento, mes_nacimiento, created_at FROM cumpleanos ORDER BY mes_nacimiento ASC, dia_nacimiento ASC, nombre ASC',
                    [],
                    (tx, result) => {
                        const birthdays = [];
                        for (let i = 0; i < result.rows.length; i++) {
                            birthdays.push(result.rows.item(i));
                        }
                        resolve(birthdays);
                    },
                    (tx, error) => {
                        reject(error);
                    }
                );
            });
        });
    }

    getBirthdaysForCalendar(year = null) {
        return new Promise((resolve, reject) => {
            if (!year) {
                year = new Date().getFullYear();
            }

            this.getAllBirthdays()
                .then(birthdays => {
                    const calendarBirthdays = [];
                    
                    birthdays.forEach(birthday => {
                        if (this.isValidDate(birthday.mes_nacimiento, birthday.dia_nacimiento, year)) {
                            const birthdayDate = `${year}-${birthday.mes_nacimiento.toString().padStart(2, '0')}-${birthday.dia_nacimiento.toString().padStart(2, '0')}`;
                            
                            calendarBirthdays.push({
                                id: 'birthday_' + birthday.id,
                                title: '🎂 ' + birthday.nombre,
                                start: birthdayDate,
                                end: birthdayDate,
                                color: '#FFD700',
                                allDay: true,
                                type: 'birthday',
                                birthday_id: birthday.id,
                                nombre: birthday.nombre,
                                dia_nacimiento: birthday.dia_nacimiento,
                                mes_nacimiento: birthday.mes_nacimiento
                            });
                        }
                    });
                    
                    resolve(calendarBirthdays);
                })
                .catch(reject);
        });
    }

    getBirthdaysForDate(date) {
        return new Promise((resolve, reject) => {
            const dateObj = new Date(date);
            const day = dateObj.getDate();
            const month = dateObj.getMonth() + 1;

            this.db.transaction(tx => {
                tx.executeSql(
                    'SELECT id, nombre, dia_nacimiento, mes_nacimiento, created_at FROM cumpleanos WHERE dia_nacimiento = ? AND mes_nacimiento = ? ORDER BY nombre ASC',
                    [day, month],
                    (tx, result) => {
                        const birthdays = [];
                        for (let i = 0; i < result.rows.length; i++) {
                            birthdays.push(result.rows.item(i));
                        }
                        resolve(birthdays);
                    },
                    (tx, error) => {
                        reject(error);
                    }
                );
            });
        });
    }

    deleteBirthday(id) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'DELETE FROM cumpleanos WHERE id = ?',
                    [id],
                    (tx, result) => {
                        if (result.rowsAffected > 0) {
                            resolve({
                                success: true,
                                message: 'Birthday deleted successfully'
                            });
                        } else {
                            reject({
                                success: false,
                                error: 'Birthday not found'
                            });
                        }
                    },
                    (tx, error) => {
                        reject({
                            success: false,
                            error: 'Failed to delete birthday',
                            details: error
                        });
                    }
                );
            });
        });
    }

    getBirthdayById(id) {
        return new Promise((resolve, reject) => {
            this.db.transaction(tx => {
                tx.executeSql(
                    'SELECT id, nombre, dia_nacimiento, mes_nacimiento, created_at FROM cumpleanos WHERE id = ?',
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

    validateBirthdayData(data) {
        const errors = [];

        if (!data.nombre || data.nombre.trim() === '') {
            errors.push('Person name is required');
        } else if (data.nombre.trim().length > 100) {
            errors.push('Person name must be 100 characters or less');
        }

        if (!data.dia_nacimiento || data.dia_nacimiento === '') {
            errors.push('Birth day is required');
        } else if (!Number.isInteger(Number(data.dia_nacimiento)) || data.dia_nacimiento < 1 || data.dia_nacimiento > 31) {
            errors.push('Birth day must be between 1 and 31');
        }

        if (!data.mes_nacimiento || data.mes_nacimiento === '') {
            errors.push('Birth month is required');
        } else if (!Number.isInteger(Number(data.mes_nacimiento)) || data.mes_nacimiento < 1 || data.mes_nacimiento > 12) {
            errors.push('Birth month must be between 1 and 12');
        }

        if (data.dia_nacimiento && data.mes_nacimiento) {
            const day = parseInt(data.dia_nacimiento);
            const month = parseInt(data.mes_nacimiento);
            
            if (!this.isValidDate(month, day, 2023)) {
                errors.push('Invalid day/month combination');
            }
        }

        return errors;
    }

    isValidDate(month, day, year) {
        const date = new Date(year, month - 1, day);
        return date.getFullYear() === year && 
               date.getMonth() === month - 1 && 
               date.getDate() === day;
    }

    capitalizeWords(str) {
        return str.replace(/\w\S*/g, (txt) => {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }
}