<x-mail::message>
# Hello,

Thank you for reaching out to **DyDev TrustLab**.

<x-mail::panel>
{{ $replyMessage }}
</x-mail::panel>

If you have any further questions, feel free to respond to this email or visit our [Support Portal]({{ config('app.url') }}).

Best regards,<br>
**{{ config('app.name') }} Support Team**
</x-mail::message>
