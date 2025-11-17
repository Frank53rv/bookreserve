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
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'bi-speedometer2',
            'active' => 'dashboard',
            'description' => 'Métricas en tiempo real y estadísticas del negocio.',
        ],
        [
            'label' => 'Catálogo',
            'icon' => 'bi-bookshelf',
            'active' => 'web.categories.*|web.books.*',
            'dropdown' => [
                [
                    'label' => 'Libros',
                    'route' => 'web.books.index',
                    'icon' => 'bi-journal-richtext',
                    'description' => 'Gestiona el catálogo completo de títulos disponibles.',
                ],
                [
                    'label' => 'Categorías',
                    'route' => 'web.categories.index',
                    'icon' => 'bi-collection',
                    'description' => 'Administra las colecciones que agrupan a los libros.',
                ],
            ],
        ],
        [
            'label' => 'Clientes',
            'route' => 'web.clients.index',
            'icon' => 'bi-people',
            'active' => 'web.clients.*',
            'description' => 'Mantén al día los datos de tus lectores frecuentes.',
        ],
        [
            'label' => 'Operaciones',
            'icon' => 'bi-stack',
            'active' => 'web.reservations.*|web.returns.*|web.sales.*',
            'dropdown' => [
                [
                    'label' => 'Reservas',
                    'route' => 'web.reservations.index',
                    'icon' => 'bi-calendar2-check',
                    'description' => 'Da seguimiento a las reservas activas y completadas.',
                ],
                [
                    'label' => 'Devoluciones',
                    'route' => 'web.returns.index',
                    'icon' => 'bi-arrow-counterclockwise',
                    'description' => 'Controla los retornos y su impacto en inventario.',
                ],
                [
                    'label' => 'Ventas',
                    'route' => 'web.sales.index',
                    'icon' => 'bi-receipt-cutoff',
                    'description' => 'Registra ventas al público y genera tickets PDF.',
                ],
            ],
        ],
        [
            'label' => 'Inventario',
            'icon' => 'bi-archive',
            'active' => 'web.inventory-records.*|web.movements.*',
            'dropdown' => [
                [
                    'label' => 'Ingresos',
                    'route' => 'web.inventory-records.index',
                    'icon' => 'bi-box-seam',
                    'description' => 'Registra entradas de inventario y proveedores.',
                ],
                [
                    'label' => 'Movimientos',
                    'route' => 'web.movements.index',
                    'icon' => 'bi-shuffle',
                    'description' => 'Consulta el historial de movimientos de inventario.',
                ],
            ],
        ],
    ],
];
