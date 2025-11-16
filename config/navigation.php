<?php

return [
    'items' => [
        [
            'label' => 'Inicio',
            'route' => 'home',
            'icon' => 'bi-stars',
            'active' => 'home',
            'description' => 'Accede a la vista general y accesos rápidos del sistema.',
        ],
        [
            'label' => 'Categorías',
            'route' => 'web.categories.index',
            'icon' => 'bi-collection',
            'active' => 'web.categories.*',
            'description' => 'Administra las colecciones que agrupan a los libros.',
        ],
        [
            'label' => 'Libros',
            'route' => 'web.books.index',
            'icon' => 'bi-journal-richtext',
            'active' => 'web.books.*',
            'description' => 'Gestiona el catálogo completo de títulos disponibles.',
        ],
        [
            'label' => 'Clientes',
            'route' => 'web.clients.index',
            'icon' => 'bi-people',
            'active' => 'web.clients.*',
            'description' => 'Mantén al día los datos de tus lectores frecuentes.',
        ],
        [
            'label' => 'Reservas',
            'route' => 'web.reservations.index',
            'icon' => 'bi-calendar2-check',
            'active' => 'web.reservations.*',
            'description' => 'Da seguimiento a las reservas activas y completadas.',
        ],
        [
            'label' => 'Devoluciones',
            'route' => 'web.returns.index',
            'icon' => 'bi-arrow-counterclockwise',
            'active' => 'web.returns.*',
            'description' => 'Controla los retornos y su impacto en inventario.',
        ],
        [
            'label' => 'Ingresos',
            'route' => 'web.inventory-records.index',
            'icon' => 'bi-box',
            'active' => 'web.inventory-records.*',
            'description' => 'Registra entradas de inventario y proveedores.',
        ],
        [
            'label' => 'Movimientos',
            'route' => 'web.movements.index',
            'icon' => 'bi-shuffle',
            'active' => 'web.movements.*',
            'description' => 'Consulta el historial de movimientos de inventario.',
        ],
        [
            'label' => 'Ventas',
            'route' => 'web.sales.index',
            'icon' => 'bi-receipt-cutoff',
            'active' => 'web.sales.*',
            'description' => 'Registra ventas al público y genera tickets PDF.',
        ],
    ],
];
