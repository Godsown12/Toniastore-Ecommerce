<!-- retry meassage of the paystack-->
<?php ob_start(); ?>
<div class="modal fade" id="paystackClose" tabindex="-1" role="dialog" aria-labelledby="paystackClose" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paystackClose">ToniaStore</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="re-click">
            <p>Click "Paystack" to retry payment</p>
        </div>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>
<?php echo ob_get_clean(); ?>