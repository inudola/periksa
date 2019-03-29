<?php

use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use reward\models\SimulationDetailSearch;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Projection Monitoring';
//$this->params['breadcrumbs'][] = $this->title;

?>

    <!--box list select option-->
    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <div class="col-lg-4">

                <input type="hidden" value="<?= $currentReal ?>" disabled class="form-control">
            </div>

            <?php if (!empty($list)) { ?>
                <div class="col-lg-offset-8">
                    <h4>Simulation (Ori)</h4>
                    <?php if (!empty($list)) { ?>
                        <?= Html::dropDownList('list', null, $list, ['class' => 'dependent-input form-control', 'id' => 'list', 'data-next' => 'alternatif_id', 'prompt' => '- Select Simulation -']) ?>
                    <?php } ?>
                </div>
            <?php } ?>


        </div>
    </div>
    <!-- /.box list select option-->

    <div id="tabel">
        <!--box grafik-->
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-line-chart"></i> Comparison Grafik</h3>
            </div>
            <div class="box-body">
                <?php
                echo
                Highcharts::widget([
                    'options' => [
                        'credits' => ['enabled' => false],
                        'chart' => [
                            'type' => 'line'
                        ],
                        'title' => ['text' => ''],
                        'xAxis' => [
                            'categories' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                            'title' => ['text' => 'Bulan'],
                            //'categories' => $data['bulan'],

                        ],
                        'yAxis' => [
                            'title' => ['text' => 'Value'],
                        ],

                        'series' =>

                            [
                                //['name' => 'Realization', 'data' => [0, 0, 0, 0, 0, 4000, 5000, 7000, 3000, 1000, 0, 4000]],
                                ['name' => 'Realization', 'data' => []],
                                ['name' => 'Projection (Ori)', 'data' => []],
                                //['name' => 'Projection', 'data' => [0, 0, 0, 1000, 0, 4000, 1000, 0, 4000, 5000, 7000, 3000,]],
                                //['name' => 'Realization', 'data' => $data['realization']],
                                //['name' => 'Projection', 'data' => $data['projection']],

                            ],


                    ]
                ]);

                ?>
            </div>
        </div>
        <!-- /.box grafik-->

        <div class="row">
            <div class="col-md-12">

            </div>
            <!-- /.col -->
        </div>
    </div>


<?php

$urlData = Url::to(['monitoring/get-data']);


$js = <<<js

$("#list").on("change",function(){
$.ajax({
url:"{$urlData}",
type: "GET",
//data:"id="+$(this).val(),
data: {id:$(this).val(), mode:''},
success:function(data){
$("#tabel").html(data);
}
});
});
js;

$this->registerJs($js);

$jsOpt = '$("#alternatif_id").on("change", function() {
	var value = $(this).val(),
	    text = $("#alternatif_id option:selected").text()

	$.ajax({
		url: "' . Url::to(['monitoring/get-data-tabel']) . '",
		data: {id: value, mode: text},
	
		success: function(data) {
			$("#tabel").html(data);
		}
	});
});';
$this->registerJs($jsOpt);


$jsAlt = '$(".dependent-input").on("change", function() {
	var value = $(this).val(),
	    text = $(this).text()
		obj = $(this).attr("id"),
		next = $(this).attr("data-next");
	
	$.ajax({
		url: "' . Yii::$app->urlManager->createUrl('monitoring/get-alternatif') . '",
		data: {value: value, obj: obj},
		type: "POST",
		success: function(data) {
			$("#" + next).html(data);
		}
	});
});';
$this->registerJs($jsAlt);
?>