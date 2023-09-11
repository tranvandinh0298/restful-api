Hello {{$user->name}}
Thank you for create an account. Please verify your email using this
{{route('verify', $user->verification_token)}}