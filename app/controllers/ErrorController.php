<?php

namespace App\controllers;
use App\utils\Helper;
class ErrorController {
	public function set404() {
		Helper::renderPage('/errors/notfound.php');

	}
}