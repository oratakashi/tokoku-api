<?php
    include('include/config.php');
    include('include/function.php');
    
    if(!empty($_REQUEST['api']) && $_REQUEST['api']==$apikey) {
        
        if(!empty($_REQUEST['iduser'])) {
            
            $iduser = $_REQUEST['iduser'];
            
            if(!empty($_REQUEST['page'])) {
                
                $halaman = $_REQUEST['page'];
                $linkikutan = linkdefault($iduser,$apikey);
                
                if(file_exists("page/".$_REQUEST['page'].".php")) {
                    
                    include('include/header.php');
                	include("page/".$_REQUEST['page'].".php");
                    include('include/footer.php');
                    
                } else {
                    
                    include('include/header.php');
                	include("page/errors_404.php");
                    include('include/footer.php');
                    
                }
                
            } else {
                
                include('include/header.php');
                include('page/intro.php');
                include('include/footer.php');
                
            }
            
        } else {
            
            include('include/header.php');
            include('page/nouser.php');
            include('include/footer.php');
            
        }
        
    } else {
        
        include('include/header.php');
        include('page/noapi.php');
        include('include/footer.php');
        
    }
?>