<?php
include 'includes/header.php';
include 'includes/navigation.php';
require_once 'includes/newsfunction.php';
if(! empty($typeMessage) AND $type == "success"){
    $subject = '';
    $message = '';
}
?>
<script type="text/javascript">
        function validate() {
            var valid = true;

            $(".info").html("");
            var subject = document.forms["mailForm"]["subject"].value;
            var userMessage = document.forms["mailForm"]["userMessage"].value;
            
            if (subject == "") {
                $("#subject-info").html("(required)");
                $("#subject").css('background-color', '#FFFFDF');
                valid = false;
            }
            if (userMessage == "") {
                $("#userMessage-info").html("(required)");
                $("#userMessage").css('background-color', '#FFFFDF');
                valid = false;
            }
            return valid;
        }
        
        function addMoreAttachment() {
            $(".attachment-row:last").clone().insertAfter(".attachment-row:last");
            $(".attachment-row:last").find("input").val("");
        }
</script>
<h2 class="text-center">Subscribe News </h2>
<div class="attachment-form-container">
    <form name="mailForm" id="mailForm" method="post" action="news"  enctype="multipart/form-data" onsubmit="return validate()">
        <div class="input-row">
            <label>Subject</label> <span id="subject-info" class="info"></span><br />
            <input type="text" class=" form-contorl input-field" name="subject" id="subject" value="<?=$subject;?>" />
        </div>
        <div class="input-row">
            <label>Message</label> <span id="userMessage-info" class="info"></span><br />
            <textarea name="userMessage" id="userMessage" class="input-field" id="userMessage" cols="60" rows="6" ><?=$message;?></textarea>
        </div>
        <div class="attachment-row">
            <input type="file" class="input-field" name="attachment[]">
        </div>
        <div onClick="addMoreAttachment();" class="icon-add-more-attachemnt" title="Add More Attachments">
            <img src="img/icon-add-more-attachment.png" alt="Add More Attachments">
        </div>
        <div>
            <input type="submit" name="send" class="btn-submit" value="Send"/>
            <div id="statusMessage"> 
                <?php
                if (! empty($typeMessage)) {
                    ?>
                    <p class='<?php echo $type; ?>Message'><?php echo $typeMessage; ?></p>
                <?php
                }
                ?>
            </div>
        </div>
    </form>
</div>
