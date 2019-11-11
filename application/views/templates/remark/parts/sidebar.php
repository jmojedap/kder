<?php $this->load->view('app/menus/elements_' . $this->session->userdata('role')) ?>

<div class="site-menubar" id="nav_1">
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu" style="padding-top: 10px;">
                    <li
                        class="site-menu-item"
                        v-for="(element, i) in elements"
                        v-bind:class="{ 'has-sub': element.submenu, 'active': element.active }"
                        >
                        <a
                            class="nav-link nav_1_link"
                            href="javascript:void(0)"
                            v-bind:id="element.id"
                            v-bind:data-cf="element.cf"
                            >
                            <i class="site-menu-icon" v-bind:class="element.icon" aria-hidde="true"></i>
                            <span class="site-menu-title">
                                {{ element.text }}
                            </span>
                            <span class="site-menu-arrow" v-if="element.submenu"></span>
                        </a>
                        <ul class="site-menu-sub" v-if="element.submenu">
                            <li class="site-menu-item"
                                v-for="(subelement, j) in element.subelements"
                                v-bind:data-parent_id="element.id"
                                v-bind:data-cf="subelement.cf"
                                v-bind:class="{ 'active': subelement.active }"
                                >
                                <a
                                    class="animsition-link nav_1_link"
                                    href="javascript:void(0)"
                                    v-bind:data-parent_id="element.id"
                                    v-bind:data-cf="subelement.cf"
                                >
                                    <span class="site-menu-title">
                                        <i class="site-menu-icon" v-bind:class="subelement.icon"></i>
                                        {{ subelement.text }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>            
        </div>
    </div>    
</div>

<script>
    $(document).ready(function()
    {
        $('.nav_1_link').click(function()
        {
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

<script>
    new Vue({
        el: '#nav_1',
        data: {
            elements: nav_1_elements
        }
    });
</script>