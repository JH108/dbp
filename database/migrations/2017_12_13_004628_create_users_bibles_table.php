<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersBiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

	    Schema::create('projects', function (Blueprint $table) {
		    $table->string('id', 24)->primary();
		    $table->string('name');
		    $table->string('url_avatar')->nullable();
		    $table->string('url_avatar_icon')->nullable();
		    $table->string('url_site')->nullable();
		    $table->text('description')->nullable();
		    $table->boolean('sensitive')->default(false);
		    $table->timestamps();
	    });

	    Schema::create('project_oauth_providers', function (Blueprint $table) {
		    $table->char('id', 8)->primary();
		    $table->string('project_id', 24);
		    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('name');
		    $table->string('client_id');
		    $table->text('client_secret');
		    $table->string('callback_url');
		    $table->string('callback_url_alt')->nullable();
		    $table->text('description');
		    $table->timestamps();
	    });

	    Schema::create('project_members', function (Blueprint $table) {
		    $table->string('user_id', 64);
		    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('project_id', 24);
		    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('role');
		    $table->boolean('subscribed')->default(false);
		    $table->timestamps();
	    });

	    Schema::create('user_accounts', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('user_id', 64);
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
		    $table->string('provider_id');
		    $table->string('provider_user_id');
		    $table->unique(['user_id','provider_id']);
		    $table->timestamps();
	    });

	    Schema::create('user_notes', function (Blueprint $table) {
	    	$table->increments('id');
		    $table->string('user_id', 64);
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('bible_id', 12);
		    $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		    $table->char('book_id', 3);
		    $table->foreign('book_id')->references('id')->on('books')->onUpdate('cascade')->onDelete('cascade');
		    $table->tinyInteger('chapter')->unsigned();
		    $table->tinyInteger('verse_start')->unsigned();
		    $table->tinyInteger('verse_end')->unsigned()->nullable();
		    $table->string('project_id', 24)->nullable();
		    $table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade')->onDelete('cascade');
		    $table->text('notes')->nullable();
		    $table->boolean('bookmark')->default(false);
		    $table->timestamps();
	    });

	    Schema::create('user_highlights', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('user_id', 64);
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('bible_id', 12);
		    $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		    $table->char('book_id', 3);
		    $table->foreign('book_id')->references('id')->on('books');
		    $table->tinyInteger('chapter')->unsigned();
		    $table->tinyInteger('verse_start')->unsigned();
		    $table->string('reference');
		    $table->string('project_id', 24)->nullable();
		    $table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade')->onDelete('cascade');
		    $table->smallInteger('highlight_start')->unsigned();
		    $table->integer('highlighted_words')->unsigned();
		    $table->string('highlighted_color', 24);
		    $table->timestamps();
	    });

	    Schema::create('user_note_tags', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('note_id')->unsigned();
		    $table->foreign('note_id')->references('id')->on('user_notes')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('type', 64);
		    $table->string('value', 64);
		    $table->timestamps();
	    });

	    Schema::create('access_groups', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('name', 64);
		    $table->text('description');
		    $table->timestamps();
	    });

	    Schema::create('access_types', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('name', 24);
		    $table->char('country_id', 2)->nullable();
		    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
		    $table->char('continent_id', 2)->nullable();
		    //$table->foreign('continent_id')->references('continent')->on('countries')->onUpdate('cascade');
		    $table->boolean('allowed');
		    $table->timestamps();
	    });

	    Schema::create('access_type_translations', function (Blueprint $table) {
		    $table->primary(['access_type_id', 'iso'],'uq_access_type_translations');
		    $table->integer('access_type_id')->unsigned();
		    $table->foreign('access_type_id')->references('id')->on('access_types')->onUpdate('cascade')->onDelete('cascade');
		    $table->char('iso', 3);
		    $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('name', 64);
		    $table->string('description');
		    $table->timestamps();
	    });

	    Schema::create('access_group_types', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('access_group_id')->unsigned();
		    $table->foreign('access_group_id')->references('id')->on('access_groups')->onUpdate('cascade')->onDelete('cascade');
		    $table->integer('access_type_id')->unsigned();
		    $table->foreign('access_type_id')->references('id')->on('access_types')->onUpdate('cascade')->onDelete('cascade');
		    $table->timestamps();
	    });

	    Schema::create('access_group_filesets', function (Blueprint $table) {
		    $table->integer('access_group_id')->unsigned();
		    $table->foreign('access_group_id')->references('id')->on('access_groups')->onUpdate('cascade')->onDelete('cascade');
		    $table->char('hash_id',12)->index();
		    $table->foreign('hash_id')->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
		    $table->timestamps();
	    });

	    Schema::create('access_group_keys', function (Blueprint $table) {
		    $table->integer('access_group_id')->unsigned();
		    $table->foreign('access_group_id')->references('id')->on('access_groups')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('key_id', 64);
		    $table->foreign('key_id')->references('key')->on('user_keys')->onUpdate('cascade')->onDelete('cascade');
		    $table->timestamps();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('user_note_tags');
	    Schema::dropIfExists('user_accounts');
        Schema::dropIfExists('user_notes');
	    Schema::dropIfExists('user_highlights');
	    Schema::dropIfExists('project_oauth_providers');
	    Schema::dropIfExists('project_members');
	    Schema::dropIfExists('projects');
	    Schema::dropIfExists('access_group_types');
	    Schema::dropIfExists('access_group_filesets');
	    Schema::dropIfExists('access_group_keys');
	    Schema::dropIfExists('access_type_translations');
	    Schema::dropIfExists('access_types');
	    Schema::dropIfExists('access_groups');
    }
}
