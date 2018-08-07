<?php

namespace IP\Events\Listeners;

use IP\Events\NoteCreated;
use IP\Modules\MailQueue\Support\MailQueue;

class NoteCreatedListener
{
    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function handle(NoteCreated $event)
    {
        $mail = $this->mailQueue->create($event->note->notable, [
            'to' => [$event->note->notable->user->email],
            'cc' => [config('ip.mailDefaultCc')],
            'bcc' => [config('ip.mailDefaultBcc')],
            'subject' => trans('ip.note_notification'),
            'body' => $event->note->formatted_note,
            'attach_pdf' => config('ip.attachPdf'),
        ]);

        $this->mailQueue->send($mail->id);
    }
}
