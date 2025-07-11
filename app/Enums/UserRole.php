<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ADMIN()
 * @method static static USER()
 */
final class UserRole extends Enum
{
    const ADMIN = 'Admin';
    const USER = 'User';
}
