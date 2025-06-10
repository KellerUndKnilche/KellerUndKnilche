<?php
/**
 * Zentrale Benachrichtigungsverwaltung
 * Hier können alle Login-Benachrichtigungen zentral verwaltet werden
 */

class LoginNotifications {
    
    /**
     * Aktuelle Benachrichtigungen
     * Einfach hier bearbeiten um Nachrichten zu ändern oder zu deaktivieren
     */
    public static function getActiveNotifications() {
        return [
            [
                'id' => 'balance_update_2025_06',
                'type' => 'info', // info, warning, success, danger
                'message' => 'Ein Gleichgewichtszauber wurde gewirkt - alle Verbesserungen und Schätze wurden neu verteilt!',
                'active' => true,
                'dismissible' => true
            ],
            // Weitere Benachrichtigungen können hier einfach hinzugefügt werden:
            /*
            [
                'id' => 'maintenance_notice',
                'type' => 'warning',
                'message' => 'Wartungsarbeiten geplant für morgen 14:00-16:00 Uhr.',
                'active' => false, // Auf true setzen um zu aktivieren
                'dismissible' => true
            ],
            [
                'id' => 'new_feature',
                'type' => 'success',
                'message' => 'Neue Features verfügbar! Schau dir das Tutorial an.',
                'active' => false,
                'dismissible' => true
            ]
            */
        ];
    }
    
    /**
     * HTML für eine Benachrichtigung generieren
     */
    public static function renderNotification($notification) {
        if (!$notification['active']) {
            return '';
        }
        
        $dismissibleHtml = $notification['dismissible'] 
            ? '<button type="button" class="notification-close" aria-label="Schließen">&times;</button>'
            : '';
        
        return "
        <div class=\"login-notification type-{$notification['type']}\">
            <div class=\"notification-content\">
                <div class=\"notification-text\">{$notification['message']}</div>
            </div>
            {$dismissibleHtml}
        </div>";
    }
    
    /**
     * Alle aktiven Benachrichtigungen rendern
     */
    public static function renderAllNotifications() {
        $html = '';
        $notifications = self::getActiveNotifications();
        
        foreach ($notifications as $notification) {
            $html .= self::renderNotification($notification);
        }
        
        return $html;
    }
}
?>
