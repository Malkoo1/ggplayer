<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create conversations table
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('private'); // private or group
            $table->string('name')->nullable(); // for group chats
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_muted')->default(false);
            $table->timestamps();
        });

        // Create conversation participants table
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
        });

        // Create messages table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('media_id')->nullable()->after('message');
            $table->foreign('media_id')->references('id')->on('shared_media')->onDelete('set null');
            $table->text('message');
            $table->string('status')->default('sent'); // sent, delivered, read
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
        });

        // Create shared media table
        Schema::create('shared_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('type'); // image, video, file
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shared_media');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
    }
};
