<?php 
// require_once '../../../config.php';
// require_once "../../sources/class.upload.php";
?>
<div id='message_box'>
    <div class='message_box__relative'>
        <div class="conversations rounded" style='display:none;'>
            <div class='message_items rounded-left'>
                <div class='message_items__message rounded-left'>                    
                </div>
            </div>
            <div class="message_details pl-0">
                <div class="message_details-container rounded-top p-1">                
                </div>
                <input type='hidden' id='message_details-conversationId'>
                <textarea class='rounded-bottom' id="message_input" data-sellerid="<?= $sellerId ?>" style='resize:none;background:#eff0f5;'></textarea>
            </div>
        </div>
        <a href="#" id='messageBox__button' data-sellerid="<?= $sellerId ?>" data-productid="<?=$_GET['id']?>">
            <span class='fa-stack fa-2x'>
                <i class="fa fa-circle fa-stack-2x icon-background text-purple"></i>
                <i class="fas fa-comment fa-stack-1x text-light"></i>
            </span>
        </a>
    </div>
</div>