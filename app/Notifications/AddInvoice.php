<?php

namespace App\Notifications;

use App\Events\InvoiceAdded;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AddInvoice extends Notification
{
    use Queueable;
    public $invoice;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = env('APP_URL').'/invoice_details/'.$this->invoice->id;
        return (new MailMessage)
                    ->greeting('مرحباً')
                    ->subject('إضافة فاتورة جديدة')
                    ->line('تم إضافة فاتورة جديدة')
                    ->action('تفاصيل الفاتورة', $url)
                    ->line('شكراً لإستخدامكم مو فواتير لإدارة الفواتير!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->invoice->id,
            'title'=> 'تم إضافة فاتورة',
            'user' => Auth::user()->name,
        ];
    }

    public function toBroadcast($notifiable)
    {
        // return new InvoiceAdded($this->invoice->id, Auth::user()->name, 'تم إضافة فاتورة');
        return new BroadcastMessage([
            'invoice_id' => $this->invoice->id,
            'title'=> 'تم إضافة فاتورة',
            'user' => Auth::user()->name,
        ]);
    }
}
