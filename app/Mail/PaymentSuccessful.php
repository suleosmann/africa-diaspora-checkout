<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $transaction;

    public function __construct(User $member, Transaction $transaction)
    {
        $this->member = $member;
        $this->transaction = $transaction;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Successful - Premium Membership Activated!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-successful',
        );
    }
}