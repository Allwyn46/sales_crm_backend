<?php

namespace App\Enums;

enum LeadSource: string
{
    case Website = 'website';
    case Phone = 'phone';
    case Email = 'email';
    case Referral = 'referral';
    case WalkIn = 'walk_in';
    case SocialMedia = 'social_media';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website',
            self::Phone => 'Phone',
            self::Email => 'Email',
            self::Referral => 'Referral',
            self::WalkIn => 'Walk-in',
            self::SocialMedia => 'Social Media',
            self::Other => 'Other',
        };
    }
}
