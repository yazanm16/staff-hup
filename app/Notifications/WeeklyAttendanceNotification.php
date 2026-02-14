<?php
namespace App\Notifications;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
class WeeklyAttendanceNotification extends Notification
{
    public function __construct(protected array $issues)
    {
        
    }
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }
    public function toArray($notifiable)
    {
       return ['message' => 'Weekly attendance report is ready'];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Weekly attendance report is ready',
        ]);
    }
}