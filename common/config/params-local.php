<?php

require __DIR__ . '/env.php';

return [
	Yii::setAlias('@imageurl', getenv('PUBLIC_DOMAIN')),
];