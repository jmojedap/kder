<script>
    var navbar_elements = [
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