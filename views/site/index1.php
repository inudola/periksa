<?php
/* @var $this yii\web\View */

use common\components\Helpers;
use reward\models\Setting;
use yii\helpers\Html;

$isAdmin = Yii::$app->user->identity->employee->isAdmin;
$isAdminProjection = Yii::$app->user->identity->employee->isAdminProjection;

//if ($isAdmin || $isAdminProjection) {
$this->title = 'Welcome to Reward Management App';
//}

//filter employee per user
$people = Yii::$app->user->identity->employee;


$formatter = \Yii::$app->formatter;

//format tanggal indo
function TanggalIndo($date) {
    $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl = substr($date, 8, 2);

    $result = $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun;
    return($result);
}
?>

<!--    <?php
/*    if ($isAdmin) { */ ?>
        <div class="site-index">
            <div class="body-content">
                <div class="row">
                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3><? /*= $result['category'] */ ?></h3>
                                <p>Num. of Master Reward Category</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-th"></i>
                            </div>
                            <? /*= Html::a('More info ' . '<i class="fa fa-arrow-circle-right"></i>', ['/category/index'], ['class' => 'small-box-footer']); */ ?>

                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><? /*= $result['mst_reward'] */ ?></h3>
                                <p>Num. of Master Reward</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-gift"></i>
                            </div>
                            <? /*= Html::a('More info ' . '<i class="fa fa-arrow-circle-right"></i>', ['/mst-reward/index'], ['class' => 'small-box-footer']); */ ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-maroon">
                            <div class="inner">
                                <h3><? /*= $result['simulation'] */ ?></h3>
                                <p>Num. of Projection</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-pencil-square-o"></i>
                            </div>
                            <? /*= Html::a('More info ' . '<i class="fa fa-arrow-circle-right"></i>', ['/simulation/index'], ['class' => 'small-box-footer']); */ ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    --><?php /*} */ ?>

