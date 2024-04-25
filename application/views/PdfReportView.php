<style>
.button-style{	padding-top:1px;height:25px;	}

.spanError {
      color: red;
      font-size: 14px;
  }
  .field-height{padding-top:0px;height:24px; font-size:12px	}
</style>
<div class="content p-4">
      
		
        <div class="card-header bg-white font-weight-bold">
           PDF Report
        </div>
       
                    <form id="form_application" method="post"  action="<?php if($form_valid){ echo base_url().'index.php/PdfReport/printReport'; }
																				else {echo base_url().'index.php/PdfReport/getValidation';}?>" >
                                                             
        <div class="card-body bg-white">
                <div class="col-md-12 col-md-auto">
               <div class="row col-md-12">
    <label>Select The Date :</label><br>
    <div class="col-md-5">
        <input type="text" class="form-control field-height" id="txtSelectedDate" placeholder="" name="txtSelectedDate" value="<?php echo set_value('txtSelectedDate')?>">
        <span id="searchError" class="invalid-feedback" style="display:block;"></span><?php echo form_error('txtSelectedDate'); ?>
    </div></br>
</div>
        
                        
                                	<input type="hidden" name="print_type" id="print_type"  value="<?php echo $print_type;?>" />

                                    
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="container">
        <div class="row">
            <div class="col-md-12 text-right">
                                        
                                    <button type="submit" class="btn btn-info" name="action" value="view_PDF"   style="width: 85px;  ">Download</button>&nbsp;&nbsp;
                                   <button type="button" class="btn btn-primary"  value="confirm" id="clearBtn" onClick="" style="width:75px;margin-right:77px; ">Clear</button>&nbsp;&nbsp;
                        </div> 
                        
                                    </div>
                    </form>
                
</div>


<div class="modal fade bd-success-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index:1200" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #00a8b8;border-radius: 5px;padding: 10px;color:#fff">
                <h5 class="modal-title">Information !</h5>
            </div>
            <div class="modal-body">
                <p id="successMsg">Branch or Insurance Company Should be Selected.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-bule" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


  <script>

    $(document).ready(function() {
    flatpickr("#txtSelectedDate", {});
});

$("#clearBtn").click(function() {
    $('#txtSelectedDate').val("");
});
   <?php if($form_valid){?>
			setTimeout(function(){ 
				$("#form_application").attr("target", "_blank");
				$('#form_application').submit( );
				$("#form_application").attr("target", "_self");
				$("#form_application").attr("action", "index.php/PdfReport/getValidation");
				history.replaceState("", "", "index.php/PdfReport");
				$('#print_type').val("");
			 }, 0);
		<?php }else {?>
				history.replaceState("", "", "index.php/PdfReport");
				$('#print_type').val("");
		<?php } ?>

  </script>