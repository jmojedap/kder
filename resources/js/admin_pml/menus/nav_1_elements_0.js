var nav_1_elements = [
    {
        text: 'Usuarios',
        active: false,
        icon: 'fa fa-user',
        cf: 'users/explore',
        subelements: [],
        sections: ['users/explore', 'users/profile', 'users/import', 'users/add', 'users/notes']
    },
    {
        text: 'Instituciones',
        active: false,
        style: '',
        icon: 'fa fa-fw fa-school',
        cf: 'institutions/explore',
        submenu: false,
        subelements: [],
        sections: ['institutions/explore', 'institutions/info', 'institutions/add', 'institutions/edit']
    },
    {
        text: 'Grupos',
        active: false,
        style: '',
        icon: 'fa fa-fw fa-users',
        cf: 'groups/explore',
        submenu: false,
        subelements: [],
        sections: ['groups/explore', 'groups/info', 'groups/add', 'groups/edit', 'groups/students']
    },
    {
        text: 'Pagos',
        active: false,
        icon: 'fas fa-fw fa-dollar-sign',
        cf: '',
        subelements: [
            {
                text: 'Cobros',
                active: false,
                icon: 'fas fa-fw fa-hand-holding-usd',
                cf: 'charges/explore',
                sections: ['charges/explore', 'charges/add', 'charges/info', 'charges/edit', 'charges/groups']
            },
            {
                text: 'Pagos',
                active: false,
                icon: 'fas fa-fw fa-dollar-sign',
                cf: 'payments/explore',
                sections: ['payments/explore']
            }
        ],
        sections: ['payments/explore']
    },
    {
        text: 'Data',
        active: false,
        icon: 'fa fa-table',
        cf: '',
        subelements: [
            {
                text: 'Posts',
                active: false,
                icon: 'fa fa-newspaper',
                cf: 'posts/explore',
                sections: ['posts/explore', 'posts/import', 'posts/import_e', 'posts/add', 'posts/info', 'posts/edit', 'posts/image']
            },
            {
                text: 'Archivos',
                active: false,
                icon: 'fa fa-file',
                cf: 'files/explore',
                sections: ['files/explore', 'files/add', 'files/edit', 'files/tags', 'files/cropping']
            },
            {
                text: 'Eventos',
                active: false,
                icon: 'fa fa-calendar',
                cf: 'events/explore',
                sections: []
            }
        ],
        sections: []
    },
    {
        text: 'Ajustes',
        active: false,
        icon: 'fa fa-cog',
        cf: '',
        subelements: [
            {
                text: 'General',
                active: false,
                icon: 'fa fa-sliders-h',
                cf: 'admin/acl',
                sections: ['admin/acl', 'admin/options', 'admin/colors']
            },
            {
                text: 'Ítems',
                active: false,
                icon: 'fa fa-bars',
                cf: 'items/manage',
                sections: ['items/manage', 'items/import', 'items/import_e']
            },
            {
                text: 'Base de datos',
                active: false,
                icon: 'fa fa-database',
                cf: 'sync/panel',
                sections: []
            }
        ],
        sections: []
    }
];