<?php require_once('aq_admin_header.php'); ?>

<?php if($res){ ?>
<table border="1">
<tr>
<td>uid</td>
<td style='width:60px'>姓名</td>
<td style='width:60px'>有无答疑权限</td>
<td>账户余额</td>
<td>擅长领域</td>
<td style='width:140px'>学校</td>
<td style='width:40px'>科目</td>
<td style='width:70px'>小/初/高</td>
<td>简介</td>
</tr>
  <?php foreach($res as $v){ ?>
  <tr>
      <td><a href='<?php echo Constant::ADMIN_URL?>edit_page?teacher_id=<?php echo $v['id']?>'><?php echo $v['id']?></a></td>
      <td><?php echo $v['name']?></td>
      <td><?php if($v['aq_teacher']){echo '有';}else{echo '无';}?></td>
      <td><?php echo $v['aq_account_balance']?></td>
      <td><?php echo $v['aq_fields']?></td>
      <td><?php echo $v['school']?></td>
      <td><?php echo $v['subject']?></td>
      <td><?php echo $v['grade_type']?></td>
      <td><?php echo $v['intro']?></td>
    </tr>
  <?php } ?>
</table>
<?php };?>

<?php echo $pageinfo ?>