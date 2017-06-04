<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHealthTables extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('patients', function (Blueprint $table) {
			$table->increments('id');
			$table->string('openid', 66)->unique()->nullable();
			$table->string('identify', 20)->unique()->nullable(); //身份证
			$table->string('mobile', 12)->unique()->nullable(); // 手机
			$table->string('password', 66);
			$table->date('birthday', 12)->nullable();
			$table->unsignedTinyInteger('age')->nullable();
			$table->string('name', 10)->nullable();
			$table->string('paytype', 20)->nullable();
			$table->tinyInteger('sex')->unsigned()->default(0);
			$table->string('address')->nullable();
			$table->boolean('livetype')->default(false);
			$table->string('nation', 15)->nullable();
			$table->string('birthplace', 30)->nullable();
			$table->text('memo')->nullable();
			$table->timestamps();
		});
		Schema::create('archives', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('patient_id')->unsigned()->nullable();
			$table->string('name', 10)->unique();
			$table->string('check_unit', 32)->nullable();
			$table->string('doctor', 10)->nullable();
			$table->json('result')->nullable();
			$table->timestamps();
			$table->foreign('patient_id')
				->references('id')
				->on('patients')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->index(['created_at', 'check_unit']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');
		Schema::dropIfExists('patients');
		Schema::dropIfExists('archives');
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}
}
