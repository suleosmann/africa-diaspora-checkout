<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $membershipType;

    public function __construct(User $member)
    {
        $this->member = $member;
        $this->membershipType = $this->getMembershipTypeName($member->register_type);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Aden Africa Network!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.member-registered',
        );
    }

    private function getMembershipTypeName($type): string
    {
        return match($type) {
            0 => 'Join Network',
            1 => 'Download Membership',
            2 => 'Premium Membership',
            default => 'Membership',
        };
    }
}