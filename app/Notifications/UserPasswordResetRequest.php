<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use GuzzleHttp\Client;

class UserPasswordResetRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->user->phone) {
            return $this->toSms();
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('passwords.email_password_reset_request_subject'))
            ->line(__('passwords.email_password_reset_request_line1'))
            ->line($this->token)
            ->line(__('passwords.email_password_reset_request_line2'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toSms()
    {
        $phone = $this->user->phone;
        if (strlen($phone) === 8) {
            $phone = "216{$phone}";
        }
        $message = "Votre code de vérification est {$this->token}";
        $from = "CLASSQUIZ";
        $key = "c2FicmluZS5pYnJhaGltQGVudmFzdC50bjpDbEFTc3NRdUlaejE2ODA1MTcqKio";
        $url = "https://www.winsmspro.com/sms/sms/api?action=send-sms&api_key={$key}=&to={$phone}&from={$from}&sms={$message}";
        $client = new Client(['headers' => [
            'User-Agent' => '',
        ]]);
        $client->get($url);
    }
}