<section class="content">

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-custom box-widget">
                <div class="box-body">
                    <div class="row row-custom">
                        <div class="col-sm-2 text-center">
                            <img class="img-circle img-responsive img-user-dashboard"
                                 src="<?= Helpers::PICTURE_URL . $people->person_id; ?>" alt="User Photo">
                        </div>
                        <div class="employee-header col-sm-10">
                            <h4>
                                <?php
                                if (!empty(Yii::$app->user->identity->nik) && !empty(Yii::$app->user->identity->employee)) {
                                    echo substr(Yii::$app->user->identity->employee->nama, 0, 30);
                                } else {
                                    echo substr(Yii::$app->user->identity->username, 0, 17);
                                }
                                ?>
                            </h4>
                            <span class="col-sm-2 text-muted"><strong>Title</strong></span><span class="col-sm-10"> : <span
                                        class="employee-title-dept"><?= substr($people->title, 0, 35); ?></span></span>
                            <span class="col-sm-2 text-muted"><strong>Department</strong></span><span class="col-sm-10"> : <span
                                        class="employee-title-dept"><?= $people->department ?></span></span>
                            <div class="col-sm-12 employee-body-divider">&nbsp;</div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <h5 class="text-muted">BAND INDIVIDU</h5>
                                    <span class="text-footer-value"><?= $people->bi ?></span>
                                </div>
                                <div class="col-sm-2">
                                    <h5 class="text-muted">BAND POSITION</h5>
                                    <span class="text-footer-value"><?= $people->bp ?></span>
                                </div>
                                <div class="col-sm-2">
                                    <h5 class="text-muted">LOCATION</h5>
                                    <span class="text-footer-value"><?= $people->location ?></span>
                                </div>
                                <div class="col-sm-2">
                                    <h5 class="text-muted">EMPLOYEE</h5>
                                    <span class="text-footer-value"><?= $people->employee_category ?></span>
                                </div>
                                <div class="col-sm-2">
                                    <h5 class="text-muted">JOIN</h5>
                                    <span class="text-footer-value"><?= TanggalIndo($people->tanggal_masuk) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box box-custom box-widget box-custom">
                <div class="box-body" style="margin-right: 10px">
                    <table class="card-data-table">
                        <?php
                        $theCategory = \reward\models\Category::find()->where(['category_name' => \reward\models\Category::THP])
                            ->asArray()->all();

                        foreach ($theCategory as $thp) { ?>
                            <tr>
                                <td><h5 class="card-title text-center"><?= $thp['category_name'] ?></h5></td>
                                <td><h5 class="text-muted">Diberikan kepada karyawan setiap bulannya</h5></td>
                            </tr>
                            <tr>
                                <td>
                                    <img class="img-responsive card-image"
                                         src="<?= Yii::getAlias('@web') . '/' . $thp['icon'] ?>"
                                         alt="thp" style="vertical-align: middle;">
                                </td>
                                <td>
                                    <table class="table-description" width="100%">
                                        <tr>
                                            <td class="card-data-row-text table-val-pop-description"
                                                onclick="return moreinfoModal(this);"
                                                id="<?= $people->salary; ?>" data-toggle="modal"
                                                data-target="#moreinfo-modal-<?= $people->salary; ?>">Gaji Dasar
                                            </td>
                                            <td class="rupiah-content">Rp.</td>
                                            <td class="money-content"><?= $formatter->asDecimal(Html::encode($people->salary), 0) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="card-data-row-text table-val-pop-description"
                                                onclick="return moreinfoModal(this);"
                                                id="<?= $people->tunjangan; ?>" data-toggle="modal"
                                                data-target="#moreinfo-modal-<?= $people->tunjangan; ?>">Tunjangan Biaya
                                                Hidup
                                            </td>
                                            <td class="rupiah-content">Rp.</td>
                                            <td class="money-content"><?= $formatter->asDecimal(Html::encode($people->tunjangan), 0) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="card-data-row-text table-val-pop-description"
                                                onclick="return moreinfoModal(this);"
                                                id="<?= $people->tunjangan_jabatan; ?>" data-toggle="modal"
                                                data-target="#moreinfo-modal-<?= $people->tunjangan_jabatan; ?>">
                                                Tunjangan
                                                Jabatan
                                            </td>
                                            <td class="rupiah-content">Rp.</td>
                                            <td class="money-content"><?= $formatter->asDecimal(Html::encode($people->tunjangan_jabatan), 0) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="card-data-row-text table-val-pop-description"
                                                onclick="return moreinfoModal(this);"
                                                id="<?= $people->tunjangan_rekomposisi; ?>" data-toggle="modal"
                                                data-target="#moreinfo-modal-<?= $people->tunjangan_rekomposisi; ?>">
                                                Tunjangan Rekomposisi
                                            </td>
                                            <td class="rupiah-content">Rp.</td>
                                            <td class="money-content"><?= $formatter->asDecimal(Html::encode($people->tunjangan_rekomposisi), 0) ?></td>
                                        </tr>
                                    </table>
                                    <hr style="margin-top: 5px; margin-bottom: 5px;border-top: 2px solid #ececec;">
                                    <table class="table-total-description">
                                        <tr>
                                            <td class="card-data-row-text card-total" style="padding-top: 5px;">TOTAL</td>
                                            <td class="rupiah-content">Rp.</td>
                                            <td class="card-total money-content">
                                                <?= $formatter->asDecimal(Html::encode($people->salary + $people->tunjangan + $people->tunjangan_jabatan + $people->tunjangan_rekomposisi), 0) ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <table>
                                        <tr>
                                            <td class="card-data-row-text">
                                                <!--                                            <span style="color: red">*</span>Merupakan nilai-->
                                                <!--                                            asumsi-->
                                            </td>
                                            <td style="text-align: right">
                                                <button class="btn btn-sm col-sm-2"
                                                        style="background-color: #ed1c24;width: 100px;color: #ffffff;border-radius: 5px;margin-top: 10px;"
                                                        data-toggle="modal" data-target="#exampleModalDetail">Detail
                                                </button>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        <?php } ?>
                    </table>

                </div>
            </div>
        </div>

        <?php foreach ($people->reward as $rows => $value) { ?>

            <?php $theCatName = \reward\models\Category::find()
                ->select(['category_name', 'icon'])
                ->where(['id' => $rows])
                ->all();
            ?>

            <?php foreach ($theCatName as $item) { ?>
                <div id="filter-records"></div>
                <div class="col-sm-6">
                    <div class="box box-custom box-widget box-custom">
                        <div class="box-body" style="margin-right: 10px">


                            <table class="card-data-table">
                                <tr>
                                    <td><h5 class="card-title text-center"><?= $item['category_name'] ?></h5></td>
                                    <td><h5 class="text-muted">Diberikan kepada karyawan setiap bulannya</h5></td>
                                </tr>

                                <tr>
                                    <td><img class="img-responsive card-image"
                                             src="<?= Yii::getAlias('@web') . '/' . $item['icon'] ?>"
                                             alt="icon" style="vertical-align: middle;"></td>
                                    <td>


                                        <table class="table-description" id="table-data">
                                            <?php foreach ($value as $r => $row) { ?>

                                                <?php
                                                foreach ($row as $x) { ?>

                                                    <tr>
                                                        <td class="card-data-row-text table-val-pop-description"
                                                            onclick="return moreinfoModal(this);"
                                                            id="<?= $x['mst_reward_id']; ?>"
                                                            data-toggle="modal"
                                                            data-target="#moreinfo-modal-<?= $x['mst_reward_id']; ?>"
                                                            href="javascript:void(0);">

                                                            <?= $r ?>
                                                        </td>
                                                        <td class="rupiah-content" id="rupiah-data">Rp.</td>
                                                        <td class="money-content" id="money-data">
                                                            <?php

                                                            $basedata = $people->salary + $people->tunjangan + $people->tunjangan_rekomposisi;

                                                            //THR
                                                            if ($x['mst_reward_id'] == 1) {
                                                                if ($people->employee_category == 'PERMANENT' || $people->employee_category == 'TELKOM') {
                                                                   echo $formatter->asDecimal(Html::encode($indexTHR2 * $basedata), 0);
                                                                } else if ($people->employee_category == 'CONTRACT') {
                                                                   echo $formatter->asDecimal(Html::encode($indexTHR * $basedata - $people->tunjangan_rekomposisi), 0);
                                                                } else if ($people->employee_category == 'TRAINEE') {
                                                                   echo $formatter->asDecimal(Html::encode($indexTHR * $people->salary), 0);
                                                                } else {
                                                                   echo $formatter->asDecimal(Html::encode($indexTHR * $basedata), 0);
                                                                }

                                                                //CUTI TAHUNAN
                                                            } elseif ($x['mst_reward_id'] == 2) {
                                                                if ($people->employee_category == 'PERMANENT' || $people->employee_category == 'TELKOM') {
                                                                   echo $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata), 0);
                                                                } else if ($people->employee_category == 'CONTRACT') {
                                                                   echo $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata-$people->tunjangan_rekomposisi), 0);
                                                                }

                                                            } elseif ($x['amount'] == 0) {
                                                                echo '';
                                                            } elseif ($x['mst_reward_id'] == 13) {
                                                                echo $formatter->asDecimal(Html::encode($x['amount']), 1) . ' Hari';
                                                            } elseif ($x['mst_reward_id'] == 10 || $x['mst_reward_id'] == 11) {
                                                                echo $formatter->asDecimal(Html::encode($x['amount']), 3);
                                                            } else {
                                                               echo $formatter->asDecimal(Html::encode($x['amount']), 0);
                                                            }

                                                            ?>


                                                        </td>
                                                    </tr>
                                                <?php }
                                            } ?>
                                        </table>

                                        <table class="table-total-description">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <br/>
                                                <!--                                            <td class="card-data-row-text card-total">TOTAL</td>-->
                                                <!--                                            <td class="card-total"></td>-->
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td class="card-data-row-text">
                                                    <?php if ($item['category_name'] == 'Variable Pay') { ?>
                                                        <span style="color: red">*</span>Merupakan
                                                        nilai asumsi
                                                    <?php } ?>
                                                </td>
                                                <td style="text-align: right">
                                                    <button class="btn btn-sm col-sm-2"
                                                            onclick="return moreinfoModal(this);"
                                                            id="<?= $rows; ?>"
                                                            style="background-color: #ed1c24;width: 100px;color: #ffffff;border-radius: 5px;margin-top: 10px;"
                                                            data-toggle="modal"
                                                            data-target="#exampleModalDetail-<?= $rows; ?>"
                                                            href="javascript:void(0);">Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            <?php }
        } ?>

        <!-- /.row -->

        <!-- Button trigger modal -->
        <!-- MODAL -->

        <!--====================================== THP ===================================-->
        <!-- GAJI DASAR -->
        <?php
        if ($people->employee_category == 'TRAINEE') {
            ?>
            <div class="modal fade" id="moreinfo-modal-<?= $people->salary; ?>" tabindex="-1" role="dialog"
                 aria-labelledby="<?= $uangSaku['reward_name'] ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content modal-custom">
                        <div class="modal-header modal-custom-header">
                            <h2 class="modal-title"><?= $uangSaku['reward_name'] ?></h2>
                        </div>
                        <div class="modal-body">
                            <?php if (!empty($uangSaku['description'])) {
                                ?>
                                <p><?= $uangSaku['description'] ?></p>
                            <?php } else { ?>
                                <p>Description not
                                    found</p>
                            <?php } ?>
                            <table class="table-detail-val">
                                <thead>
                                <tr>
                                    <td class="col-sm-4">Tunjangan</td>
                                    <td class="col-sm-4">Nilai</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="col-sm-4"><?= $uangSaku['reward_name'] ?></td>
                                    <td class="col-sm-4"><?= number_format($people->salary, 0) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer modal-custom-footer">
                            <div class="row">
                                <div class="col-sm-offset-5 col-sm-2 text-center">
                                    <button type="button" class="btn bt-close-modal" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="modal fade" id="moreinfo-modal-<?= $people->salary; ?>" tabindex="-1" role="dialog"
                 aria-labelledby="<?= $gaji['reward_name'] ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content modal-custom">
                        <div class="modal-header modal-custom-header">
                            <h2 class="modal-title"><?= $gaji['reward_name'] ?></h2>
                        </div>
                        <div class="modal-body">
                            <?php if (!empty($gaji['description'])) {
                                ?>
                                <p><?= $gaji['description'] ?></p>
                            <?php } else { ?>
                                <p>Description not
                                    found</p>
                            <?php } ?>
                            <table class="table-detail-val">
                                <thead>
                                <tr>
                                    <td class="col-sm-4">Tunjangan</td>
                                    <td class="col-sm-4">Nilai</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="col-sm-4"><?= $gaji['reward_name'] ?></td>
                                    <td class="col-sm-4"><?= number_format($people->salary, 0) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer modal-custom-footer">
                            <div class="row">
                                <div class="col-sm-offset-5 col-sm-2 text-center">
                                    <button type="button" class="btn bt-close-modal" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- TBH -->
        <div class="modal fade" id="moreinfo-modal-<?= $people->tunjangan; ?>" tabindex="-1" role="dialog"
             aria-labelledby="<?= $tbh['reward_name'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-custom">
                    <div class="modal-header modal-custom-header">
                        <h2 class="modal-title"><?= $tbh['reward_name'] ?></h2>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($tbh['description'])) {
                            ?>
                            <p><?= $tbh['description'] ?></p>
                        <?php } else { ?>
                            <p>Description not
                                found</p>
                        <?php } ?>
                        <table class="table-detail-val">
                            <thead>
                            <tr>
                                <td class="col-sm-4">Tunjangan</td>
                                <td class="col-sm-4">Nilai</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="col-sm-4"><?= $tbh['reward_name'] ?></td>
                                <td class="col-sm-4"><?= number_format($people->tunjangan, 0) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer modal-custom-footer">
                        <div class="row">
                            <div class="col-sm-offset-5 col-sm-2 text-center">
                                <button type="button" class="btn bt-close-modal" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- TUNJANGAN JABATAN -->
        <div class="modal fade" id="moreinfo-modal-<?= $people->tunjangan_jabatan; ?>" tabindex="-1" role="dialog"
             aria-labelledby="<?= $tunjab['reward_name'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-custom">
                    <div class="modal-header modal-custom-header">
                        <h2 class="modal-title"><?= $tunjab['reward_name'] ?></h2>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($tunjab['description'])) {
                            ?>
                            <p><?= $tunjab['description'] ?></p>
                        <?php } else { ?>
                            <p>Description not
                                found</p>
                        <?php } ?>
                        <table class="table-detail-val">
                            <thead>
                            <tr>
                                <td class="col-sm-4">Tunjangan</td>
                                <td class="col-sm-4">Nilai</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="col-sm-4"><?= $tunjab['reward_name'] ?></td>
                                <td class="col-sm-4"><?= number_format($people->tunjangan_jabatan, 0) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer modal-custom-footer">
                        <div class="row">
                            <div class="col-sm-offset-5 col-sm-2 text-center">
                                <button type="button" class="btn bt-close-modal" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- TUNJANGAN REKOMPOSISI -->
        <div class="modal fade" id="moreinfo-modal-<?= $people->tunjangan_rekomposisi; ?>" tabindex="-1" role="dialog"
             aria-labelledby="<?= $rekomposisi['reward_name'] ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-custom">
                    <div class="modal-header modal-custom-header">
                        <h2 class="modal-title"><?= $rekomposisi['reward_name'] ?></h2>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($rekomposisi['description'])) {
                            ?>
                            <p><?= $rekomposisi['description'] ?></p>
                        <?php } else { ?>
                            <p>Description not
                                found</p>
                        <?php } ?>
                        <table class="table-detail-val">
                            <thead>
                            <tr>
                                <td class="col-sm-4">Tunjangan</td>
                                <td class="col-sm-4">Nilai</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="col-sm-4"><?= $rekomposisi['reward_name'] ?></td>
                                <td class="col-sm-4"><?= number_format($people->tunjangan_rekomposisi, 0) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer modal-custom-footer">
                        <div class="row">
                            <div class="col-sm-offset-5 col-sm-2 text-center">
                                <button type="button" class="btn bt-close-modal" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-custom">
                    <?php
                    $theCategory = \reward\models\Category::find()->where(['category_name' => \reward\models\Category::THP])
                        ->asArray()->all();

                    foreach ($theCategory as $thp) { ?>
                        <div class="modal-header modal-custom-header">
                            <h2 class="modal-title"><?= $thp['category_name'] ?></h2>
                        </div>
                        <div class="modal-body">
                            <?= $thp['description'] ?>
                            <table class="table-detail-val">
                                <thead>
                                <tr>
                                    <td class="col-sm-4">Tunjangan</td>
                                    <td class="col-sm-4">Bulanan</td>
                                    <td class="col-sm-4">Tahunan</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="col-sm-4">Gaji Dasar</td>
                                    <td class="col-sm-4">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->salary), 0) ?></td>
                                    <td class="col-sm-4">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->salary * 12), 0) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4">Tunjangan Biaya Hidup</td>
                                    <td class="col-sm-4">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->tunjangan), 0) ?></td>
                                    <td class="col-sm-4">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->tunjangan * 12), 0) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4">Tunjangan Jabatan</td>
                                    <td class="col-sm-4">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->tunjangan_jabatan), 0) ?></td>
                                    <td class="col-sm-4">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->tunjangan_jabatan * 12), 0) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4">Tunjangan Rekomposisi</td>
                                    <td class="col-sm-4">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->tunjangan_rekomposisi), 0) ?></td>
                                    <td class="col-sm-4">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->tunjangan_rekomposisi * 12), 0) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4 table-total-val">Total</td>
                                    <td class="col-sm-4 table-total-val">
                                        Rp. <?= $formatter->asDecimal(Html::encode($people->salary + $people->tunjangan + $people->tunjangan_jabatan + $people->tunjangan_rekomposisi), 0) ?></td>
                                    <td class="col-sm-4 table-total-val">
                                        Rp. <?= $formatter->asDecimal(Html::encode(($people->salary * 12) + ($people->tunjangan * 12) + ($people->tunjangan_jabatan * 12) + ($people->tunjangan_rekomposisi * 12)), 0) ?></td>
                                </tr>

                                </tbody>
                            </table>
                            <span class="syarat">*)Syarat dan ketentuan berlaku</span>
                        </div>
                        <div class="modal-footer modal-custom-footer">
                            <div class="row">
                                <div class="col-sm-offset-5 col-sm-2 text-center">
                                    <button type="button" class="btn bt-close-modal" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!--====================================== THP END===================================-->


        <?php foreach ($people->reward as $rows => $value) { ?>

            <?php foreach ($value

                           as $r => $row) { ?>

                <?php
                foreach ($row

                         as $x) { ?>
                    <div class="modal fade" id="moreinfo-modal-<?= $x['mst_reward_id']; ?>" tabindex="-1" role="dialog"
                         aria-labelledby="<?= $r ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-custom">
                                <div class="modal-header modal-custom-header">
                                    <h2 class="modal-title"><?= $r ?></h2>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $theRewardName = \reward\models\MstReward::find()
                                        ->select(['description'])
                                        ->where(['id' => $x['mst_reward_id']])
                                        ->all();
                                    ?>

                                    <?php foreach ($theRewardName as $desc) { ?>
                                        <?php if (!empty($desc['description'])) {
                                            ?>
                                            <p><?= $desc['description'] ?></p>
                                        <?php } else { ?>
                                            <p>Description not
                                                found</p>
                                        <?php }
                                    } ?>
                                    <table class="table-detail-val">
                                        <thead>
                                        <tr>
                                            <td class="col-sm-4">Tunjangan</td>
                                            <td class="col-sm-4">Nilai</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="col-sm-4"><?= $r ?></td>
                                            <td class="col-sm-4"><?= number_format($x['amount'], 3) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer modal-custom-footer">
                                    <div class="row">
                                        <div class="col-sm-offset-5 col-sm-2 text-center">
                                            <button type="button" class="btn bt-close-modal" data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                <?php }
            }
        } ?>


        <?php foreach ($people->reward as $rows => $value) { ?>
            <?php $theCatName = \reward\models\Category::find()
                ->select(['category_name', 'icon', 'description'])
                ->where(['id' => $rows])
                ->all();
            ?>
        <div class="modal fade" id="exampleModalDetail-<?= $rows; ?>" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content modal-custom">
            <?php foreach ($theCatName

                           as $item) { ?>
                <div class="modal-header modal-custom-header">
                    <h2 class="modal-title"><?= $item['category_name'] ?></h2>
                </div>
                <div class="modal-body">
                    <?php if (!empty($item['description'])) {
                        ?>
                        <p><?= $item['description'] ?></p>
                    <?php } else { ?>
                        <p>Description not
                            found</p>
                    <?php } ?>

                    <table class="table-detail-val">
                        <thead>
                        <tr>
                            <td class="col-sm-4">Tunjangan</td>
                            <td class="col-sm-4">Nilai</td>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($value

                                       as $r => $row) { ?>

                            <?php
                            foreach ($row as $x) { ?>
                                <tr>
                                    <td class="col-sm-4"><?= $r ?></td>
                                    <td class="col-sm-4">
                                        <?php

                                        $basedata = $people->salary + $people->tunjangan + $people->tunjangan_rekomposisi;

                                        $indexTHR = floatval(Setting::getBaseSetting(Setting::INDEX_THR_1));
                                        $indexTHR2 = floatval(Setting::getBaseSetting(Setting::INDEX_THR_2));
                                        $indexTunjCuti = floatval(Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_CUTI));

                                        //THR
                                        if ($x['mst_reward_id'] == 1) {
                                            if ($people->employee_category == 'PERMANENT' || $people->employee_category == 'TELKOM') {
                                               echo $formatter->asDecimal(Html::encode($indexTHR2 * $basedata), 0);
                                            } else if ($people->employee_category == 'CONTRACT') {
                                               echo $formatter->asDecimal(Html::encode($indexTHR * $basedata - $people->tunjangan_rekomposisi), 0);
                                            } else if ($people->employee_category == 'TRAINEE') {
                                               echo $formatter->asDecimal(Html::encode($indexTHR * $people->salary), 0);
                                            } else {
                                               echo $formatter->asDecimal(Html::encode($indexTHR * $basedata), 0);
                                            }

                                            //CUTI TAHUNAN
                                        } elseif ($x['mst_reward_id'] == 2) {
                                            if ($people->employee_category == 'PERMANENT' || $people->employee_category == 'TELKOM') {
                                               echo $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata), 0);
                                            } else if ($people->employee_category == 'CONTRACT') {
                                               echo $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata-$people->tunjangan_rekomposisi), 0);
                                            }


                                        } elseif ($x['amount'] == 0) {
                                            echo '';
                                        } elseif ($x['mst_reward_id'] == 13) {
                                            echo $formatter->asDecimal(Html::encode($x['amount']), 1) . ' Hari';
                                        } elseif ($x['mst_reward_id'] == 10 || $x['mst_reward_id'] == 11) {
                                            echo $formatter->asDecimal(Html::encode($x['amount']), 3);
                                        } else {
                                           echo $formatter->asDecimal(Html::encode($x['amount']), 0);
                                        }

                                        ?>
                                    </td>

                                </tr>
                            <?php }
                        } ?>

                        </tbody>
                    </table>
                    <span class="syarat">*)Syarat dan ketentuan berlaku</span>
                </div>
                <div class="modal-footer modal-custom-footer">
                    <div class="row">
                        <div class="col-sm-offset-5 col-sm-2 text-center">
                            <button type="button" class="btn bt-close-modal" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>

                </div>
                </div>
                </div>
                </div>
            <?php }
        } ?>
