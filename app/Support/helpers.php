<?php

if (!function_exists('csp_nonce')) {
    /**
     * Ambil nonce CSP yang di-generate oleh middleware HeaderSecurity.
     */
    function csp_nonce(): string
    {
        return (string) request()->attributes->get('csp_nonce', '');
    }
}
