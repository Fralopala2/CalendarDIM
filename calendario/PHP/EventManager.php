<?php
/**
 * EventManager Class
 * 
 * Handles CRUD operations for calendar events with enhanced functionality
 * including time support, descriptions, and unified save/update logic.
 * 
 * Requirements: 3.4, 3.6, 4.4
 */

class EventManager {
    private $connection;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    /**
     * Save event - creates new or updates existing based on ID presence
     * Requirement 3.4: Check if event exists and either update or create accordingly
     */
    public function saveEvent($data) {
        try {
            // Validate input data
            $validationErrors = $this->validateEventData($data);
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validationErrors
                ];
            }
            
            // Prepare data
            $evento = ucwords(trim($data['evento']));
            $fecha_inicio = date('Y-m-d', strtotime($data['fecha_inicio']));
            
            // Handle end date - add 1 day as per original logic
            $f_fin = $data['fecha_fin'];
            $seteando_f_final = date('Y-m-d', strtotime($f_fin));
            $fecha_fin1 = strtotime($seteando_f_final . "+ 1 days");
            $fecha_fin = date('Y-m-d', $fecha_fin1);
            
            $color_evento = $data['color_evento'];
            $hora_inicio = isset($data['hora_inicio']) ? $data['hora_inicio'] : null;
            $descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : null;
            
