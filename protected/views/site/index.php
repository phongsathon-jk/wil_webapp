<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<h1>Attractions in Chiang Mai</h1>
<div class="row" id="search_option">
	<div class="col-md-6 col-md-offset-3">
		<div class="row">
			<div class="col-md-6" id="dropdown_ajax">
				dropdown
			</div>
			<div class="col-md-6" id="autocomplete_ajax">
				autocomplete
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" style="text-align: center;">
				<a href="<?php echo Yii::app()->request->url; ?>restaurant">check-out the restaurants</a>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-2" id="twitter">
		twitter api
	</div>
	<div class="col-md-8" id="main_content">
		<?php
		$places = Place::model()->findAll();
		foreach($places as $place):
			?>
			<div class="row" id="single_place">
				<div class="col-md-4">
					<a href="<?php echo Yii::app()->request->url; ?>view?id=<?php echo $place->id; ?>">
						<img class="img-responsive img-thumbnail" src="<?php //echo $place->pic; ?>" alt="<?php echo $place->name; ?>">
					</a>
				</div>
				<div class="col-md-8" id="short_detail">
					<a href="<?php echo Yii::app()->request->url; ?>view?id=<?php echo $place->id; ?>">
						<label><?php echo $place->name; ?></label>
					</a>
					<p><?php echo $place->detail; ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="col-md-2" id="weather">
		<?php
		$weather = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=chiangmai&units=metric"));
		?>
		<img src="http://openweathermap.org/img/w/<?php echo $weather->weather[0]->icon; ?>.png" alt="<?php echo $weather->weather[0]->main; ?>">
		<p>Condition: <?php echo $weather->weather[0]->main; ?></p>
		<p>Temperature: <?php echo $weather->main->temp; ?> &#8451;</p>
		<p>Min. Temp: <?php echo $weather->main->temp_min; ?> &#8451;</p>
		<p>Max. Temp: <?php echo $weather->main->temp_max; ?> &#8451;</p>
	</div>
</div>
