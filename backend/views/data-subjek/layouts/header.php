<?php

use yii\helpers\Html;
use backend\models\Circulation;




?>
<header class="main-header">
  <!-- Logo -->
  <a href="index" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b><?= $title ?></b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b><?= $title ?></b></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->

        <!-- Notifications: style can be found in dropdown.less -->
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            
<?php
$sirkulasi = Circulation::find()->where(['status_peminjaman'=>'Dipinjam']);

$all= $sirkulasi->all();

$pinjam = $sirkulasi->count();

if(!empty($all)){
  $i=0;
  foreach ($all as $data) {
        $timeStart = strtotime($data->tanggal_kembali);
        $timeEnd = strtotime(date('Y-m-d'));
        $terlambat = date("d", $timeEnd) - date("d", $timeStart);
        if ($terlambat>3){
            $i = $i+1;   
      }
  }
  if ($i!== 0){
echo
  '<span class="label label-warning">
            '.$pinjam.'</span>';
}
}
 ?>

          </a>

          <?php
           if (!empty($all)){
          echo'
          <ul class="dropdown-menu">
            
            <li>
              
              <ul class="menu">
                <li>
                  <a href="#">
                    <i class="fa fa-users text-aqua"></i> '.$pinjam.' buku dipinjam
                  </a>
                </li>

                                <li>
                  <a href="#">
                    <i class="fa fa-users text-danger"></i> '.$i.' buku terlambat kembali
                  </a>
                </li>
     
              </ul>
            </li>
           <li class="footer">'. Html::a('Lihat Semua', ['sirkulasi/pengembalian']).'
           </li>
          </ul>';
        }
          ?>
        </li>


        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">

            <?= Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . \Yii::$app->user->identity->picture, ['class' => 'user-image', 'alt' => 'myImage', 'width' => '160', 'height' => 'auto']); ?>

            <span class="hidden-xs"><?= \Yii::$app->user->identity->username ?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <?= Html::img('@web/img/user2-160x160.jpg', ['class' => 'img-circle', 'alt' => 'User Image']) ?>
              <p>
                <?= \Yii::$app->user->identity->username ?>
              </p>
              <p>
                <?= \Yii::$app->user->identity->email ?>
              </p>
            </li>
            <!-- Menu Body -->

            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">

                <?= Html::a(
                  'Profile',
                  ['/admin/user/profile', 'id' => Yii::$app->user->identity->id],
                  ['class' => 'btn btn-default btn-flat']
                ) ?>
              </div>
              <div class="pull-right">
                <?= Html::a(
                  'Sign out',
                  ['/site/logout'],
                  ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                ) ?>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        <!-- <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li> -->
      </ul>
    </div>
  </nav>
</header>