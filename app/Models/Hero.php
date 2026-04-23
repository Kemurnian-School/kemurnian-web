<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Hero extends Model
{
    protected $fillable = [
        'header_text',
        'button_text',
        'href',
        'desktop_image',
        'tablet_image',
        'mobile_image',
        'order',
    ];
    /**
     * Accessor: Append the domain to the desktop image path.
     */
    protected function desktopImage(): Attribute
    {
        return Attribute::make(
            // Uses config('app.url') instead of env() to prevent issues with config:cache
            get: fn($value) => $value ? config('app.url') . $value : null,
        );
    }

    /**
     * Accessor: Append the domain to the tablet image path.
     */
    protected function tabletImage(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? config('app.url') . $value : null,
        );
    }

    /**
     * Accessor: Append the domain to the mobile image path.
     */
    protected function mobileImage(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? config('app.url') . $value : null,
        );
    }
}
