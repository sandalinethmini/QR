<style>
	.form-row>.col, .form-row>[class*=col-] {
    padding-right: 15px;
}
.lblStrong {
    font-weight:bold;
	
}

table#recordTable tbody td{
	font-size:13px;
	}
	
	table#recordTable thead th
	{
		font-size:14px;
	}
  .spanError {
      color: red;
      font-size: 14px;
  }
.field-height{	padding-top:0px;height:24px; font-size:12px	}
</style>

    <div class="content p-4">
        

      
		
        <div class="card mb-4" >
        
            <div class="card-header bg-blue font-weight-bold">
                Upload Excel
            </div>
            <div class="card-body">
            
            <form action="index.php/Log_import/upload" method="post" enctype="multipart/form-data">
            	<div class="row col-md-12">
                    <div class="col-md-2">
                        <label for="file" class="lblStrong">Choose Excel File:</label>
                    </div>
                    <div class="col-md-4">
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx, .xls, .csv" required>
                        <?php echo form_error('file'); ?> 
                    </div>
                    <div class="col-md-3">
						<button type="submit" class="btn btn-info field-height">Upload File</button>
                    </div>
                </div>
            
            </form>
                
               
					 
                   	
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
        <button type="button" class="btn btn-blue" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


							 
	 </div>
	  </div>
  
<script>
$(document).ready(function () {

  load_data();


		DataTable();

    });
	
  <?php 

	if($this->session->flashdata('fail'))
	{?>	
		 $("#successMsg").html("<?php echo $this->session->flashdata('fail');?>");
		 $(".bd-success-modal-sm").modal("show");
					
 <?php	}?>	
	
 <?php 
	if( $this->session->flashdata('info'))
	{?>	
		 $("#successMsg").html("<?php echo $this->session->flashdata('info');?>");
		 $(".bd-success-modal-sm").modal("show");
					
 <?php	}?>
	
function load_data()
 {
  $.ajax({
   url:"<?php echo base_url(); ?>log_import/fetch",
   method:"POST",
   success:function(data){
    $('#customer_data').html(data);
   }
  })
 }

 $('#import_form').on('submit', function(event){
  event.preventDefault();
  $.ajax({
   url:"<?php echo base_url(); ?>log_import/import",
   method:"POST",
   data:new FormData(this),
   contentType:false,
   cache:false,
   processData:false,
   success:function(data){
    $('#file').val('');
    load_data();
    alert(data);
   }
  })
 });





function DataTable()
	{
		 $('#recordTable').DataTable({ 
		searchHighlight: true,
        "processing": true, 
        "serverSide": true,
        "order": [], 

        "ajax": {
            "url": 'index.php/OpenTicket/recordlist',
            "type": "POST"
        },

		
		"columnDefs": 
		[
		{
			"targets": [0,2,3,4,5],
			"orderable": false
			},
			{ className: 'text-center', targets: [0,1,4,5] },
			
		{
			"targets": 5,
			"data": "status",
			"render": function (data, type, row, meta) {
				
				if(row[6]==0 || row[6]==5){
					return '<button  onclick="uploadDocument(\''+row[5]+'\');" class="btn btn-icon btn-pill btn-primary btn-sm"  title="Upload/ View"><i class="fa fa-fw fa-upload"></i></button>';	
				}
				else if(row[6]==1 || row[6]==4 ){
					return '<button  onclick="uploadDocument(\''+row[5]+'\');" class="btn btn-icon btn-pill btn-success btn-sm"  title="View"><i class="fa fa-fw fa-search"></i></button>';	
				}
				else{
					return '';	
				}
					
					
				
				
}
		}
         
        ]
		});
	}	


function uploadDocument(recordID){
	
	$('#recordID').val(recordID);
	$("#upload_model").modal("show");
}

function clearData()
	 {
		$('#form_application input[type="text"]').val('');
		$('#form_application input[type="text"]').attr("value", "");
	 }

	
</script>