
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
    <base href="<?php echo base_url(); ?>">
	<?php include 'headerincludes/head_includes.php'; ?>

    <title>RDB | LOS Security Document</title>
    

		
</head>
<body class="bg-light">

<nav class="navbar navbar-expand navbar-dark bg-info" >
    <a class="sidebar-toggle mr-3" href="#"><i class="fa fa-bars"></i></a>
    <a class="navbar-brand" href="index.php/Home"> Loan Secuirty Document Transfer System</a>

  <div class="navbar-collapse collapse">
        <ul class="navbar-nav ml-auto">
           <!-- <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-envelope"></i> 5</a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-bell"></i> 3</a></li>-->
            <li class="nav-item dropdown">
                <a href="#" id="dd_user" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $this->session->userdata('user_full_name'); ?></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd_user">
                    <?php if($password_reset){?><a href="index.php/userSettings/passwordReset" class="dropdown-item">Change Password</a><?php }?>
                    <a href="index.php/logout/logout_page" class="dropdown-item ">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="d-flex">
    <div class="sidebar sidebar-dark bg-dark">
        <ul class="list-unstyled">
            <li><a href="index.php/Home"><i class="fa fa-university" aria-hidden="true"></i> Home</a></li>
           <?php if($administration){?> <li>
                <a href="#sm_base" data-toggle="collapse">
                <i class="fa fa-user" aria-hidden="true"></i> Administrator
                </a>
                
        
                <ul id="sm_base" class="list-unstyled collapse">
               		<?php /*?><?php if($user_details){?><li><a href="index.php/masterData/UserDetails">User Management</a></li><?php }?><?php */?>
                    <?php if($user_accounts){?><li><a href="index.php/masterData/UserDetails">User Details</a></li><?php }?>
                    
                </ul>
            </li><?php }?>
           
            
           
			
		 	<?php if($upload_file){?><li>
                <a href="index.php/Excel_import">
                    <i class="fa fa-fw fa-upload"></i> Upload File
                </a>
            </li><?php }?>

            <?php if($upload_file){?><li>
                <a href="index.php/Log_import">
                    <i class="fa fa-fw fa-upload"></i> Upload TransactionLog
                </a>
            </li><?php }?>
<!-- AccountDetails-->
            <?php if($account_search){?><li>
                <a href="index.php/AccountDetails">
                <i class="fa fa-credit-card" aria-hidden="true"></i></i> Account Search
                </a>
            </li><?php }?>
           <?php if($download_status_file){?><li>
                <a href="index.php/DownloadExcel">
                    <i class="fa fa-fw fa-download"></i>Download Excel
                </a>
            </li><?php }?>
            <?php if($download_status_file){?><li>
                <a href="index.php/PdfReport">
                    <i class="fa fa-fw fa-download"></i>Report
                </a>
            </li><?php }?>

            <?php if($download_status_file){?><li>
                <a href="index.php/SetTimeController">
                <i class="fa fa-times" aria-hidden="true"></i> Set Time
                </a>
            </li><?php }?>
            
             

           
        </ul>


    </div>        


    