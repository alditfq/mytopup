<?php

namespace App\Services;

use App\Models\Promo;

class PromoService
{
    /**
     * Validate a promo code against a specific transaction price.
     *
     * @param string $code
     * @param float $price
     * @return Promo|null
     */
    public function validatePromo(string $code, float $price): ?Promo
    {
        $promo = Promo::where('code', strtoupper(trim($code)))
            ->where('is_active', true)
            ->first();

        if (!$promo) {
            return null;
        }

        // Check minimum transaction requirement
        if ($price < $promo->min_transaction) {
            return null;
        }

        // Check expiration date
        $now = date('Y-m-d H:i:s');
        if (!is_null($promo->expiry_date) && $promo->expiry_date < $now) {
            return null;
        }

        // Check usage limits
        if (!is_null($promo->max_uses) && $promo->uses_count >= $promo->max_uses) {
            return null;
        }

        return $promo;
    }
}
