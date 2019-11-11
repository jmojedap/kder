<ul id="nav_1" class="navbar-nav mr-auto">
    <li
        class="nav-item"
        v-for="(element, i) in elements"
        v-bind:class="{ dropdown: element.submenu }"
        >
        <a
            class="nav-link nav_1_link"
            href="#"
            data-toggle="dropdown"
            v-bind:id="element.id"
            v-bind:data-cf="element.cf"
            v-bind:class="{ 'dropdown-toogle': element.submenu, 'active': element.active }"
            >
            <i v-bind:class="element.icon"></i>
            {{ element.text }}
            <i class="fa fa-caret-down" v-if="element.submenu"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown" v-if="element.submenu">
            <a
                class="dropdown-item nav_1_link"
                href="#"
                v-for="(subelement, j) in element.subelements"
                v-bind:data-parent_id="element.id"
                v-bind:data-cf="subelement.cf"
                v-bind:class="{ 'active': subelement.active }"
               >
                {{ subelement.text }}
            </a>
        </div>
    </li>
</ul>

<script>
    $(document).ready(function(){
        $('.nav_1_link').click(function(){
            if ( $(this).data('cf').length > 0 )
            {
                app_cf = $(this).data('cf');
                load_sections('nav_1');

                $('.nav_1_link').removeClass('active');
                $(this).addClass('active');
                
                if ( $(this).data('parent_id') ) 
                {
                    var parent_id = '#' + $(this).data('parent_id');
                    $(parent_id).addClass('active');
                }
                
            }
        });
    });
</script>

<?php
    //Script with variables for construction of navbar menu
    $view_menu_navbar = 'templates/bootstrap/menus/elements_' . $this->session->userdata('role');
    $this->load->view($view_menu_navbar);
?>

<script>
    new Vue({
        el: '#nav_1',
        data: {
            elements: navbar_elements
        }
    });
</script>