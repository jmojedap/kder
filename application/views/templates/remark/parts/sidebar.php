<style>
    .site-menu>.site-menu-item.active>a {
        border-left: 4px solid #FF4845;
    }

    .site-menu .site-menu-sub .site-menu-item.active>a{
        border-left: 4px solid #FF4845;
    }

    .site-menu>.site-menu-item.open>a{
        border-left: 4px solid #B53C3A;
    }
</style>

<?php $this->load->view('templates/remark/menus/elements_' . $this->session->userdata('role')) ?>

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
                            v-on:click="load_sections(i)"
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
                                    class="animsition-link nav_1_sub"
                                    href="javascript:void(0)"
                                    v-on:click="load_sections_sub(i,j)"
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
    new Vue({
        el: '#nav_1',
        data: {
            elements: nav_1_elements
        },
        methods: {
            load_sections: function(i){
                app_cf = this.elements[i].cf;
                
                console.log(app_cf);

                if ( this.elements[i].submenu ) {
                    console.log('ABRIENDO');
                } else {
                    load_sections('nav_1');
                    $('.nav_1_link').removeClass('active');
                    $('.site-menu-item').removeClass('active');
                    $('.has-sub').removeClass('open');
                }
            },
            load_sections_sub: function(i,j){
                $('.site-menu-item').removeClass('active');
                app_cf = this.elements[i].subelements[j].cf;
                console.log(app_cf);
                load_sections('nav_1');
            }   
        }
    });
</script>