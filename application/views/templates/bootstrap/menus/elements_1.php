<script>
    var cf_index = {
        users_explore: [0,-1],
        usuarios_nuevo: [0,-1],
        usuarios_importar: [0,-1],
        usuarios_info: [0,-1],
        documents_laws: [1,0],
        app_start: [1,1],
        documents_decrets: [1,2],
        admin_acl: [2,0],
        items_manage: [2,1],
        lugares_sublugares: [2,2]
    };
    
    var navbar_elements = [
            {
                text: 'Usuarios',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-user',
                cf: 'users/explore',
                submenu: false,
                subelements: []
            },
            {
                text: 'Documentos',
                active: false,
                style: '',
                icon: 'fa fa-fw fa-book',
                cf: 'documents/explore',
                submenu: false,
                subelements: []
            },
            {
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
                    }
                ]
            }
        ];
</script>