                             <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nome_usuario')->unique();
            $table->string('biografia')->nullable();
            $table->string('email')->unique();
            $table->string('senha');
            $table->binary('foto_perfil')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $mydeiImagePath = public_path('assets/Mydei_icon.jpeg');
        $acheronImagePath = public_path('assets/Acheron_icon.webp');
        $polyxiaImagePath = public_path('assets/Polyxia_icon.webp');

        $mydeiImageData = file_exists($mydeiImagePath) ? base64_encode(file_get_contents($mydeiImagePath)) : null;
        $acheronImageData = file_exists($acheronImagePath) ? base64_encode(file_get_contents($acheronImagePath)) : null;
        $polyxiaImageData = file_exists($polyxiaImagePath) ? base64_encode(file_get_contents($polyxiaImagePath)) : null;

        DB::table('users')->insert([
            [
                'nome' => 'Mydei',
                'nome_usuario' => 'Mydeimos',
                'email' => 'mydeimos@gmail.com',
                'senha' => Hash::make('senha123'),
                'foto_perfil' => $mydeiImageData,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'Acheron',
                'nome_usuario' => 'AcheronIX',
                'email' => 'acheron@gmail.com',
                'senha' => Hash::make('IX123'),
                'foto_perfil' => $acheronImageData,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'Polyxia',
                'nome_usuario' => 'PolyxiaUser',
                'email' => 'polyxia@gmail.com',
                'senha' => Hash::make('senha123'),
                'foto_perfil' => $polyxiaImageData,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
