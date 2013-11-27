<?php require_once('aq_admin_header.php'); ?>


*excel 2007 is highly recommended.

<form method='post' action='<?php echo Constant::ADMIN_URL?>import_excel' enctype="multipart/form-data">
<input class="touming up_front aqupload" type="file" id="up" name="up"/>
                <input type='submit' value='上传' />
</form>