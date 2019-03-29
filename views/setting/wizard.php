<?php
/**
 * Created by IntelliJ IDEA.
 * User: MacNovo
 * Date: 1/31/2019
 * Time: 5:15 PM
 */

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel projection\models\SimulationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Walkthrough';
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html; ?>
<div class="content-responsive">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="main-wizard">

                        <div class="container-wizard">
                                <?php $form = ActiveForm::begin(['id' => 'signup-form', 'class' => 'signup-form', 'options' => ['method' => 'post', 'action' => 'simulation/create']]); ?>
                                <div>
                                    <h3>Base Salaries</h3>
                                    <fieldset>
                                        <h2>53111001 Base Salaries</h2>
                                        <p class="desc">Silahkan input parameter dibawah ini</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Poin (asumsi) karyawan tiap semester</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $asumsiPoint ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 1], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Batas minimal total score</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $totalPoint ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 2], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                    </fieldset>

                                    <h3>Overtime</h3>
                                    <fieldset>
                                        <h2>53111002 Overtime</h2>
                                        <p class="desc">Tidak ada yang perlu diatur</p>
                                        <p>Input nominal saja, biarkan kami (sistem) yang spread tiap bulan</p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>


                                    <h3>Performance Incentives</h3>
                                    <fieldset>
                                        <h2>53112001 Performance Incentives</h2>
                                        <p class="desc">Silahkan input parameter dibawah ini</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Nilai NKK</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $indexNkk ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 37], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Nilai NKU</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $indexNku ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 38], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Nilai NKI</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $indexNki ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 39], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                    </fieldset>

                                    <h3>Functional Allowances</h3>
                                    <fieldset>
                                        <h2>53121001 Functional Allowances</h2>
                                        <p class="desc">Pengaturan sama seperti Base Salaries</p>
                                        <p>Pastikan sudah mengisi parameter <i>Base Salaries</i></p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                    <h3>Living Cost Allowances</h3>
                                    <fieldset>
                                        <h2>53121002 Living Cost Allowances</h2>
                                        <p class="desc">Pengaturan sama seperti Base Salaries</p>
                                        <p>Pastikan sudah mengisi parameter <i>Base Salaries</i></p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                    <h3>Employees Income Tax</h3>
                                    <fieldset>
                                        <h2>53121003 Employees Income Tax</h2>
                                        <p class="desc">Silahkan input parameter dibawah ini</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Nilai tax</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $tax ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 10], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                    </fieldset>

                                    <h3>Employees BPJS</h3>
                                    <fieldset>
                                        <h2>53121004 Employees BPJS</h2>
                                        <p class="desc">Silahkan input parameter dibawah ini</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Jaminan Kecelakaan Kerja</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $iuranJKK ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 3], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">Jaminan Kematian</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $iuranJKM ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 4], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Jaminan Hari Tua</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $iuranJHT ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 5], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">Jaminan Pensiun</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $iuranJP ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 6], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Jaminan Kesehatan</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $iuranKes ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 7], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">Nilai maksimal Jaminan Pensiun</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $maxJP ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 19], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Nilai maksimal Jaminan Kesehatan</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $maxJkes ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 20], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>

                                    </fieldset>

                                    <h3>Other Allowance</h3>
                                    <fieldset>
                                        <h2>Other Allowance 8</h2>
                                        <p class="desc">Input Parameter dibawah ini</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">THR 1 Kali</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $indexTHR ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 23], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">THR 2 Kali</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $indexTHR2 ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 8], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Tunjangan Cuti</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $indexTunjCuti ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 9], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">Uang Saku Akhir Program</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $indexUangSakuAP ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 22], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Tunjangan Akhir Tahun</label>
                                                <div class="fieldset-content">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="<?php echo $indexTA ?>" required/>
                                                        <span class="input-group-btn">
                                                            <?= Html::a('Edit', ['setting/update', 'id' => 26], ['class' => 'btn btn-info btn-flat']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                    </fieldset>

                                    <h3>Tunjangan Rekomposisi</h3>
                                    <fieldset>
                                        <h2>Tunjangan Rekomposisi</h2>
                                        <p class="desc">Pengaturan sama seperti Base Salaries</p>
                                        <p>Pastikan sudah mengisi parameter <i>Base Salaries</i></p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                    <h3>Medical Allowance</h3>
                                    <fieldset>
                                        <h2>53122001 Medical Allowance</h2>
                                        <p class="desc">Tidak ada yang perlu diatur</p>
                                        <p>Input nominal saja, boleh beda tiap bulan. Lalu biarkan kami (sistem) yang
                                            spread nilainya</p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                    <h3>Relocation (Mutation)</h3>
                                    <fieldset>
                                        <h2>53122002 Relocation (Mutation)</h2>
                                        <p class="desc">Tidak ada yang perlu diatur</p>
                                        <p>Input nominal saja, biarkan kami (sistem) yang spread tiap bulan</p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                    <h3>Pension Benefit</h3>
                                    <fieldset>
                                        <h2>53210001 Pension Benefit</h2>
                                        <p class="desc">Tidak ada yang perlu diatur</p>
                                        <p>Input nominal saja, biarkan kami (sistem) yang spread tiap bulan</p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                    <h3>Long Service Award</h3>
                                    <fieldset>
                                        <h2>53210002 Long Service Award</h2>
                                        <p class="desc">Tidak ada yang perlu diatur</p>
                                        <p>Input nominal saja, biarkan kami (sistem) yang spread tiap bulan</p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                    <h3>Long Service Leave</h3>
                                    <fieldset>
                                        <h2>53210003 Long Service Leave</h2>
                                        <p class="desc">Tidak ada yang perlu diatur</p>
                                        <p>Input nominal saja, biarkan kami (sistem) yang spread tiap bulan</p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                    <h3>Other Employee Benefit</h3>
                                    <fieldset>
                                        <h2>53210004 Other Employee Benefit</h2>
                                        <p class="desc">Tidak ada yang perlu diatur</p>
                                        <p>Input nominal saja, biarkan kami (sistem) yang spread tiap bulan</p>
                                        <div class="fieldset-content">

                                        </div>
                                    </fieldset>

                                </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>


    </div>
</div>
