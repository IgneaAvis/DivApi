<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateMessage extends Mailable
{
    use Queueable, SerializesModels;

    private $items;

    /**
     * @param $items
     */
    public function __construct($items)
    {
        $this->items = $items;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('update_message', ['arr' => $this->items]);
    }
}
