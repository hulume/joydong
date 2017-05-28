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
	public function up() {
		Schema::create('wesite', function (Blueprint $table) {
			$table->increments('id');
			$table->string('label', 30);
			$table->unsignedTinyInteger('type')->defalut(1); // 1.mainbar 2.guide 3.theme
			$table->unsignedTinyInteger('order')->default(1);
			$table->string('route', 20)->nullable();
			$table->string('icon', 20)->nullable();
			$table->string('color', 10)->nullable();
			$table->string('theme_img')->nullable();
			$table->boolean('is_url')->default(false);
			$table->json('link')->nullable();
			$table->timestamps();
		});

		// Schema::create('we_pages', function (Blueprint $table) {
		// 	$table->increments('id');
		// 	$table->string('title', 30);
		// 	$table->string('thumb_url');
		// 	$table->string('url');
		// 	$table->unsignedTinyInteger('we_menu_id');
		// 	$table->string('digest')->nullable();
		// 	$table->string('author', 20)->nullable();
		// 	$table->timestamps();
		// });
	}
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('page_menu');
		Schema::dropIfExists('we_menus');
		Schema::dropIfExists('we_pages');
	}
}
