class DatabaseManager {
    constructor() {
        this.db = null;
        this.isInitialized = false;
        this.useSQLite = false;
        this.localStorage = window.localStorage;
    }

    init() {
        return new Promise((resolve, reject) => {
            if (this.isInitialized) {
                resolve(this.db);
                return;
            }

            const initDatabase = () => {
                if (window.sqlitePlugin) {
                    this.useSQLite = true;
                    this.db = window.sqlitePlugin.openDatabase({
                        name: 'calendario.db',
                        location: 'default'
                    });

                    this.createTables()
                        .then(() => {
                            this.isInitialized = true;
                            resolve(this.db);
                        })
                        .catch(reject);
                } else {
                    this.useSQLite = false;
                    this.initLocalStorage();
                    this.isInitialized = true;
                    resolve(this.localStorage);
                }
            };

            if (document.readyState === 'loading') {
                document.addEventListener('deviceready', initDatabase);
                setTimeout(initDatabase, 2000);
            } else {
                initDatabase();
            }
        });
    }

    initLocalStorage() {
        if (!this.localStorage.getItem('calendario_eventos')) {
            this.localStorage.setItem('calendario_eventos', JSON.stringify([]));
        }
        if (!this.localStorage.getItem('calendario_cumpleanos')) {
            this.localStorage.setItem('calendario_cumpleanos', JSON.stringify([]));
        }
    }

    createTables() {
        return new Promise((resolve, reject) => {
            if (!this.useSQLite) {
                resolve();
                return;
            }

            const createEventosTable = `
                CREATE TABLE IF NOT EXISTS eventoscalendar (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    evento TEXT,
                    color_evento TEXT,
                    fecha_inicio TEXT,
                    fecha_fin TEXT,
                    hora_inicio TEXT,
                    descripcion TEXT
                )
            `;

            const createCumpleanosTable = `
                CREATE TABLE IF NOT EXISTS cumpleanos (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    nombre TEXT NOT NULL,
                    dia_nacimiento INTEGER NOT NULL,
                    mes_nacimiento INTEGER NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            `;

            const createIndexes = [
                'CREATE INDEX IF NOT EXISTS idx_fecha_inicio ON eventoscalendar(fecha_inicio)',
                'CREATE INDEX IF NOT EXISTS idx_fecha_fin ON eventoscalendar(fecha_fin)',
                'CREATE INDEX IF NOT EXISTS idx_birth_date ON cumpleanos(dia_nacimiento, mes_nacimiento)'
            ];

            this.db.transaction(tx => {
                tx.executeSql(createEventosTable);
                tx.executeSql(createCumpleanosTable);
                
                createIndexes.forEach(indexSql => {
                    tx.executeSql(indexSql);
                });
            }, reject, resolve);
        });
    }

    getDatabase() {
        return this.db;
    }

    isUsingSQLite() {
        return this.useSQLite;
    }
}

window.databaseManager = new DatabaseManager();