<?php

$menuItems = [
    [
        'items' => [
            [
                'title' => 'Dashboard',
                'icon' => 'ri-home-smile-line',
                'route' => 'dashboard',
                'active' => 'dashboard',
                'submenu' => [],
            ],
        ],
    ],
    [
        'header' => 'Reports',
        'items' => [
            [
                'title' => 'My Child\'s Development',
                'icon' => 'ri-file-user-line',
                'route' => 'mychild-reports.index',
                'active' => 'mychild-reports.*',
                'submenu' => [],
            ],
        ],
    ],
    [
        'header' => 'Settings',
        'items' => [
            [
                'title' => 'Profile',
                'icon' => 'ri-settings-4-line',
                'route' => 'profile.edit',
                'active' => 'profile.*',
                'submenu' => [],
            ],
        ],
    ],
];

return $menuItems;
