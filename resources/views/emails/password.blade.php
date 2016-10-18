You are receiving this email because you've filled out our 'Forgot Password' form.

Click the link below to reset your password: 
{{ url('reset_password', $token).'?email='.urlencode($email) }}