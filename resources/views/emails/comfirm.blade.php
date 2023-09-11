<x-mail::message>
# Hello {{$user->name}}

You changed your email, so we need to verify this new address. Please use the link below:

<x-mail::link :url="{{route('verify', $user->verification_token)}}">
{{route('verify', $user->verification_token)}}
</x-mail::link>

{{ config('app.name') }}
</x-mail::message>