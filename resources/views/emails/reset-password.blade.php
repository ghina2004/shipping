<p>Hello,</p>
<p>You requested a password reset. Click the link below:</p>

<a href="{{ url('/reset-password?token=' . $token . '&email=' . $email) }}">
    Reset your password
</a>

<p>If you didn't request this, ignore this email.</p>
