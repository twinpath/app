@props(['theme' => 'auto', 'size' => 'normal', 'tabindex' => 0])

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

<div class="cf-turnstile" 
    data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}" 
    data-theme="{{ $theme }}"
    data-size="{{ $size }}"
    data-tabindex="{{ $tabindex }}"
    {{ $attributes }}
></div>
