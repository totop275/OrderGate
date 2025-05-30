<?php

return [
    [
        'label' => 'Dashboard',
        'icon' => 'bx bxs-home',
        'route' => 'dashboard',
        'permissions' => ['dashboard'],
    ],
    [
        'type' => 'section_header',
        'label' => 'Orders',
    ],
    [
        'label' => 'Create new order',
        'icon' => 'bx bx-plus',
        'route' => 'orders.create',
        'permissions' => ['orders.create'],
    ],
    [
        'label' => 'Orders',
        'icon' => 'bx bxs-cart',
        'route' => 'orders.index',
        'permissions' => ['orders.browse'],
    ],
    [
        'type' => 'section_header',
        'label' => 'Master Data',
    ],
    [
        'label' => 'Products',
        'icon' => 'bx bxs-box',
        'route' => 'products.index',
        'permissions' => ['products.browse'],
    ],
    [
        'label' => 'Customers',
        'icon' => 'bx bxs-user-detail',
        'route' => 'customers.index',
        'permissions' => ['customers.browse'],
    ],
    [
        'type' => 'section_header',
        'label' => 'Settings',
    ],
    [
        'label' => 'Users',
        'icon' => 'bx bxs-user',
        'route' => 'users.index',
        'permissions' => ['users.browse'],
    ],
    [
        'label' => 'Roles',
        'icon' => 'bx bxs-user-account',
        'route' => 'roles.index',
        'permissions' => ['roles.browse'],
    ],
];