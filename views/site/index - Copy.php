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
function TanggalIndo($date)
{
    $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl = substr($date, 8, 2);

    $result = $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun;
    return ($result);
}

?>

<section class="content">
    <div class="content-responsive">
        <div class="row row-custom">
            <div class="col-sm-12 box-top-header-content">
                <div class="box box-custom box-widget">
                    <div class="box-body">
                        <div class="row-custom-header row col-sm-12">

                            <div class="col-sm-6">
                                <table class="table-header-person">
                                    <tr>
                                        <td style="vertical-align:center; width:120px;">
                                            <img class="img-circle img-responsive img-user-dashboard"
                                                 src="<?= Helpers::PICTURE_URL . $people->person_id; ?>" alt="">
                                        </td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td colspan="3"><h4 class="col-sm-12 table-person-name">
                                                            <?php
                                                            if (!empty(Yii::$app->user->identity->nik) && !empty(Yii::$app->user->identity->employee)) {
                                                                echo substr(Yii::$app->user->identity->employee->nama, 0, 30);
                                                            } else {
                                                                echo substr(Yii::$app->user->identity->username, 0, 17);
                                                            }
                                                            ?>
                                                        </h4></td>
                                                </tr>
                                                <tr>
                                                    <td class="col-sm-3 text-muted nowrap"><strong>Title</strong></td>
                                                    <td>:</td>
                                                    <td class="employee-title-dept"><?= substr($people->title, 0, 35); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="col-sm-3 text-muted nowrap"><strong>Department</strong>
                                                    </td>
                                                    <td>:</td>
                                                    <td class="employee-title-dept"><?= $people->department ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="col-sm-3 text-muted nowrap"><strong>Band
                                                            Individu</strong></td>
                                                    <td>:</td>
                                                    <td class="employee-title-dept"><?= $people->bi ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="col-sm-3 text-muted nowrap"><strong>Band
                                                            Position</strong></td>
                                                    <td>:</td>
                                                    <td class="employee-title-dept"><?= $people->bp ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="col-sm-3 text-muted nowrap"><strong>Location</strong>
                                                    </td>
                                                    <td>:</td>
                                                    <td class="employee-title-dept"><?= $people->location ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="col-sm-3 text-muted nowrap"><strong>Employee</strong>
                                                    </td>
                                                    <td>:</td>
                                                    <td class="employee-title-dept"><?= $people->employee_category ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="col-sm-3 text-muted nowrap"><strong>Join</strong></td>
                                                    <td>:</td>
                                                    <td class="employee-title-dept"><?= TanggalIndo($people->tanggal_masuk) ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-1">
                                <div class="header-divider">&nbsp;</div>
                            </div>
                            <div class="col-sm-5">
                                <table style="width:100%; margin-top: 10px;">
                                    <tr>
                                        <td><h5 class="card-title">Take Home Pay</h5></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6 class="text-muted card-subtitle">Diberikan kepada karyawan setiap
                                                bulannya</h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table style="width:100%;height:110px !important;"
                                                   class="table-description">

                                                <tr>
                                                    <td class="card-data-row-text table-val-pop-description"
                                                        onclick="return moreinfoModal(this);" id="37"
                                                        data-toggle="modal" data-target="#moreinfo-modal-37"
                                                        href="javascript:void(0);">
                                                        Gaji Dasar
                                                    </td>
                                                    <td class="rupiah-content" id="rupiah-data">
                                                        Rp.
                                                    </td>
                                                    <td class="money-content" id="money-data">
                                                        <?= $formatter->asDecimal(Html::encode($people->salary), 0); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="card-data-row-text table-val-pop-description"
                                                        onclick="return moreinfoModal(this);" id="37"
                                                        data-toggle="modal" data-target="#moreinfo-modal-37"
                                                        href="javascript:void(0);">
                                                        Tunjangan Biaya Hidup
                                                    </td>
                                                    <td class="rupiah-content" id="rupiah-data">
                                                        Rp.
                                                    </td>
                                                    <td class="money-content" id="money-data">
                                                        <?= $formatter->asDecimal(Html::encode($people->tunjangan), 0); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="card-data-row-text table-val-pop-description"
                                                        onclick="return moreinfoModal(this);" id="37"
                                                        data-toggle="modal" data-target="#moreinfo-modal-37"
                                                        href="javascript:void(0);">
                                                        Tunjangan Jabatan
                                                    </td>
                                                    <td class="rupiah-content" id="rupiah-data">
                                                        Rp.
                                                    </td>
                                                    <td class="money-content" id="money-data">
                                                        <?= $formatter->asDecimal(Html::encode($people->tunjangan_jabatan), 0); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="card-data-row-text table-val-pop-description"
                                                        onclick="return moreinfoModal(this);" id="37"
                                                        data-toggle="modal" data-target="#moreinfo-modal-37"
                                                        href="javascript:void(0);">
                                                        Tunjangan Rekomposisi
                                                    </td>
                                                    <td class="rupiah-content" id="rupiah-data">
                                                        Rp.
                                                    </td>
                                                    <td class="money-content" id="money-data">
                                                        <?= $formatter->asDecimal(Html::encode($people->tunjangan_rekomposisi), 0); ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <hr style="margin-top: 5px; margin-bottom: 5px;border-top: 2px solid #ececec;">
                                <table class="table-total-description">
                                    <tbody>
                                    <tr>
                                        <td class="card-data-row-text card-total">
                                            TOTAL
                                        </td>
                                        <td class="rupiah-content">Rp.</td>
                                        <td class="card-total money-content">
                                            <?= $formatter->asDecimal(Html::encode($basedataFull), 0); ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--SEARCH RESULT-->
            <div id="filter-records"></div>
            <div class="tag-title-reward"><p>Reward Information</p></div>
            <!--SEARCH RESULT-->
            <?php foreach ($people->reward as $rows => $value) { ?>

                <?php $theCatName = \reward\models\Category::find()
                    ->select(['category_name', 'icon', 'title', 'note'])
                    ->orderBy(['id' => SORT_ASC])
                    ->where(['id' => $rows])
                    ->all();
                ?>

                <?php foreach ($theCatName as $item) { ?>

                    <div class="col-sm-6 class-default">
                        <div class="box box-custom box-widget box-custom">
                            <div class="box-body" style="margin-right: 10px">


                                <table class="card-data-table">
                                    <tr>
                                        <td colspan="2"><h5 class="card-title"><?= $item['category_name'] ?></h5></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><h6 class="text-muted card-subtitle"><?= $item['title'] ?></h6>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="vertical-align:top; width:100px;"><img
                                                    class="img-responsive card-image"
                                                    src="<?= Yii::getAlias('@web') . '/' . $item['icon'] ?>"
                                                    alt="icon"
                                                    style="vertical-align: middle;">
                                        </td>
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
                                                            <td class="rupiah-content" id="rupiah-data">

                                                                Rp.
                                                            </td>
                                                            <td class="money-content" id="money-data">
                                                                <?php

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
                                                                        echo $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata - $people->tunjangan_rekomposisi), 0);
                                                                    }

                                                                } elseif ($x['amount'] == 0) {
                                                                    echo '-';
                                                                } elseif ($x['mst_reward_id'] == 13) {
                                                                    echo $formatter->asDecimal(Html::encode($x['amount']), 1) . ' Hari';
                                                                } else if ($item['category_name'] == 'Take Home Pay') {
                                                                    if ($r == 'Gaji Dasar') {
                                                                        echo $formatter->asDecimal(Html::encode($people->salary), 0);
                                                                    } else if ($r == 'Tunjangan Biaya Hidup') {
                                                                        echo $formatter->asDecimal(Html::encode($people->tunjangan), 0);
                                                                    } else if ($r == 'Tunjangan Jabatan') {
                                                                        if ($people->tunjangan_jabatan > 0) {
                                                                            echo $formatter->asDecimal(Html::encode($people->tunjangan_jabatan), 0);
                                                                        } else {
                                                                            echo '-';
                                                                        }
                                                                    } else if ($r == 'Tunjangan Rekomposisi') {
                                                                        echo $formatter->asDecimal(Html::encode($people->tunjangan_rekomposisi), 0);
                                                                    } else if ($r == 'Penghargaan Masa Kerja') {
                                                                        if ($people->employee_category == 'PERMANENT') {
                                                                            //PMK => KELIPATAN 5 TAHUN S/D 30 TAHUN
                                                                            if ($masaKerja == 60) {
                                                                                echo $formatter->asDecimal(Html::encode($indexPMK5 * $basedata), 0);
                                                                            } else if ($masaKerja == 120) {
                                                                                echo $formatter->asDecimal(Html::encode($indexPMK10 * $basedata), 0);
                                                                            } else if ($masaKerja == 180) {
                                                                                echo $formatter->asDecimal(Html::encode($indexPMK15 * $basedata), 0);
                                                                            } else if ($masaKerja == 240) {
                                                                                echo $formatter->asDecimal(Html::encode($indexPMK20 * $basedata), 0);
                                                                            } else if ($masaKerja == 300) {
                                                                                echo $formatter->asDecimal(Html::encode($indexPMK25 * $basedata), 0);
                                                                            } else if ($masaKerja == 360) {
                                                                                echo $formatter->asDecimal(Html::encode($indexPMK30 * $basedata), 0);
                                                                            } else {
                                                                                echo '-';
                                                                            }

                                                                        }
                                                                    } //CUTI BESAR => 6 TAHUN = 72 BULAN
                                                                    else if ($r == 'Cuti Besar') {
                                                                        if ($people->employee_category == 'PERMANENT') {
                                                                            if ($masaKerja == 72 || $masaKerja == 144 || $masaKerja == 216 || $masaKerja == 288 || $masaKerja == 360 || $masaKerja == 432) {
                                                                                echo $formatter->asDecimal(Html::encode($indexCutiBesar * $basedata), 0);
                                                                            } else {
                                                                                echo '-';
                                                                            }
                                                                        }
                                                                    } else if ($r == 'Uang Lembur') {
                                                                        echo '-';
                                                                    } else {
                                                                        echo $formatter->asDecimal(Html::encode($x['amount']), 0);
                                                                    }
                                                                } else {
                                                                    echo $formatter->asDecimal(Html::encode($x['amount']), 0);
                                                                }


                                                                ?>


                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                            </table>

                                            <?php if ($item['category_name'] == 'Take Home Pay') { ?>
                                                <hr style="margin-top: 5px; margin-bottom: 5px;border-top: 2px solid #ececec;">
                                                <table class="table-total-description">

                                                    <tr>
                                                        <td class="card-data-row-text card-total">
                                                            TOTAL
                                                        </td>
                                                        <td class="rupiah-content">Rp.</td>
                                                        <td class="card-total money-content">
                                                            <?= $formatter->asDecimal(Html::encode($basedataFull), 0) ?></td>
                                                    </tr>
                                                </table>
                                            <?php } else { ?>
                                                <table class="table-total-description">
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <br/>
                                                        <!--                                            <td class="card-data-row-text card-total">TOTAL</td>-->
                                                        <!--                                            <td class="card-total"></td>-->
                                                    </tr>
                                                </table>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <table width="100%">
                                                <tr>
                                                    <td class="card-data-row-text">
                                                        <?php if (!empty($item['note'])) { ?>
                                                            <span style="color: red">*</span><?= $item['note'] ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="card-data-row-text" style="text-align: right">
                                                        <button class="btn btn-sm col-sm-2 card-bt-detail pull-right"
                                                                onclick="return moreinfoModal(this);"
                                                                id="<?= $rows; ?>"
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

            <div class="col-md-12">
                <h5>Disclosure : <br/>
                    Jika Seluruh informasi ini berbeda dengan ketentuan, maka gunakan ketentuan terbaru sebagai acuan.
                </h5>
            </div>
            <!-- /.row -->

            <!-- Button trigger modal -->
            <!-- MODAL -->
            <?php foreach ($people->reward as $rows => $value) { ?>

                <?php foreach ($value

                               as $r => $row) { ?>

                    <?php
                    foreach ($row as $x) { ?>
                        <div class="modal fade" id="moreinfo-modal-<?= $x['mst_reward_id']; ?>" tabindex="-1"
                             role="dialog"
                             aria-labelledby="<?= $r ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content modal-custom">
                                    <div class="modal-header modal-custom-header">
                                        <h2 class="modal-title"><?= $r ?></h2>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        $theRewardName = \reward\models\MstReward::find()
                                            ->select(['description', 'formula', 'file', 'categoryId', 'reward_name'])
                                            ->orderBy(['reward_name' => SORT_ASC])
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

                                            <?php } ?>

                                            <?php if (!empty($desc['file'])) {
                                                ?>
                                                <p>
                                                    <?=
                                                    Html::a('PDF', ['mst-reward/pdf',
                                                        'id' => $x['mst_reward_id'],], ['class' => 'btn btn-default',//'target' => '_blank',
                                                    ]);
                                                    ?>
                                                </p>

                                            <?php }
                                        } ?>
                                        <span class="formula">Formula :</span><span><?= $desc['formula'] ?></span>
                                        <table class="table-detail-val">
                                            <thead>
                                            <tr>
                                                <td class="col-sm-4">Komponen</td>
                                                <td class="col-sm-4">Nilai</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="col-sm-4"><?= $r ?></td>
                                                <td class="col-sm-4 table-detail-val-number"><?php
                                                    //THR
                                                    if ($x['mst_reward_id'] == 1) {
                                                        if ($people->employee_category == 'PERMANENT' || $people->employee_category == 'TELKOM') {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTHR2 * $basedata), 0);
                                                        } else if ($people->employee_category == 'CONTRACT') {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTHR * $basedata - $people->tunjangan_rekomposisi), 0);
                                                        } else if ($people->employee_category == 'TRAINEE') {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTHR * $people->salary), 0);
                                                        } else {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTHR * $basedata), 0);
                                                        }

                                                        //CUTI TAHUNAN
                                                    } else if ($x['mst_reward_id'] == 2) {
                                                        if ($people->employee_category == 'PERMANENT' || $people->employee_category == 'TELKOM') {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata), 0);
                                                        } else if ($people->employee_category == 'CONTRACT') {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata - $people->tunjangan_rekomposisi), 0);
                                                        }
                                                    } else if ($r == "Rawat Inap Tahunan") {
                                                        echo number_format($x['amount'], 2);
                                                    } else if ($desc['categoryId'] == 2) {
                                                        if ($r == 'Gaji Dasar') {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->salary), 0);
                                                        } else if ($r == 'Tunjangan Biaya Hidup') {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan), 0);
                                                        } else if ($r == 'Tunjangan Jabatan') {
                                                            if ($people->tunjangan_jabatan > 0) {
                                                                echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan_jabatan), 0);
                                                            } else {
                                                                echo '';
                                                            }
                                                        } else if ($r == 'Tunjangan Rekomposisi') {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan_rekomposisi), 0);
                                                        } else if ($r == 'Penghargaan Masa Kerja') {
                                                            if ($people->employee_category == 'PERMANENT') {
                                                                //PMK => KELIPATAN 5 TAHUN S/D 30 TAHUN
                                                                if ($masaKerja == 60) {
                                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK5 * $basedata), 0);
                                                                } else if ($masaKerja == 120) {
                                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK10 * $basedata), 0);
                                                                } else if ($masaKerja == 180) {
                                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK15 * $basedata), 0);
                                                                } else if ($masaKerja == 240) {
                                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK20 * $basedata), 0);
                                                                } else if ($masaKerja == 300) {
                                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK25 * $basedata), 0);
                                                                } else if ($masaKerja == 360) {
                                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK30 * $basedata), 0);
                                                                } else {
                                                                    echo '-';
                                                                }

                                                            }
                                                        } //CUTI BESAR => 6 TAHUN = 72 BULAN
                                                        else if ($r == 'Cuti Besar') {
                                                            if ($people->employee_category == 'PERMANENT') {
                                                                if ($masaKerja == 72 || $masaKerja == 144 || $masaKerja == 216 || $masaKerja == 288 || $masaKerja == 360 || $masaKerja == 432) {
                                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexCutiBesar * $basedata), 0);
                                                                }
                                                            }
                                                        } else if ($r == 'Uang Lembur') {
                                                            echo '-';
                                                        } else {
                                                            echo "<span class='pull-left'>Rp. </span>" . number_format($x['amount'], 0);
                                                        }
                                                    } else {
                                                        //echo number_format($x['amount'], 0);
                                                        echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($x['amount']), 0);
                                                    } ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer modal-custom-footer">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button type="button" class="btn bt-close-modal pull-right"
                                                        data-dismiss="modal">
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
                    ->orderBy(['id' => SORT_ASC])
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
                        <span class="formula"></span>
                        <table class="table-detail-val">
                            <thead>
                            <tr>
                                <td class="col-sm-4">Komponen</td>
                                <?php if ($item['category_name'] == 'Take Home Pay') { ?>
                                    <td class="col-sm-4">Bulanan</td>
                                    <td class="col-sm-4">Tahunan</td>
                                <?php } else { ?>
                                    <td class="col-sm-4">Nilai</td>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($value

                                           as $r => $row) { ?>

                                <?php
                                foreach ($row as $x) { ?>
                                    <tr>
                                        <td class="col-sm-4"><?= $r ?></td>
                                        <td class="col-sm-4 table-detail-val-number">
                                            <?php

                                            //THR
                                            if ($x['mst_reward_id'] == 1) {
                                                if ($people->employee_category == 'PERMANENT' || $people->employee_category == 'TELKOM') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTHR2 * $basedata), 0);
                                                } else if ($people->employee_category == 'CONTRACT') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTHR * $basedata - $people->tunjangan_rekomposisi), 0);
                                                } else if ($people->employee_category == 'TRAINEE') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTHR * $people->salary), 0);
                                                } else {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTHR * $basedata), 0);
                                                }

                                                //CUTI TAHUNAN
                                            } elseif ($x['mst_reward_id'] == 2) {
                                                if ($people->employee_category == 'PERMANENT' || $people->employee_category == 'TELKOM') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata), 0);
                                                } else if ($people->employee_category == 'CONTRACT') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexTunjCuti * $basedata - $people->tunjangan_rekomposisi), 0);
                                                } else {
                                                    echo '-';
                                                }


                                            } elseif ($x['amount'] == 0) {
                                                echo '-';
                                            } elseif ($x['mst_reward_id'] == 13) {
                                                echo $formatter->asDecimal(Html::encode($x['amount']), 1) . ' Hari';
                                            } else if ($item['category_name'] == 'Take Home Pay') {
                                                if ($r == 'Gaji Dasar') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->salary), 0);
                                                } else if ($r == 'Tunjangan Biaya Hidup') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan), 0);
                                                } else if ($r == 'Tunjangan Jabatan') {
                                                    if ($people->tunjangan_jabatan > 0) {
                                                        echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan_jabatan), 0);
                                                    } else {
                                                        echo '-';
                                                    }
                                                } else if ($r == 'Tunjangan Rekomposisi') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan_rekomposisi), 0);
                                                } //PMK
                                                else if ($r == 'Penghargaan Masa Kerja') {
                                                    if ($people->employee_category == 'PERMANENT') {
                                                        //PMK => KELIPATAN 5 TAHUN S/D 30 TAHUN
                                                        if ($masaKerja == 60) {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK5 * $basedata), 0);
                                                        } else if ($masaKerja == 120) {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK10 * $basedata), 0);
                                                        } else if ($masaKerja == 180) {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK15 * $basedata), 0);
                                                        } else if ($masaKerja == 240) {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK20 * $basedata), 0);
                                                        } else if ($masaKerja == 300) {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK25 * $basedata), 0);
                                                        } else if ($masaKerja == 360) {
                                                            echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($indexPMK30 * $basedata), 0);
                                                        } else {
                                                            echo '-';
                                                        }

                                                    }
                                                } //CUTI BESAR => 6 TAHUN = 72 BULAN
                                                else if ($r == 'Cuti Besar') {
                                                    if ($people->employee_category == 'PERMANENT') {
                                                        if ($masaKerja == 72 || $masaKerja == 144 || $masaKerja == 216 || $masaKerja == 288 || $masaKerja == 360 || $masaKerja == 432) {
                                                            echo $formatter->asDecimal(Html::encode($indexCutiBesar * $basedata), 0);
                                                        } else {
                                                            echo "-";
                                                        }
                                                    }
                                                } else if ($r == 'Uang Lembur') {
                                                    echo '-';
                                                } else {
                                                    echo number_format($x['amount'], 2);
                                                }
                                            } else {
                                                echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($x['amount']), 0);
                                            }

                                            ?>
                                        </td>

                                        <?php if ($item['category_name'] == 'Take Home Pay') { ?>
                                            <td class="col-sm-4 table-detail-val-number">
                                                <?php if ($r == 'Gaji Dasar') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->salary * 12), 0);
                                                } else if ($r == 'Tunjangan Biaya Hidup') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan * 12), 0);
                                                } else if ($r == 'Tunjangan Jabatan') {
                                                    if ($people->tunjangan_jabatan > 0) {
                                                        echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan_jabatan * 12), 0);
                                                    } else {
                                                        echo '-';
                                                    }
                                                } else if ($r == 'Tunjangan Rekomposisi') {
                                                    echo "<span class='pull-left'>Rp. </span>" . $formatter->asDecimal(Html::encode($people->tunjangan_rekomposisi * 12), 0);
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                        <?php } ?>

                                    </tr>
                                <?php }
                            } ?>

                            <?php if ($item['category_name'] == 'Take Home Pay') { ?>
                                <tr>
                                    <td class="col-sm-4 table-total-val">Total</td>
                                    <td class="col-sm-4 table-total-val table-detail-val-number">
                                        <span class='pull-left'>Rp. </span><?= $formatter->asDecimal(Html::encode($people->salary + $people->tunjangan + $people->tunjangan_jabatan + $people->tunjangan_rekomposisi), 0) ?>
                                    </td>
                                    <td class="col-sm-4 table-total-val table-detail-val-number">
                                        <span class='pull-left'>Rp. </span><?= $formatter->asDecimal(Html::encode(($people->salary * 12) + ($people->tunjangan * 12) + ($people->tunjangan_jabatan * 12) + ($people->tunjangan_rekomposisi * 12)), 0) ?>
                                    </td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                        <span class="syarat">*)Syarat dan ketentuan berlaku</span>
                    </div>
                    <div class="modal-footer modal-custom-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn bt-close-modal" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>

                    </div>

                <?php }
            } ?>
        </div>
    </div>
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
    .table-total-description{
        border-top: none !important;
    }
    .box{
        margin-bottom: 6px;
    }
    .col-sm-6{
        padding-right: 8px;
    }