            // Check if this is an update (ID provided) or new event
            if (isset($data['id']) && !empty($data['id'])) {
                return $this->updateEvent($data['id'], $evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion);
            } else {
                return $this->createEvent($evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion);
            }
            
        } catch (Exception $e) {
            error_log("EventManager::saveEvent Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Database error occurred',
                'details' => []
            ];
        }
    }
    
    /**
     * Create new event
     */
    private function createEvent($evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion) {
        $stmt = $this->connection->prepare("
            INSERT INTO eventoscalendar (
                evento, 
                fecha_inicio, 
                fecha_fin, 
                color_evento, 
                hora_inicio, 
                descripcion
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Failed to prepare statement',
                'details' => []
            ];
        }
        
        $stmt->bind_param("ssssss", $evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion);
        
        if ($stmt->execute()) {
            $eventId = $this->connection->insert_id;
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Event created successfully',
                'event_id' => $eventId
            ];
        } else {
            $stmt->close();
            return [
                'success' => false,
                'error' => 'Failed to create event',
                'details' => []
            ];
        }
    }
    
    /**
     * Update existing event
     */
    private function updateEvent($id, $evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion) {
        $stmt = $this->connection->prepare("
            UPDATE eventoscalendar 
            SET evento = ?, 
                fecha_inicio = ?, 
                fecha_fin = ?, 
                color_evento = ?, 
                hora_inicio = ?, 
                descripcion = ?
            WHERE id = ?
        ");
        
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Failed to prepare statement',
                'details' => []
            ];
        }
        
        $stmt->bind_param("ssssssi", $evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion, $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Event updated successfully',
                'event_id' => $id
            ];
        } else {
            $stmt->close();
            return [
                'success' => false,
                'error' => 'Failed to update event',
                'details' => []
            ];
        }
    }
    
    /**
     * Get events for a specific date
     */
    public function getEventsForDate($date) {
        $stmt = $this->connection->prepare("
            SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion 
            FROM eventoscalendar 
            WHERE fecha_inicio <= ? AND fecha_fin > ?
            ORDER BY hora_inicio ASC, evento ASC
        ");
        
        if (!$stmt) {
            return [];
        }
        
        $stmt->bind_param("ss", $date, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        
        $stmt->close();
        return $events;
    }
    
    /**
     * Get events for a specific month
     * Requirement 3.6: Include new fields in event queries
     */
    public function getEventsForMonth($year, $month) {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-d', strtotime($startDate . ' +1 month'));
        
        $stmt = $this->connection->prepare("
            SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion 
            FROM eventoscalendar 
            WHERE (fecha_inicio >= ? AND fecha_inicio < ?) 
               OR (fecha_fin > ? AND fecha_inicio < ?)
            ORDER BY fecha_inicio ASC, hora_inicio ASC, evento ASC
        ");
        
        if (!$stmt) {
            return [];
        }
        
        $stmt->bind_param("ssss", $startDate, $endDate, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        
        $stmt->close();
        return $events;
    }
    
    /**
     * Get all events (for FullCalendar display)
     * Requirement 4.4: Show both time and title in calendar display
     */
    public function getAllEvents() {
        $stmt = $this->connection->prepare("
            SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion 
            FROM eventoscalendar 
            ORDER BY fecha_inicio ASC, hora_inicio ASC
        ");
        
        if (!$stmt) {
            return [];
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $events = [];
        while ($row = $result->fetch_assoc()) {
            // Format for FullCalendar - include time in title if available
            $title = $row['evento'];
            if (!empty($row['hora_inicio'])) {
                $title = date('H:i', strtotime($row['hora_inicio'])) . ' - ' . $title;
            }
            
            $events[] = [
                'id' => $row['id'],
                'title' => $title,
                'start' => $row['fecha_inicio'],
                'end' => $row['fecha_fin'],
                'color' => $row['color_evento'],
                'hora_inicio' => $row['hora_inicio'],
                'descripcion' => $row['descripcion'],
                'evento' => $row['evento'] // Original title without time
            ];
        }
        
        $stmt->close();
        return $events;
    }
    
    /**
     * Delete event by ID
     */
    public function deleteEvent($id) {
        $stmt = $this->connection->prepare("DELETE FROM eventoscalendar WHERE id = ?");
        
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
                    'message' => 'Event deleted successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Event not found',
                    'details' => []
                ];
            }
        } else {
            $stmt->close();
            return [
                'success' => false,
                'error' => 'Failed to delete event',
                'details' => []
            ];
        }
    }
    
    /**
     * Get single event by ID
     */
    public function getEventById($id) {
        $stmt = $this->connection->prepare("
            SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion 
            FROM eventoscalendar 
            WHERE id = ?
        ");
        
        if (!$stmt) {
            return null;
        }
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $event = $result->fetch_assoc();
        $stmt->close();
        
        return $event;
    }
    
    /**
     * Validate event data
     */
    private function validateEventData($data) {
        $errors = [];
        
        // Event title validation
        if (empty($data['evento']) || trim($data['evento']) === '') {
            $errors['evento'] = 'Event title is required';
        } elseif (strlen(trim($data['evento'])) > 250) {
            $errors['evento'] = 'Event title must be 250 characters or less';
        }
        
        // Start date validation
        if (empty($data['fecha_inicio'])) {
            $errors['fecha_inicio'] = 'Start date is required';
        } elseif (!strtotime($data['fecha_inicio'])) {
            $errors['fecha_inicio'] = 'Invalid start date format';
        }
        
        // End date validation
        if (empty($data['fecha_fin'])) {
            $errors['fecha_fin'] = 'End date is required';
        } elseif (!strtotime($data['fecha_fin'])) {
            $errors['fecha_fin'] = 'Invalid end date format';
        } elseif (strtotime($data['fecha_inicio']) > strtotime($data['fecha_fin'])) {
            $errors['fecha_fin'] = 'End date must be after start date';
        }
        
        // Color validation
        if (empty($data['color_evento'])) {
            $errors['color_evento'] = 'Event color is required';
        }
        
        // Time validation (optional)
        if (!empty($data['hora_inicio'])) {
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['hora_inicio'])) {
                $errors['hora_inicio'] = 'Invalid time format (use HH:MM)';
            }
        }
        
        // Description validation (optional, but limit length)
        if (!empty($data['descripcion']) && strlen($data['descripcion']) > 1000) {
            $errors['descripcion'] = 'Description must be 1000 characters or less';
        }
        
        return $errors;
    }
}
?>