</section>

<!-- CSS SCRIPT -->
<?php
$script = <<< CSS
    .rupiah-content {
        text-align: left;
    }
    .money-content {
        text-align: right;
        width: 20% !important;
    }
    .card-data-row-text{
        width: 80%;
    }
    .table-total-description{
        border-top: none !important;
    }
    .box{
        margin-bottom: 6px;
    }
    .box-custom{
        height: 220px;
    }
    .img-user-dashboard{
        height: 170px !important;
        width: auto !important;
    }
    .col-sm-6{
        padding-right: 8px;
    }
CSS;

$this->registerCss($script);
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>

    //$url = 'http://localhost/hcm/reward/web/index.php?r=site/options';
    $url = 'https://api.myjson.com/bins/czse8';


    // Replace ./data.json with your JSON feed
    fetch($url).then(response => {
        return response.json();
    }).then(data => {
        // Work with JSON data here
        $('#txt-search').keyup(function () {
            var searchField = $(this).val();


            //console.log(searchField)
            if (searchField === '') {
                $('#filter-records').html('');
                return;
            }

            var regex = new RegExp(searchField, "i");

            var output = '<div>';
            var count = 1;


            $.each(data, function (key, val) {
                if (val.reward_name.search(regex) !== -1) {

                    output += '<div class="col-xs-6 col-md-3 text-center">';
                    output += '<a class="social-button google"> <img src="' + val.icon + '" alt="' + val.reward_name + '" id="ikon" class="btnn"/></a>';
                    //output += '<div class="col-md-3"><img class="img-responsive" src="'+val.icon+'" alt="'+ val.reward_name +'" /></div>';

                    output += '<h5>' + val.reward_name + '</h5>';
                    output += '<p>' + val.employee_salary + '</p>'

                    output += '</div>';
                    if (count % 2 == 0) {
                        output += '</div><div>'
                    }
                    count++;
                }

            });
            output += '</div>';
            $('#filter-records').html(output);
        });
        //console.log(data);
    }).catch(err => {
        // Do something for an error here


    });

    $('#table-data #money-data').each(function() {
        var cellText = $(this).text();
        if(cellText.indexOf('Hari') >= 0){
            console.log("Test");
            $(this).prev().html("");
        }
    });
</script>

<script> function moreinfoModal(field) {
        console.log(field.id);
        $('.moreinfo-modal').toggleClass('open');
    }

    function closeMoreInfoModal() {
        $('.moreinfo-modal').toggleClass('open');
    }

    $(document).on('click', '.close-pill', function (e) {
        $(this).parent().remove();
        e.preventDefault();
    });

    $(document).ready(function(){
        $(".content-header").text("");
        $(".content").css("padding-top","0px");
        $(".content").css("margin-top","-10px");
    });
</script>

