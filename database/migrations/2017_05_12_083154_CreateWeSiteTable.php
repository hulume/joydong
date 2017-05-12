<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeSiteTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	Schema::create('wx_menus', function (Blueprint $table) {
		$table->increments('id');
		$table->string('label', 30);
		$table->unsignedTinyInteger('order')->nullable();
		$table->string('route', 20)->nullable();
		$table->string('icon', 20)->nullable();
		$talbe->string('color', 10)->nullable();
		$table->timestamps();
	});

	Schema::create('wx_posts', function (Blueprint $table) {
		$table->increments('id');
		$table->string('title', 30);
		$table->string('thumb_url');
		$table->string('url');
		$talbe->string('digest')->nullable();
		$table->string()
		$table->timestamps();
	});

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('wx_menus');
		Schema::dropIfExists('wx_posts');
	}
}