CSS;

$this->registerCss($script);
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    var base_uri = window.location.origin;
    $url = base_uri + '/new/hcmcollaboration/reward/web/index.php?r=site/options';
    //test
    //end test
    // Replace ./data.json with your JSON feed
    fetch($url).then(response => {
        return response.json();
    }).then(data => {
        // Work with JSON data here
        $('#txt-search').keyup(function () {
            var searchField = $(this).val();
            //console.log(searchField);


            //console.log(searchField)
            if (searchField === '') {
                $('#filter-records').html('');
                $('.class-default').show();
                $('.tag-title-reward').show();
                return;
            }

            var regex = new RegExp(searchField, "i");
            var output = '<div class="col-sm-6">';
            var count = 1;
            $.each(data, function (key, val) {
                if (val.reward_name.search(regex) !== -1) {
                    output += '<div class="box box-custom box-widget box-custom">';
                    output += '<div class="box-body" style="margin-right: 10px">';
                    output += '<table class="card-data-table">';
                    output += '<tbody><tr>';
                    output += '<td colspan="2"><h5 class="card-title">' + val.reward_name + '</h5></td>';
                    output += '</tr>';
                    output += '<tr>';
                    output += '</tr>';
                    output += '<tr class="card-margin-img-val">';
                    output += '<td style="vertical-align:top; width:100px;">';
                    output += '<img class="img-responsive card-image-search" src="' + val.icon + '" alt="' + val.reward_name + '" style="vertical-align: middle;">';
                    output += '</td>';
                    //value
                    output += '<td>';
                    output += '<table class="table-description" width="100%">';
                    output += '<tbody><tr>';
                    output += '<td class="rupiah-content"><h5>Rp. 8.832.000</h5></td>';
                    output += '</tr>';
                    output += '</tbody></table>';
                    output += '</td>';
                    //end value

                    //search-description
                    output += '<table class="table-description" width="100%">';
                    output += '<tbody><tr>';
                    output += '<div class="search-tiles"><h5>Deskripsi:</h5></div>';
                    output += '<td class="search-description">' + val.description + '</td>';
                    output += '</tr>';
                    output += '</tbody></table>';
                    // End of search-description

                    output += '</tbody></table>';
                    output += '</div>';
                    output += '</div>';
                    if (count % 2 == 0) {
                        output += '</div><div class="col-sm-6">'
                    }
                    count++;
                }

            });
            output += '</div>';
            $('#filter-records').html(output);
            $('.class-default').hide();
            $('.tag-title-reward').hide();

        });
        //console.log(data);
    }).catch(err => {
        // Do something for an error here


    });

    $('#table-data #money-data').each(function () {
        var cellText = $(this).text();
        if (cellText.indexOf('Hari') >= 0) {
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

    $(document).ready(function () {
        $(".content-header").text("");
        $(".content").css("padding-top", "0px");
        $(".content").css("margin-top", "-10px");
    });
</script>

