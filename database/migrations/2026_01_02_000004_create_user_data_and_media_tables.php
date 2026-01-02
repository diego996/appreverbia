<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedInteger('user_id')->nullable();
            $table->string('cognome')->nullable();
            $table->string('nome')->nullable();
            $table->string('consenso_al_trattamento_dei_dati_con_finalita_di_marketing')->nullable();
            $table->string('scadenza_consenso_trattamento_dati_a_fini_di_marketing')->nullable();
            $table->string('consenso_all_invio_di_comunicazioni_a_fini_di_servizio')->nullable();
            $table->string('scadenza_consenso_all_invio_di_comunicazioni_a_fini_di_servi')->nullable();
            $table->string('luogo_di_nascita')->nullable();
            $table->string('data_di_nascita')->nullable();
            $table->string('codice_fiscale')->nullable();
            $table->string('data_registrazione')->nullable();
            $table->string('ultimo_accesso_utente')->nullable();
            $table->string('genere')->nullable();
            $table->string('categoria')->nullable();
            $table->string('cid')->nullable();
            $table->string('origine')->nullable();
            $table->string('cap')->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('citta')->nullable();
            $table->string('provincia')->nullable();
            $table->string('validita_certificato_medico')->nullable();
            $table->string('tessere')->nullable();
            $table->string('stato_utente')->nullable();
            $table->string('blocco')->nullable();
            $table->string('stato_utente_1')->nullable();
            $table->string('telefono')->nullable();
            $table->string('cellulare')->nullable();
            $table->string('numero_cellulare_non_raggiungibile')->nullable();
            $table->string('blocco_invio_sms')->nullable();
            $table->string('note_su_blocco_invio_sms')->nullable();
            $table->string('e_mail')->nullable();
            $table->string('email_non_raggiungibile')->nullable();
            $table->string('blocco_invio_email')->nullable();
            $table->string('note_su_blocco_invio_email')->nullable();
            $table->string('consulente_principale')->nullable();
            $table->string('consulente_tecnico')->nullable();
            $table->string('credito_tessera')->nullable();
            $table->string('borsellino_elettronico')->nullable();
            $table->string('num_tessera_associazione')->nullable();
            $table->string('num_tessera_associazione_1')->nullable();
            $table->string('scadenza_tessera_associazione')->nullable();
            $table->string('app_utente')->nullable();
            $table->string('numero_accessi')->nullable();
            $table->string('numero_presenze')->nullable();
            $table->string('unnamed')->nullable();
            $table->timestamps();
            $table->text('notes')->nullable();

            $table->index('user_id', 'user_data_user_id_index');
            $table->foreign('user_id', 'user_data_user_id_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        Schema::create('user_media', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('file_path');
            $table->string('file_type', 100);
            $table->string('title')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();

            $table->index('user_id', 'user_media_user_id_idx');
            $table->foreign('user_id', 'user_media_user_id_fk')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_media');
        Schema::dropIfExists('user_data');
    }
};
