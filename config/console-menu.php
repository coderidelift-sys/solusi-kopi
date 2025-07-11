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
                'roles' => ['admin', 'kasir', 'user'],
            ],
        ],
    ],
    [
        'header' => 'Manajemen Pengguna',
        'items' => [
            [
                'title' => 'Pengguna',
                'icon' => 'ri-user-line',
                'route' => 'users.index',
                'active' => 'users.*',
                'submenu' => [],
                'roles' => ['admin'],
            ],
        ],
    ],
    [
        'header' => 'Manajemen Data',
        'items' => [
            [
                'title' => 'Outlet',
                'icon' => 'ri-store-line',
                'route' => 'outlets.index',
                'active' => 'outlets.*',
                'submenu' => [],
                'roles' => ['admin'],
            ],
            [
                'title' => 'Meja',
                'icon' => 'ri-table-line',
                'route' => 'tables.index',
                'active' => 'tables.*',
                'submenu' => [],
                'roles' => ['admin'],
            ],
            [
                'title' => 'Kategori',
                'icon' => 'ri-bookmark-line',
                'route' => 'categories.index',
                'active' => 'categories.*',
                'submenu' => [],
                'roles' => ['admin'],
            ],
            [
                'title' => 'Produk',
                'icon' => 'ri-restaurant-line',
                'route' => 'products.index',
                'active' => 'products.*',
                'submenu' => [],
                'roles' => ['admin', 'kasir'],
            ],
            [
                'title' => 'Promosi',
                'icon' => 'ri-percent-line',
                'route' => 'promotions.index',
                'active' => 'promotions.*',
                'submenu' => [],
                'roles' => ['admin', 'kasir'],
            ],
        ],
    ],
    [
        'header' => 'Manajemen Order',
        'items' => [
            [
                'title' => 'Daftar Order',
                'icon' => 'ri-shopping-cart-line',
                'route' => 'console.orders.index',
                'active' => 'console.orders.*',
                'submenu' => [],
                'roles' => ['admin', 'kasir'],
            ],
        ],
    ],
    [
        'header' => 'Laporan & Analytics',
        'items' => [
            [
                'title' => 'Dashboard Reporting',
                'icon' => 'ri-bar-chart-line',
                'route' => 'console.reporting.index',
                'active' => 'console.reporting.*',
                'submenu' => [],
                'roles' => ['admin', 'kasir'],
            ],
        ],
    ],
    [
        'header' => 'Pengaturan',
        'items' => [
            [
                'title' => 'Profil',
                'icon' => 'ri-settings-4-line',
                'route' => 'profile.edit',
                'active' => 'profile.*',
                'submenu' => [],
                'roles' => ['admin', 'kasir', 'user'],
            ],
        ],
    ],
];

return $menuItems;
