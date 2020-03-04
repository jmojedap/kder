<script>
    var nav_1_elements = [
            {
                id: 'nav_1_students',
                text: 'Estudiantes',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-user-graduate',
                cf: 'students/explore',
                submenu: false,
                subelements: []
            },
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
                id: 'nav_1_grupos',
                text: 'Grupos',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-users',
                cf: 'groups/explore',
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
                id: 'nav_1_institution',
                text: 'Institución',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-shield-alt',
                cf: 'institutions/my_institutions',
                submenu: false,
                subelements: []
            }
        ];
</script>