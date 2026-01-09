<?php
/**
 * BirthdayManager Class
 * 
 * Handles CRUD operations for birthday management with yearly recurrence
 * functionality. Stores only day and month for automatic yearly display.
 * 
 * Requirements: 2.2, 2.3, 2.5
 */

class BirthdayManager {
    private $connection;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    /**
     * Save birthday - creates new or updates existing based on ID presence
     * Requirement 2.2: Store person's name, day, and month in cumpleañoscalendar table
     */
    public function saveBirthday($data) {
        try {
            // Validate input data
            $validationErrors = $this->validateBirthdayData($data);
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validationErrors
                ];
            }
            
            // Prepare data
            $nombre = ucwords(trim($data['nombre']));
            $dia_nacimiento = (int)$data['dia_nacimiento'];
            $mes_nacimiento = (int)$data['mes_nacimiento'];
            
            // Check if this is an update (ID provided) or new birthday
            if (isset($data['id']) && !empty($data['id'])) {
                return $this->updateBirthday($data['id'], $nombre, $dia_nacimiento, $mes_nacimiento);
            } else {
                return $this->createBirthday($nombre, $dia_nacimiento, $mes_nacimiento);
            }
            
        } catch (Exception $e) {
            error_log("BirthdayManager::saveBirthday Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Database error occurred',
                'details' => []
            ];
        }
    }
    
    /**
     * Create new birthday
     */
    private function createBirthday($nombre, $dia_nacimiento, $mes_nacimiento) {
        $stmt = $this->connection->prepare("
            INSERT INTO cumpleanos (
                nombre, 
                dia_nacimiento, 
                mes_nacimiento
            ) VALUES (?, ?, ?)
        ");
        
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Failed to prepare statement',
                'details' => []
            ];
        }
        
        $stmt->bind_param("sii", $nombre, $dia_nacimiento, $mes_nacimiento);
        
        if ($stmt->execute()) {
            $birthdayId = $this->connection->insert_id;
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Birthday created successfully',
                'birthday_id' => $birthdayId
            ];
        } else {
            $stmt->close();
            return [
                'success' => false,
                'error' => 'Failed to create birthday',
                'details' => []
            ];
        }
    }
    
    /**
     * Update existing birthday
     */
    private function updateBirthday($id, $nombre, $dia_nacimiento, $mes_nacimiento) {
        $stmt = $this->connection->prepare("
            UPDATE cumpleanos 
            SET nombre = ?, 
                dia_nacimiento = ?, 
                mes_nacimiento = ?
            WHERE id = ?
        ");
        
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Failed to prepare statement',
                'details' => []
            ];
        }
        
        $stmt->bind_param("siii", $nombre, $dia_nacimiento, $mes_nacimiento, $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Birthday updated successfully',
                'birthday_id' => $id
            ];
        } else {
            $stmt->close();
            return [
                'success' => false,
                'error' => 'Failed to update birthday',
                'details' => []
            ];
        }
    }
    
    /**
     * Get birthdays for a specific date
     */
    public function getBirthdaysForDate($date) {
        $dateObj = new DateTime($date);
        $day = (int)$dateObj->format('j');
        $month = (int)$dateObj->format('n');
        
        $stmt = $this->connection->prepare("
            SELECT id, nombre, dia_nacimiento, mes_nacimiento, created_at 
            FROM cumpleanos 
            WHERE dia_nacimiento = ? AND mes_nacimiento = ?
            ORDER BY nombre ASC
        ");
        
        if (!$stmt) {
            return [];
        }
        
        $stmt->bind_param("ii", $day, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $birthdays = [];
        while ($row = $result->fetch_assoc()) {
            $birthdays[] = $row;
        }
        
        $stmt->close();
        return $birthdays;
    }
    
    /**
     * Get birthdays for a specific month
     * Requirement 2.3: Query for birthdays in that month and display them
     */
    public function getBirthdaysForMonth($year, $month) {
        $stmt = $this->connection->prepare("
            SELECT id, nombre, dia_nacimiento, mes_nacimiento, created_at 
            FROM cumpleanos 
            WHERE mes_nacimiento = ?
            ORDER BY dia_nacimiento ASC, nombre ASC
        ");
        
        if (!$stmt) {
            return [];
        }
        
        $stmt->bind_param("i", $month);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $birthdays = [];
        while ($row = $result->fetch_assoc()) {
            // Add the year for display purposes (yearly recurrence)
            $row['display_date'] = sprintf('%04d-%02d-%02d', $year, $row['mes_nacimiento'], $row['dia_nacimiento']);
            $birthdays[] = $row;
        }
        
        $stmt->close();
        return $birthdays;
    }
    
    /**
     * Get all birthdays for yearly recurrence display
     * Requirement 2.5: Handle recurring yearly birthdays automatically
     */
    public function getAllBirthdays() {
        $stmt = $this->connection->prepare("
            SELECT id, nombre, dia_nacimiento, mes_nacimiento, created_at 
            FROM cumpleanos 
            ORDER BY mes_nacimiento ASC, dia_nacimiento ASC, nombre ASC
        ");
        
        if (!$stmt) {
            return [];
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $birthdays = [];
        while ($row = $result->fetch_assoc()) {
            $birthdays[] = $row;
        }
        
        $stmt->close();
        return $birthdays;
    }
    
    /**
     * Get birthdays formatted for FullCalendar display
     * Requirement 2.5: Yearly recurrence for current year
     */
    public function getBirthdaysForCalendar($year = null) {
        if ($year === null) {
            $year = date('Y');
        }
        
        $allBirthdays = $this->getAllBirthdays();
        $calendarBirthdays = [];
        
        foreach ($allBirthdays as $birthday) {
            // Create birthday event for the specified year
            $birthdayDate = sprintf('%04d-%02d-%02d', $year, $birthday['mes_nacimiento'], $birthday['dia_nacimiento']);
            
            // Validate the date (handle leap year issues)
            if (checkdate($birthday['mes_nacimiento'], $birthday['dia_nacimiento'], $year)) {
                $calendarBirthdays[] = [
                    'id' => 'birthday_' . $birthday['id'],
                    'title' => '🎂 ' . $birthday['nombre'],
                    'start' => $birthdayDate,
                    'end' => $birthdayDate,
                    'color' => '#FFD700', // Gold color for birthdays
                    'allDay' => true,
                    'type' => 'birthday',
                    'birthday_id' => $birthday['id'],
                    'nombre' => $birthday['nombre'],
                    'dia_nacimiento' => $birthday['dia_nacimiento'],
                    'mes_nacimiento' => $birthday['mes_nacimiento']
                ];
            }
        }
        
        return $calendarBirthdays;
    }
    
    /**
     * Delete birthday by ID
     */
    public function deleteBirthday($id) {
        $stmt = $this->connection->prepare("DELETE FROM cumpleanos WHERE id = ?");
        
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Failed to prepare statement',
                'details' => []
            ];
        }
        
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $affected = $stmt->affected_rows;
            $stmt->close();
            
            if ($affected > 0) {
                return [
                    'success' => true,
                    'message' => 'Birthday deleted successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Birthday not found',
                    'details' => []
                ];
            }
        } else {
            $stmt->close();
            return [
                'success' => false,
                'error' => 'Failed to delete birthday',
                'details' => []
            ];
        }
    }
    
    /**
     * Get single birthday by ID
     */
    public function getBirthdayById($id) {
        $stmt = $this->connection->prepare("
            SELECT id, nombre, dia_nacimiento, mes_nacimiento, created_at 
            FROM cumpleanos 
            WHERE id = ?
        ");
        
        if (!$stmt) {
            return null;
        }
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $birthday = $result->fetch_assoc();
        $stmt->close();
        
        return $birthday;
    }
    
    /**
     * Check if a birthday exists for a specific person and date
     */
    public function birthdayExists($nombre, $dia_nacimiento, $mes_nacimiento, $excludeId = null) {
        $sql = "SELECT id FROM cumpleanos WHERE nombre = ? AND dia_nacimiento = ? AND mes_nacimiento = ?";
        $params = [$nombre, $dia_nacimiento, $mes_nacimiento];
        $types = "sii";
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
            $types .= "i";
        }
        
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $exists = $result->num_rows > 0;
        $stmt->close();
        
        return $exists;
    }
    
    /**
     * Validate birthday data
     */
    private function validateBirthdayData($data) {
        $errors = [];
        
        // Name validation
        if (empty($data['nombre']) || trim($data['nombre']) === '') {
            $errors['nombre'] = 'Person name is required';
        } elseif (strlen(trim($data['nombre'])) > 100) {
            $errors['nombre'] = 'Person name must be 100 characters or less';
        }
        
        // Day validation
        if (!isset($data['dia_nacimiento']) || $data['dia_nacimiento'] === '') {
            $errors['dia_nacimiento'] = 'Birth day is required';
        } elseif (!is_numeric($data['dia_nacimiento']) || $data['dia_nacimiento'] < 1 || $data['dia_nacimiento'] > 31) {
            $errors['dia_nacimiento'] = 'Birth day must be between 1 and 31';
        }
        
        // Month validation
        if (!isset($data['mes_nacimiento']) || $data['mes_nacimiento'] === '') {
            $errors['mes_nacimiento'] = 'Birth month is required';
        } elseif (!is_numeric($data['mes_nacimiento']) || $data['mes_nacimiento'] < 1 || $data['mes_nacimiento'] > 12) {
            $errors['mes_nacimiento'] = 'Birth month must be between 1 and 12';
        }
        
        // Validate day/month combination
        if (empty($errors['dia_nacimiento']) && empty($errors['mes_nacimiento'])) {
            $day = (int)$data['dia_nacimiento'];
            $month = (int)$data['mes_nacimiento'];
            
            // Check if the date is valid (use a non-leap year for validation)
            if (!checkdate($month, $day, 2023)) {
                $errors['dia_nacimiento'] = 'Invalid day/month combination';
            }
        }
        
        return $errors;
    }
}
?>