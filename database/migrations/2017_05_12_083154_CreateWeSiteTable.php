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
		Schema::create('we_menus', function (Blueprint $table) {
			$table->increments('id');
			$table->string('label', 30);
			$table->boolean('is_mainbar')->defalut(false);
			$table->unsignedTinyInteger('order')->nullable();
			$table->string('route', 20)->nullable();
			$table->string('icon', 20)->nullable();
			$table->string('color', 10)->nullable();
			$table->timestamps();
		});

		Schema::create('we_pages', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title', 30);
			$table->string('thumb_url');
			$table->string('url');
			$table->string('digest')->nullable();
			$table->string('author', 20)->nullable();
			$table->timestamps();
		});
	}
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('wesite_menus');
		Schema::dropIfExists('wesite_posts');
	}
}
