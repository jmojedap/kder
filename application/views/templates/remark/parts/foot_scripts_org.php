        <!-- Core  -->
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
        
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/popper-js/umd/popper.min.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/bootstrap/bootstrap.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/animsition/animsition.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/mousewheel/jquery.mousewheel.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/asscrollable/jquery-asScrollable.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>        

        <!-- Plugins -->
        <script src="<?php //echo URL_RESOURCES ?>templates/remark/global/vendor/switchery/switchery.js"></script>
        <script src="<?php //echo URL_RESOURCES ?>templates/remark/global/vendor/intro-js/intro.js"></script>
        <script src="<?php //echo URL_RESOURCES ?>templates/remark/global/vendor/screenfull/screenfull.js"></script>
        <script src="<?php //echo URL_RESOURCES ?>templates/remark/global/vendor/slidepanel/jquery-slidePanel.js"></script>

        <!-- Scripts -->
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/js/Component.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/js/Plugin.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/js/Base.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/js/Config.js"></script>

        <script src="<?php echo URL_RESOURCES ?>templates/remark/base/assets/js/Section/Menubar.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/base/assets/js/Section/GridMenu.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/base/assets/js/Section/Sidebar.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/base/assets/js/Section/PageAside.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/base/assets/js/Plugin/menu.js"></script>

        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/js/config/colors.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/base/assets/js/config/tour.js"></script>
        <script>Config.set('assets', '../../assets');</script>

        <!-- Page -->
        <script src="<?php echo URL_RESOURCES ?>templates/remark/base/assets/js/Site.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/js/Plugin/asscrollable.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/js/Plugin/slidepanel.js"></script>
        <script src="<?php echo URL_RESOURCES ?>templates/remark/global/js/Plugin/switchery.js"></script>

        <script>
            (function (document, window, $) {
                'use strict';

                var Site = window.Site;
                $(document).ready(function () {
                    Site.run();
                });
            })(document, window, jQuery);
        </script>