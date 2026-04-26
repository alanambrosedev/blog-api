<x-mail::message>
    # Welcome, {{ $user->name }}!

    Thank you for joining our platform. We are excited to have you on board.

    <x-mail::button :url="'https://yourplatform.com/dashboard'">
        Go to Dashboard
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>