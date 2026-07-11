<?php

namespace App\Notifications;

use App\Filament\Resources\Messages\MessageResource;
use App\Filament\Resources\Photos\PhotoResource;
use App\Models\Message;
use App\Models\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubmissionNotification extends Notification
{
    use Queueable;

    public function __construct(protected Message|Photo $submission)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->submission instanceof Message) {
            $hunt = $this->submission->clue->hunt;

            return (new MailMessage)
                ->subject("New message board post — {$hunt->title}")
                ->line("{$this->submission->nickname} posted a message on \"{$hunt->title}\".")
                ->line($this->submission->body)
                ->action('Review in admin', MessageResource::getUrl('index'));
        }

        $hunt = $this->submission->hunt;

        return (new MailMessage)
            ->subject("New photo posted — {$hunt->title}")
            ->line("{$this->submission->nickname} posted a photo on \"{$hunt->title}\".")
            ->when($this->submission->caption, fn (MailMessage $mail) => $mail->line($this->submission->caption))
            ->action('Review in admin', PhotoResource::getUrl('index'));
    }
}
