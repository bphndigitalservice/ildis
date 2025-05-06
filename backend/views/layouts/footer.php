<!-- 	  <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.3.0
        </div>
        <strong>Copyright &copy; 2020 <a href="https://bphn.go.id">Pusat Dokumentasi dan Jaringan Informasi Hukum Nasional</a>.</strong> All rights reserved.
      </footer>
 -->
<?php

use mdm\admin\models\User;
use mdm\admin\models\AuthAssignment;

$user = User::find()->where(['username' => Yii::$app->user->identity->username])->one();
?>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <strong>Copyright &copy; 2020 <a href="https://bphn.go.id">Badan Pembinaan Hukum Nasional</a>.</strong> All rights reserved.
  </div>
  User : <span class="label label-default label-md"><?php echo $user->username; ?></span> | Hak Akses :
  <?php //$group = AuthAssignment::find()->(['user_id'=>$user->user_id])->all();
  $group = AuthAssignment::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
  if ($group != NULL) {
    foreach ($group as $data) {
      echo '<span class="label label-warning label-md">' . $data['item_name'] . '</span>&nbsp;';
    }
  }


  ?>
</footer>
