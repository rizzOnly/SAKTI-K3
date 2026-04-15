<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    // Kirim pesan ke satu nomor
    public static function send(string $phone, string $message): bool
    {
        $phone = self::formatPhone($phone);
        if (!$phone) return false;

        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => config('services.fonnte.token')])
                ->post('https://api.fonnte.com/send', [
                    'target'  => $phone,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                Log::info("WA sent to {$phone}", ['response' => $response->json()]);
                return true;
            }

            Log::warning("WA failed to {$phone}", ['response' => $response->json()]);
            return false;

        } catch (\Exception $e) {
            Log::error("WA exception: " . $e->getMessage());
            return false;
        }
    }

    // Kirim ke banyak nomor sekaligus
    public static function sendBulk(array $phones, string $message): void
    {
        foreach ($phones as $phone) {
            self::send($phone, $message);
        }
    }

    // Format nomor: 08xx → 628xx
    private static function formatPhone(string $phone): ?string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($phone)) return null;

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }
        if (str_starts_with($phone, '62')) {
            return $phone;
        }
        return '62' . $phone;
    }
}
