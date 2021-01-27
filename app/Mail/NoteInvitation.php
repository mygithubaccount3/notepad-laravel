<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoteInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The link.
     *
     * @var string
     */
    public $link;

    /**
     * Create a new message instance.
     *
     * @param string $link
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('layouts.frontend.pages.emails.invitation');
    }
}
