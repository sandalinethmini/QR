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

.field-height{	padding-top:0px;height:24px; font-size:12px	}
</style>

    <div class="content p-4">
    
        <div class="card mb-4" >
        
            <div class="card-header bg-white font-weight-bold">
                Download Status File
            </div>
            <div class="card-body">
                <div class="col-md-12 col-md-auto">
                    <form id="form_application" method="post"  action="index.php/DownloadExcel/SalvaBatch" style="margin:responsive;" >
                   	 
					 <div class="row col-md-10">
                        <div class="col-md-3">
                            <label>From Date/Time:</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control field-height" id="txtFromDate" placeholder="" name="txtFromDate" value="<?php echo set_value('txtFromDate')?>" ><?php echo form_error('txtFromDate'); ?>
                        </div>
                    </div>
                    
                     <div class="row col-md-10">
                        <div class="col-md-3">
                            <label>To Date/Time:</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control field-height" id="txtToDate" placeholder="" name="txtToDate" value="<?php echo set_value('txtToDate')?>" ><?php echo form_error('txtToDate'); ?>
                        </div>
                    </div>
                    
                    
                        
                        <div class="row col-md-10">
    <div class="col-md-3">
        <label for="response_code">Select Response Code:</label>
    </div>
    <div class="col-md-3">
        <select class="form-control field-height" aria-label="Default select example" id="response_code" name="response_code">
            <option value=""> </option>
            <option value="All">All</option>
            <option value="1">1 - Success</option>
            <option value="2">2 - Time Out</option>
            <option value="3">3 - Reversal</option>
        </select>
        <?php echo form_error('response_code'); ?> 
    </div>
</div>
                    </div>
                    
                    <div class="row col-md-12">
                        <div class="col-md-11" > </div>
                        <div class="col-md-1" >
                            <button type="submit" class="btn btn-info button-style field-height" name="btnSubmit" id="btnBatch"   >Download</button>
                        </div>
                    </div>
  					</form>
                </div>
	 	</div>
	 </div>

 

<script>

<?php 

		if($this->session->flashdata('success'))
		{?>	
			 $("#successMsg").html("<?php echo $this->session->flashdata('success');?>");
			 $(".bd-success-modal-sm").modal("show");
						
	 <?php	}?>

    $(document).ready(function() {
		flatpickr("#txtFromDate", {enableTime: true,  dateFormat: "Y-m-d H:i"});
		flatpickr("#txtToDate", {enableTime: true,  dateFormat: "Y-m-d H:i"});

        

       loadTable();
    });

</script>



</body>
</html>
