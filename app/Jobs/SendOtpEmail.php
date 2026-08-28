<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

/**
 * Queueable OTP email.
 *
 * Dispatched instead of sending synchronously so the request that ends up
 * on the OTP screen does not wait ~20-30s for Gmail SMTP. The job is stored
 * in the `jobs` table and processed by a running queue worker.
 */
class SendOtpEmail implements ShouldQueue
{
    use Queueable;

    /**
     * @param string $email   Recipient address.
     * @param string $body    Plain-text email body.
     * @param string $subject Email subject line.
     */
    public function __construct(
        public string $email,
        public string $body,
        public string $subject,
    ) {}

    /**
     * Send the email when the job is processed by the queue worker.
     */
    public function handle(): void
    {
        Mail::raw(
            $this->body,
            function ($message) {
                $message->to($this->email)
                    ->subject($this->subject);
            }
        );
    }
}