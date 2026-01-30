<?php

class EventManager {
    private $connection;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    public function saveEvent($data) {
        try {
            $validationErrors = $this->validateEventData($data);
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validationErrors
                ];
            }
            
            $evento = ucwords(trim($data['evento']));
            $fecha_inicio = $this->parseDate($data['fecha_inicio']);
            
            $fecha_fin_parsed = $this->parseDate($data['fecha_fin']);
            $fecha_fin1 = strtotime($fecha_fin_parsed . "+ 1 days");
            $fecha_fin = date('Y-m-d', $fecha_fin1);
            
            $color_evento = $data['color_evento'];
            $hora_inicio = isset($data['hora_inicio']) ? $data['hora_inicio'] : null;
            $descripcion = isset($data['descripcion']) ? trim($data['descripcion']) : null;
            $es_recurrente = isset($data['es_recurrente']) ? $data['es_recurrente'] : 0;
            $recurring_group_id = isset($data['recurring_group_id']) ? $data['recurring_group_id'] : null;
            
            if (isset($data['id']) && !empty($data['id'])) {
                return $this->updateEvent($data['id'], $evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion);
            } else {
                return $this->createEvent($evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion, $es_recurrente, $recurring_group_id);
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
    
    private function createEvent($evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion, $es_recurrente = 0, $recurring_group_id = null) {
        $stmt = $this->connection->prepare("
            INSERT INTO eventoscalendar (
                evento, 
                fecha_inicio, 
                fecha_fin, 
                color_evento, 
                hora_inicio, 
                descripcion,
                es_recurrente,
                recurring_group_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Failed to prepare statement: ' . $this->connection->error,
                'details' => []
            ];
        }
        
        // Bind parameters
        $stmt->bind_param("ssssssis", $evento, $fecha_inicio, $fecha_fin, $color_evento, $hora_inicio, $descripcion, $es_recurrente, $recurring_group_id);
        
        if ($stmt->execute()) {
            $eventId = $this->connection->insert_id;
            $stmt->close();
            return [
                'success' => true,
                'message' => 'Event created successfully',
                'event_id' => $eventId
            ];
        } else {
            $error = $stmt->error;
            $stmt->close();
            return [
                'success' => false,
                'error' => 'Failed to create event: ' . $error,
                'details' => []
            ];
        }
    }
    
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
                'evento' => $row['evento']
            ];
        }
        
        $stmt->close();
        return $events;
    }
    
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
     * Delete a recurring event and all future instances in the same group
     * @param int $id Event ID to delete
     * @return array Result with success status and message
     */
    public function deleteRecurringEvent($id) {
        // First, get the event details to find its group and date
        $event = $this->getEventById($id);
        
        if (!$event) {
            return [
                'success' => false,
                'error' => 'Event not found',
                'details' => []
            ];
        }
        
        // Check if it's a recurring event
        if (empty($event['recurring_group_id'])) {
            // Not a recurring event, just delete it normally
            return $this->deleteEvent($id);
        }
        
        // Delete this event and all future events in the same recurring group
        $stmt = $this->connection->prepare("
            DELETE FROM eventoscalendar 
            WHERE recurring_group_id = ? 
            AND fecha_inicio >= ?
        ");
        
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Failed to prepare statement',
                'details' => []
            ];
        }
        
        $recurring_group_id = $event['recurring_group_id'];
        $fecha_inicio = $event['fecha_inicio'];
        
        $stmt->bind_param("ss", $recurring_group_id, $fecha_inicio);
        
        if ($stmt->execute()) {
            $affected = $stmt->affected_rows;
            $stmt->close();
            
            if ($affected > 0) {
                return [
                    'success' => true,
                    'message' => "Se eliminaron $affected instancias del evento recurrente",
                    'deleted_count' => $affected
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'No events found to delete',
                    'details' => []
                ];
            }
        } else {
            $error = $stmt->error;
            $stmt->close();
            return [
                'success' => false,
                'error' => 'Failed to delete recurring events: ' . $error,
                'details' => []
            ];
        }
    }
    
    public function getEventById($id) {
        $stmt = $this->connection->prepare("
            SELECT id, evento, fecha_inicio, fecha_fin, color_evento, hora_inicio, descripcion, es_recurrente, recurring_group_id 
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
    
    private function validateEventData($data) {
        $errors = [];
        
        if (empty($data['evento']) || trim($data['evento']) === '') {
            $errors['evento'] = 'Event title is required';
        } elseif (strlen(trim($data['evento'])) > 250) {
            $errors['evento'] = 'Event title must be 250 characters or less';
        }
        
        if (empty($data['fecha_inicio'])) {
            $errors['fecha_inicio'] = 'Start date is required';
        } else {
            $parsedStartDate = $this->parseDate($data['fecha_inicio']);
            if (!$parsedStartDate) {
                $errors['fecha_inicio'] = 'Invalid start date format';
            }
        }
        
        if (empty($data['fecha_fin'])) {
            $errors['fecha_fin'] = 'End date is required';
        } else {
            $parsedEndDate = $this->parseDate($data['fecha_fin']);
            if (!$parsedEndDate) {
                $errors['fecha_fin'] = 'Invalid end date format';
            } elseif (isset($parsedStartDate) && $parsedStartDate && strtotime($parsedStartDate) > strtotime($parsedEndDate)) {
                $errors['fecha_fin'] = 'End date must be after start date';
            }
        }
        
        if (empty($data['color_evento'])) {
            $errors['color_evento'] = 'Event color is required';
        }
        
        if (!empty($data['hora_inicio'])) {
            if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $data['hora_inicio'])) {
                $errors['hora_inicio'] = 'Invalid time format (use HH:MM)';
            }
        }
        
        if (!empty($data['descripcion']) && strlen($data['descripcion']) > 1000) {
            $errors['descripcion'] = 'Description must be 1000 characters or less';
        }
        
        return $errors;
    }
    
    private function parseDate($dateString) {
        if (empty($dateString)) {
            return false;
        }
        
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $dateString, $matches)) {
            $year = $matches[1];
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[3], 2, '0', STR_PAD_LEFT);
            return $year . '-' . $month . '-' . $day;
        }
        
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $dateString, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return $year . '-' . $month . '-' . $day;
        }
        
        $timestamp = strtotime($dateString);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        return false;
    }
}
?>