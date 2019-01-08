<form action="<?echo $target?>" id="modal-form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $title ?></h4>
            </div>
            <div class="modal-body">
                <div id="errors"></div>
                <?php echo $content ?>
            </div>
            <div class="modal-footer">
            <?if(isset($buttons)):?>
                <?php echo $buttons?>
            <?else:?>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel')?></button>
                <button type="submit" class="btn btn-primary"><?php echo __('Save')?></button>
            <?endif?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</form>