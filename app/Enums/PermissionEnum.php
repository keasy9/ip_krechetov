<?php
namespace App\Enums;

enum PermissionEnum: string
{
    case users = 'users';
    case roles = 'roles';
    case ownRole = 'roles.own';
    case pages = 'content.pages';
    case menu = 'content.menu';
    case settings = 'settings';
}
