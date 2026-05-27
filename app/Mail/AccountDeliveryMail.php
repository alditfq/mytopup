<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountDeliveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $buyerName;
    public $gameName;
    public $accountTitle;
    public $accountEmail;
    public $accountPassword;
    public $notes;

    /**
     * Create a new message instance.
     */
    public function __construct($invoice, $buyerName, $gameName, $accountTitle, $accountEmail, $accountPassword, $notes = null)
    {
        $this->invoice = $invoice;
        $this->buyerName = $buyerName;
        $this->gameName = $gameName;
        $this->accountTitle = $accountTitle;
        $this->accountEmail = $accountEmail;
        $this->accountPassword = $accountPassword;
        $this->notes = $notes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[GameTopup] Kredensial Akun Game Pembelian Anda - ' . $this->invoice,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account_delivery',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
