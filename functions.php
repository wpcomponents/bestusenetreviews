<?php

namespace BestUsenetReviews\Theme;

foreach ( \glob( __DIR__ . '/*/*.php' ) as $file ) {
	require_once $file;
}
