<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Radius;

class RadiusController extends Controller
{
	public function index() {
		return Radius::firstOrFail();
	}

	public function store(Request $request) {
		$request->validate([
		'lat' => 'required',
		'long' => 'required',
		'radius' => 'required',
		]);
		Radius::query()->delete();
		Radius::create($request->all());
	}
}

