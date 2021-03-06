<?php
$this->breadcrumbs=array(
    'Home' => Yii::app()->baseUrl,
    'Restaurant',
);
?>
<div class="container single_place">
        <h2>Top Restaurants in Chiang Mai <small>powered by <a href="https://foursquare.com/" target="_blank">Foursquare</a></small></h2>
        <ul>
            <?php
            foreach ($restaurants->response->groups[0]->items as $restaurant):
                $coordinate = $restaurant->venue->location->lat . "," . $restaurant->venue->location->lng;
                ?>
                <li>
                    <a href="https://www.google.co.th/maps/place/<?php echo $coordinate; ?>" target="_blank">
                        <?php echo $restaurant->venue->name; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

