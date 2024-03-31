<?php

namespace App\controllers;

use App\utils\Helper;

class HomeController
{
	public function index()
	{
		if (Helper::getPrefixUrl() !== '') {
			Helper::redirectTo('/');
		}
		Helper::renderPage('/home/index.php');
	}
	public function create()
	{
	}
	public function store()
	{
	}
	public function edit()
	{
	}
	public function update()
	{
	}
	public function delete()
	{
	}
}
