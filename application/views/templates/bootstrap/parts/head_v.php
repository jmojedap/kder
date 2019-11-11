<title><?php echo $head_title ?></title>
        
        <link rel="shortcut icon" href="<?php echo URL_IMG ?>app/favicon.png"> 
        
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <script src="<?php echo base_url('resources/js/pcrn.js') ?>"></script>

        <?php $this->load->view('assets/bootstrap'); ?>
        
        <link rel="stylesheet" href="<?php echo base_url('resources/templates/bootstrap/css/main.css') ?>">
        <!--Titles font-->
        <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Ubuntu:500,300'>

        <!-- Google Analytics -->
        <?php //$this->load->view('assets/google_analytics') ?>
        
        <!--Icons font-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        
        <!-- Vue.js -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
        
        <?php $this->load->view('assets/toastr'); ?>