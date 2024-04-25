<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        .button-style {
            padding-top: 1px;
            height: 25px;
        }

        .spanError {
            color: red;
            font-size: 14px;
        }

        #last-entered-details {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .field-height{	padding-top:0px;height:24px; font-size:12px	}
    </style>
</head>
<body>

<div class="content p-4">

    <div class="card-header bg-white font-weight-bold">
        Save Time
    </div>

    <?php echo form_open('SetTimeController/set_time'); ?>
    <div class="card-header bg-white font-weight-bold">
        <div class="form-group">
            <label for="current_time">Current Day Time:</label>
            <div class="col-md-3">
                <input type="text" name="current_time" id="current_time" class="form-control field-height" value="<?php echo $daytime->currentdaytime; ?>" required>
            </div>
            <span class="spanError" id="current_time_error"></span>
        </div>
        <div class="form-group">
            <label for="previous_time">Previous Day Time:</label>
            <div class="col-md-3">
                <input type="text" name="previous_time" id="previous_time" class="form-control field-height" value="<?php echo $daytime->previousdaytime; ?>" required>
            </div>
            <span class="spanError" id="previous_time_error"></span>
        </div>
        <button type="submit" class="btn btn-info button-style">Save</button>
    </div>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    
            <p><strong>Entered by :  <?php echo $daytime->last_updated_by; ?></strong></p>
            <p><strong>Entered at :  <?php echo $daytime->entered_at; ?></strong></p>
       
    </div>

   
    </form>

    <script>
      
        
    $(document).ready(function() {
            flatpickr("#current_time", {enableTime: true, enableSeconds: true, noCalendar: true, timeFormat: "H:i:s"});
            flatpickr("#previous_time", {enableTime: true, enableSeconds: true, noCalendar: true, timeFormat: "H:i:s"});

        });

        function updateLastEnteredDetails() {
        $.ajax({
            url: '<?php echo base_url() . "SetTimeController/get_last_entered"; ?>', 
            method: 'GET',
            dataType: 'JSON',
            success: function(response) {
                if (response.success) { 
                    $('#last_current_time').text(response.data.currentdaytime);
                    $('#last_previous_time').text(response.data.previousdaytime);
                    $('#last_entered_at').text(response.data.entered_at);
                } else {
                   
                }
            }
        });
    }


</script>
</body>
</html>
