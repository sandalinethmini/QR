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
                Account Details
            </div>
            <div class="card-body">
                <div class="col-md-12 col-md-auto">
                
    <div class="row col-md-4">
        
            <label class="lblStrong">Account Number :</label>
       
        
            <input type="text" name="txtSearchValue" id="txtSearchValue" class="form-control" placeholder="Enter Account Number">
            <span id="searchError" class="invalid-feedback" style="display:block;"></span>
        

        
            <label class="lblStrong">From Date :</label>
        
        
            <input type="text" name="txtFromDate" id="txtFromDate" class="form-control" placeholder=""value="<?php echo set_value('txtFromDate')?>" >
            <span id="searchError" class="invalid-feedback" style="display:block;"></span>
        

        
            <label class="lblStrong">To Date :</label>
        
        
            <input type="text" name="txtToDate" id="txtToDate" class="form-control" placeholder=""value="<?php echo set_value('txtToDate')?>" >
            <span id="searchError" class="invalid-feedback" style="display:block;"></span>
        


       
        <button class="btn btn-info field-height" type="button" onclick="validateAndLoadTableWithDate()" style="margin-right:10px;">Search</button>
          
        
       
        
        <button class="btn btn-info field-height" type="button" onclick="downloadExcel()" >Export to Excel</button>

        
        </div>
</div>
</div>
</div>
        <?php if ($this->session->flashdata('error_message')) : ?>
  <div class="alert alert-danger" role="alert">
    <?php echo $this->session->flashdata('error_message'); ?>
  </div>
<?php endif; ?>




    
     <div class="card mb-4">
            <div class="card-body">
                <table id="recordTable" class="table table-hover table-striped table-sm" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                    <th >No</th>
                       
                        <th >A/C Number</th>
                        <th >RRN</th>
                        <th id="amount">Amount</th>
                        <th width="15%">Time</th>
                        
                        <th>Response</th>
                        <th >Other Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    
                   
                    </tbody>
                </table>
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
	

     


<div class="modal fade " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  aria-hidden="true" id="upload_model">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content"> 
      <div class="modal-header" style="background-color: #00a8b8;border-radius: 5px;padding: 10px;color:#fff">
        <strong class="modal-title">View/Upload Document !</strong>
      </div>
      <div class="modal-body">
        <p >Upload or View Uploaded Documents ?</p>
      </div>
      <form action="index.php/UploadDocument/UploadDocument" method="post" >
      <input type="hidden" value="" id="recordID" name="recordID"  />
      <div class="modal-footer">
      	<button type="submit"  class="btn btn-success field-height" data-dismiss="">Yes</button>
        <button type="button" class="btn btn-primary field-height" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>





<script>

  $(document).ready(function() {
		flatpickr("#txtFromDate", {});
		flatpickr("#txtToDate", {});
        loadTable();
    });

    function validateAndLoadTableWithDate() {
      var startDate = $("#txtFromDate").val(); 
    var endDate = $("#txtToDate").val();  
        var searchText = $("#txtSearchValue").val();
        var accountNumberPattern = /^\d{12}$/;
       
       $('table tbody').empty();

        if (searchText.trim() === "") {
          $("#searchError").html("Please enter an Account Number.");
        } else if (!accountNumberPattern.test(searchText)) {
          $("#searchError").html("Please enter a 12-digit Account Number.");
        } 
        else if (!startDate || !endDate) {
        $("#searchError").html("Please select both From Date and To Date.");
    } else if (!isValidDate(startDate) || !isValidDate(endDate)) {
        $("#searchError").html("Please enter valid From Date and To Date.");
    }
    else {
          $("#searchError").html("");
          loadTableWithDate(startDate, endDate, searchText);
        }
    }

    function isValidDate(dateString) {
    
    var regex = /^\d{4}-\d{2}-\d{2}$/;
    return regex.test(dateString);
}

    <?php if($this->session->flashdata('info')) { ?>    
        $("#successMsg").html("<?php echo $this->session->flashdata('info');?>");
        $(".bd-success-modal-sm").modal("show");
    <?php }?>

    <?php if($this->session->flashdata('validation')) { ?>    
        
        $("#successMsg").html("Try Again. <br/> Data not Updated ");
        $(".bd-success-modal-sm").modal("show");
        
    <?php }?>

    

    $("#txtSearchValue").on("keydown", function(event) {
        if (event.which == 13) 
            validateAndLoadTable();
    });

    function loadTableWithDate(startDate, endDate, searchText) {


    var dTable = $('#recordTable').DataTable({
        destroy: true,
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": 'index.php/AccountDetails/recordlist',
            "type": "POST",
            data: {
                'startDate': startDate,
                'endDate': endDate,
                'searchText':searchText
            }
        },
        "columnDefs": [
            {
                "targets": [0,1,2,3,4,5,6],
                "orderable": false,
            },
            { className: 'text-center', targets: [0,2,4,5,6] },
            { className: 'text-right', targets: [3] },
        ],
    });


}

function downloadExcel() {
  var form = document.createElement("form");
  form.method = "post";
  form.action = "<?php echo site_url('AccountDetails/download_excel'); ?>";

  var fromDateInput = document.getElementById("txtFromDate"); 
  var toDateInput = document.getElementById("txtToDate"); 
  var searchValueInput = document.getElementById("txtSearchValue"); 

  form.appendChild(fromDateInput.cloneNode(true));
  form.appendChild(toDateInput.cloneNode(true));
  form.appendChild(searchValueInput.cloneNode(true));

  document.body.appendChild(form);
  form.submit();
}



    
</script>
