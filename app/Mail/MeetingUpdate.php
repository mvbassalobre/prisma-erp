<?php

namespace App\Mail;

use App\Http\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $meeting;
    public $subject;
    public $body;
    public $config;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Meeting $meeting, $subject, $body, $config = [])
    {
        $this->meeting = $meeting;
        $this->subject = $subject;
        $this->body = $body;
        $this->config = $config;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $meeting = $this->meeting;
        $customer = $this->meeting->customer;
        $data = [
            "customer" => $customer,
            "meeting" => $meeting,
            "responsible" => $meeting->responsible,
            "body" => $this->body,
            "config" => $this->config
        ];

        //dd($data);
        return $this->subject($this->subject)
            // ->to($customer->email)
            ->markdown('mail.meeting.update', $data);
    }
}
