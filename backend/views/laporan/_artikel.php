<?php

use backend\models\DokumenJdih;
use miloschuman\highcharts\Highcharts;

?>

<div class="box box-primary box-solid">
	<div class="box-header with-border">
		<b>Rekapitulasi Data Artikel</b>

	</div>

	<div class="box-body">
		<?php if (!empty($artikel)) : ?>
			<table class="table table-bordered table-striped ">
				<thead>
					<tr>
						<th>Jenis artikel</th>
						<th>Jumlah Koleksi</th>

					</tr>
				</thead>
				<tbody>
					<?php foreach ($artikel as $data) { ?>
						<tr>
							<td><?= $data->name ?></td>
							<td><?= $data->getJumlahKoleksi($data->singkatan) ?></td>

						</tr>
					<?php } ?>
				</tbody>
			</table>
		<?php endif; ?>


	</div>
</div>


<div class="box box-primary box-solid">
	<div class="box-header with-border">
		<b>Grafik Data Artikel</b>

	</div>

	<div class="box-body">
		<?php

		$produkhukum = \backend\models\TipeDokumen::find()
			->where(['parent_id' => 3])
			->orderBy(['id' => SORT_ASC])
			->all();
		foreach ($produkhukum as $values) {
			$produkpusat = DokumenJdih::find()
				->where(['singkatan_jenis' => $values['singkatan']])
				->count();

			//$a[0]= ($values['regional']); 
			//$c[]= ($values['regional']); 
			$b[] = array('type' => 'column', 'name' => $values['singkatan'], 'data' => array((int)$produkpusat));
		}



		echo Highcharts::widget([
			'scripts' => [
				'modules/exporting',
				'themes/grid-light',
			],
			'options' => [
				'title' => [
					'text' => 'REKAPITULASI PERATURAN',
				],
				'xAxis' => [
					'categories' => ['PRODUK HUKUM'],
				],
				'yAxis' => [
					'title' => ['text' => 'Jumlah Produk Hukum']
				],
				// 'labels' => [
				//     'items' => [
				//         [
				//             'html' => 'Total fruit consumption',
				//             'style' => [
				//                 'left' => '50px',
				//                 'top' => '18px',
				//                 'color' => new JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
				//             ],
				//         ],
				//     ],
				// ],
				'series' => $b
			],
		]);
		?>
	</div>
</div>