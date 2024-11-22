<?php
namespace App\Enums;

enum PermissionEnum: string
{
    case users = 'users';
    case roles = 'roles';
    case pages = 'pages';
}
