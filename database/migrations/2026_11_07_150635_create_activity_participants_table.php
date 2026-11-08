    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('activity_participants', function (Blueprint $table) {
                $table->id('activity_participant_id');
                $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
                $table->foreignId('activity_id')->constrained('activities', 'activity_id')->cascadeOnDelete();
                $table->datetime('joined_at');
                $table->string('status');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('activity_participants');
        }
    };
