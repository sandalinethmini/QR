

    <div class="content p-4">
        

        <!--<h2 class="mb-4">Cards</h2>-->
		<form id="form_application" method="post" action="<?php echo base_url().'index.php/masterData/UserDetails/saveRecord'?>">
        <div class="card mb-4">
            <div class="card-header bg-white font-weight-bold">
                User Management
            </div>
            <div class="card-body">
                <div class="col-md-12 col-md-auto">
                    	
                        <div class="form-row mb-3 ">
                         	<div class="col-md-4 ">
                         		<strong><u>User Details</u></strong>
                                </div>
                         </div>
                          
                           <div class="form-row">
						   
                           <div class="col-md-2 mb-3">
                                <!--<label for="validationServer01">User Name</label>-->
                                <input type="text" class="form-control " id="txtUserCode" name="txtUserCode" placeholder="-- Code --" value="<?php echo set_value('txtUserCode'); ?>" required>
                                <?php echo form_error('txtUserCode');  ?>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <!--<label for="validationServer01">User Name</label>-->
                                <input type="text" class="form-control " id="txtEmpNo" name="txtEmpNo" placeholder="-- Employee No --" value="<?php echo set_value('txtEmpNo'); ?>" required>
                                <input type="hidden" id="txtUserId" name="txtUserId" value="<?php echo set_value('txtEmpNo'); ?>" />
                                <?php echo form_error('txtEmpNo');  ?>
                            </div>
                           
                            <?php /*?><div class="col-md-2 mb-3">
                                <!--<label for="validationServer01">User Name</label>-->
                                <input type="text" class="form-control " id="txtUserName" name="txtUserName" placeholder="-- User Name --" value="<?php echo set_value('txtUserName'); ?>" required>
                                <input type="hidden" id="txtUserId" name="txtUserId" value="<?php echo set_value('txtUserId'); ?>" />
                                <?php echo form_error('txtUserName');  ?>
                            </div><?php */?>
                            
                            <div class="col-md-4 mb-3">
                                <!--<label for="validationServer02">Full Name</label>-->
                                <input type="text" class="form-control " id="txtFullName" name="txtFullName" placeholder="-- Full Name --" value="<?php echo set_value('txtFullName'); ?>" required>
                                <?php echo form_error('txtFullName');  ?>
                            </div>
                            
                            
                          
                        </div>
                        
                        <div class="form-row mb-3 ">
                         	<div class="col-md-4 ">
                         		<strong><u>Page Access Permission</u></strong>
                                </div>
                         </div>
                        
                        <div class="form-row">
                        
                        	<?php 
										
										
											$count = 1;
										
										if(isset($pages))
										{
											
										
										
										
											foreach($pages as $page){
												//echo form_checkbox('cover[]', $cover->ba_covers_id, set_checkbox('cover', $cover->ba_covers_id));
												?>
												
												<div class="form-group col-md-4">
													<div class="row">
														<div class="col-md-1"><?php echo form_checkbox('page[]', $page->page_id, set_checkbox('page', $page->page_id),'id='.$page->page_id);?></div>
														<div class="col-md-10"> <label class="form-check-label" for="exampleCheck1"><?php echo $page->page_viewname; ?></label></div>
														
													</div>
												</div>
												<?php
										
											}
										}
										?>
                        
                        </div>
                        

                    
                </div>

            </div>
            <div class="card-footer bg-white card-auto button-right">
            	<div class="float-right">
                	 <button class="btn btn-success" type="submit">Save</button> <button class="btn btn-info" id="btnClear" type="button">Clear</button>
                </div>
               
            </div>
            </form>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <table id="recordTable" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>User Name</th>
                        <th>Full Name</th>
                        
                        <th class="action">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-success-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content"> 
      <div class="modal-header" style="background-color: #00a8b8;border-radius: 5px;padding: 10px;color:#fff">
        <h5 class="modal-title">Information !</h5>
      </div>
      <div class="modal-body">
        <p id="successMsg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-bule" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
    $(document).ready(function () {
       DataTable();
	   $( "#btnClear" ).click(function() {
		  	$('#form_application input[type="text"],select').val('');
			$('input[type=checkbox]').prop('checked',false);
			$('#form_application input[type=hidden]').val('');
			$('.text-danger').html("");
			$("#txtEmpNo").prop('disabled', false);
			$("#txtUserCode").prop('disabled', false); 
			//$('#cmbSubLocation').html('<option>-- Select Office --</option>');
		});
		
		$( "#cmbLocationCode" ).change(function() {
		  	loadSubLocation();
		});
    });
	
	 <?php 

		if($this->session->flashdata('fail'))
		{?>	
			 $("#successMsg").html("<?php echo $this->session->flashdata('fail');?>");
			 $(".bd-success-modal-sm").modal("show");
						
	 <?php	}?>	
		
	 <?php 
		if($this->session->flashdata('success'))
		{?>	
			 $("#successMsg").html("<?php echo $this->session->flashdata('success');?>");
			 $(".bd-success-modal-sm").modal("show");
						
	 <?php	}?>	
	
	
	function DataTable()
	{
		 $('#recordTable').DataTable({ 
		
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": 'index.php/masterData/UserDetails/recordlist',
            "type": "POST"
        },
		
		"columnDefs": 
		[
			{
						"targets": [ 0,3 ], //first column / numbering column
						"orderable": false, //set not orderable
			 },
			 {
					"targets": [ 3 ], //first column / numbering column
					className: 'text-center', //set not orderable
				},
        	 { 
			"targets": 3,
			"data": "cstatus",
			"render": function ( data, type, row, meta ) 
			{
				
				if(row[3]=='0')
				{
					return '<button  onclick="updateRecord('+row[4]+');" class="btn btn-icon btn-pill btn-primary btn-sm"  title="Edit"><i class="fa fa-fw fa-edit"></i></button> <button  onclick="changeStatus(\''+row[4]+'\',1);" class="btn btn-icon btn-pill btn-danger btn-sm"  title="Click to Activate"><i class="fa fa-fw fa-lock"></i></button>';
				}
				else
				{
					return '<button  onclick="updateRecord(\''+row[4]+'\');" class="btn btn-icon btn-pill btn-primary btn-sm"  title="Edit"><i class="fa fa-fw fa-edit"></i></button> <button onclick="changeStatus('+row[4]+',0);" class="btn btn-icon btn-pill btn-success btn-sm"  title="Click to De-Activate"><i class="fa fa-fw fa-check"></i></button> <button  onclick="changePassword(\''+row[4]+'\',\''+row[5]+'\');" class="btn btn-icon btn-pill btn-info btn- btn-sm"  title="Password Reset"><i class="fa fa-fw  fa-redo"></i></button>';
				}
			}// end of rendr function
		 }
        ]// end of columnDefs
		});// end of Datatable
	}
	
	function updateRecord(recordId)
	{
		clearData();
		
		var locationCode = "";
		
		$.ajax({type: 'POST',dataType:"JSON",	url: 'index.php/masterData/UserDetails/editRecord',data:{'recordId':recordId},	async : false,	success: function (html) 
		{	
			$('#txtEmpNo').val(html.jsonData.branch_email);
			$('#txtFullName').val(html.jsonData.branch_name);
			$('#txtUserCode').val(html.jsonData.branch_code); 
			$("#txtUserCode").prop('disabled', true); 
			/*subLocationCode = html.jsonData.system_user_area_id; 
			locationCode = html.jsonData.area_code;*/
			$('#txtUserId').val(html.jsonData.branch_id);
			$("#txtEmpNo").prop('disabled', true);
		 },    error: function(html){  }	});	
		 
		/* $.ajax({type: "POST",  async : false,  dataType: "json",	url: "index.php/masterData/UserDetails/getSubArea",data:{'areaCode':locationCode},	success: function (html) {	
			$.each(html, function(i, option) {
				
			 $('#cmbSubLocation').append($('<option/>').attr("value", option.area_id).text(option.area_sub_description));}); 
		 },  error: function(html){  }	});	*/
		 
		/* $('#cmbSubLocation').val(subLocationCode);  */  
		 
		 
		  $.ajax({type: "POST", dataType: "json",	url: "index.php/masterData/UserDetails/loadPages",data:{'recordId':recordId}, async : false,	success: function (html) {	
		var inc = 0;
		
	
		//alert(data);
		$.each(html, function(i, option) {
			$("#"+html[inc].page_id).prop( "checked", true );
			inc++;	
		
	});
	},  error: function(html){  }	});
		 
		 
	}

	function changeStatus(recordId,status)
	{
		$.ajax({type: 'POST',	url: 'index.php/masterData/UserDetails/changeStatus',data:{'recordId':recordId,'status':status},	async : false,	success: function (html) {	$('#recordTable').dataTable().fnDestroy();DataTable(); $("#successMsg").html(html);
			 $(".bd-success-modal-sm").modal("show");   },    error: function(html){  }	});
	}
	
	function changePassword(recordId,userName)
	{
		$.ajax({type: 'POST',	url: 'index.php/masterData/UserDetails/changePassword',data:{'recordId':recordId,'userName':userName},	async : false,	success: function (html) {	$('#recordTable').dataTable().fnDestroy();DataTable();  $("#successMsg").html(html); $(".bd-success-modal-sm").modal("show"); },    error: function(html){  }	});
	}
	
	function clearData()
	{
		$('#form_application input[type="text"],select').val('');
		$('input[type=checkbox]').prop('checked',false);
		$('.text-danger').html("");
		$("#txtEmpNo").prop('disabled', false);
		$("#txtUserCode").prop('disabled', false); 
		$('#form_application input[type=hidden]').val('');
		//$('#cmbSubLocation').html('-- Select Office --');
	}
	
	/*function loadSubLocation()
   {
		$('#cmbSubLocation').html('');
		var areaCode = $('#cmbLocationCode').val();
		
		$.ajax({type: "POST",  async : false,  dataType: "json",	url: "index.php/masterData/UserDetails/getSubArea",data:{'areaCode':areaCode},	success: function (html) {	
			$.each(html, function(i, option) {
				
			 $('#cmbSubLocation').append($('<option/>').attr("value", option.area_id).text(option.area_sub_description));}); 
		 },  error: function(html){  }	});	   
   }*/
	
</script>



