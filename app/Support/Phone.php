<?php
// app/Support/Phone.php
namespace App\Support;

class Phone
{
    /** E.164 normalize: default India (+91) */
    public static function normalize(string $raw, string $defaultCountry = 'IN'): string
    {
        $raw = trim($raw ?? '');

        // Already in +.. format
        if (str_starts_with($raw, '+')) {
            $digits = '+' . preg_replace('/\D+/', '', ltrim($raw, '+'));
            return $digits;
        }

        // Handle 0091... style
        if (preg_match('/^00(\d+)/', $raw, $m)) {
            return '+' . preg_replace('/\D+/', '', $m[1]);
        }

        // Strip all non-digits
        $digits = preg_replace('/\D+/', '', $raw);

        if ($defaultCountry === 'IN') {
            // Drop leading zeros
            $digits = ltrim($digits, '0');

            // If starts with 91 and total 12 digits => assume already with CC
            if (str_starts_with($digits, '91') && strlen($digits) === 12) {
                return '+' . $digits;
            }

            // If 10-digit local mobile => prefix +91
            if (strlen($digits) === 10) {
                return '+91' . $digits;
            }
        }

        // Fallback: just add +
        return '+' . $digits;
    }

    /** Basic Indian mobile validation: 10 digits, starts 6–9 */
    public static function isValidIndianMobile(string $raw): bool
    {
        $d = preg_replace('/\D+/', '', $raw ?? '');
        return (strlen($d) === 10) && preg_match('/^[6-9]\d{9}$/', $d);
    }
}
