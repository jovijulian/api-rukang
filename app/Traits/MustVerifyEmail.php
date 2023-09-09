<?php

namespace App\Traits;

use App\Notifications\VerifyEmail;

/**
 * Trait MustVerifyEmail.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Traits
 */
trait MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified($time)
    {
        return $this->forceFill([
            'email_verified_at' => $time,
        ])->save();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }
}
