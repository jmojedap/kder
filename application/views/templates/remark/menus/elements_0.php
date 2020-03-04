<script>
    var nav_1_elements = [
            {
                id: 'nav_1_usuarios',
                text: 'Usuarios',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-user',
                cf: 'users/explore',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_institutions',
                text: 'Instituciones',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-school',
                cf: 'institutions/explore',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_groups',
                text: 'Grupos',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-users',
                cf: 'groups/explore',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_notes',
                text: 'Anotaciones',
                active: false,
                style: '',
                icon: 'far fa-fw fa-sticky-note',
                cf: 'notes/explore',
                submenu: false,
                subelements: []
            },
            {
                id: 'nav_1_payments',
                text: 'Pagos',
                active: false,
                style: '',
                icon: 'fas fa-fw fa-dollar-sign',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Cobros',
                        active: false,
                        icon: 'fas fa-fw fa-hand-holding-usd',
                        cf: 'charges/explore'
                    },
                    {
                        text: 'Pagos',
                        active: false,
                        icon: 'fas fa-fw fa-dollar-sign',
                        cf: 'payments/explore'
                    }
                ]
            },
            {
                id: 'nav_1_datos',
                text: 'Datos',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-table',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'Posts',
                        active: false,
                        icon: 'far fa-newspaper',
                        cf: 'posts/explore'
                    },
                    {
                        text: 'Archivos',
                        active: false,
                        icon: 'fa fa-fw fa-file',
                        cf: 'files/explore'
                    },
                    {
                        text: 'Eventos',
                        active: false,
                        icon: 'fa fa-fw fa-calendar',
                        cf: 'events/explore'
                    }
                ]
            },
            {
                id: 'nav_1_ajustes',
                text: 'Ajustes',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-sliders-h',
                cf: '',
                submenu: true,
                subelements: [
                    {
                        text: 'General',
                        active: false,
                        icon: 'fa fa-fw fa-cogs',
                        cf: 'admin/acl'
                    },
                    {
                        text: 'Ítems',
                        active: false,
                        icon: 'fa fa-fw fa-bars',
                        cf: 'items/manage'
                    },
                    {
                        text: 'Base de datos',
                        active: false,
                        icon: 'fa fa-fw fa-database',
                        cf: 'sync/panel'
                    }
                ]
            }
        ];
</script>