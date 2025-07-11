<?php

$menuItems =  [
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
        'header' => 'Master Data',
        'items' => [
            [
                'title' => 'Classes',
                'icon' => 'ri-group-line',
                'route' => 'classes.index',
                'active' => 'classes.*',
                'submenu' => [],
            ],
            [
                'title' => 'Students',
                'icon' => 'ri-user-line',
                'route' => 'students.index',
                'active' => 'students.*',
                'submenu' => [],
            ],
            [
                'title' => 'Parents/Guardians',
                'icon' => 'ri-parent-line',
                'route' => 'parents.index',
                'active' => 'parents.*',
                'submenu' => [],
            ],
            [
                'title' => 'Teachers',
                'icon' => 'ri-user-star-line',
                'route' => 'teachers.index',
                'active' => 'teachers.*',
                'submenu' => [],
            ],
        ],
    ],
    [
        'header' => 'Assessments',
        'items' => [
            [
                'title' => 'Student Assessments',
                'icon' => 'ri-pencil-line',
                'route' => 'student-assessments.index',
                'active' => 'student-assessments.*',
                'submenu' => [],
            ],
        ],
    ],
    [
        'header' => 'Reports',
        'items' => [
            [
                'title' => 'Student Reports',
                'icon' => 'ri-file-chart-line',
                'route' => 'student-reports.index',
                'active' => 'student-reports.*',
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
