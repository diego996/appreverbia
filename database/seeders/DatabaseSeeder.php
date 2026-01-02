<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Course;
use App\Models\CourseBooking;
use App\Models\CourseOccurrence;
use App\Models\CourseWaitlist;
use App\Models\Item;
use App\Models\ItemProperty;
use App\Models\Lead;
use App\Models\Log;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\SupportAttachment;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\UserData;
use App\Models\UserMedia;
use App\Models\UsersDatiVeri;
use App\Models\Wallet;
use App\Models\WorkoutPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $branchMilano = Branch::create([
            'name' => 'Milano Centro',
            'address' => 'Via Roma 10, Milano',
            'phone' => '+39 02 000000',
            'email' => 'milano@example.com',
            'status' => 'active',
        ]);

        $branchTorino = Branch::create([
            'name' => 'Torino Porta Nuova',
            'address' => 'Corso Vittorio 55, Torino',
            'phone' => '+39 011 000000',
            'email' => 'torino@example.com',
            'status' => 'active',
        ]);

        $trainer = User::create([
            'branch_id' => $branchMilano->id,
            'name' => 'Luca Trainer',
            'email' => 'trainer@example.com',
            'phone' => '+39 333 1111111',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'status' => 'active',
            'privacy_accepted' => true,
            'newsletter_opt_in' => false,
        ]);

        $staff = User::create([
            'branch_id' => $branchMilano->id,
            'name' => 'Giulia Staff',
            'email' => 'staff@example.com',
            'phone' => '+39 333 2222222',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => 'active',
            'privacy_accepted' => true,
            'newsletter_opt_in' => true,
        ]);

        $customer = User::create([
            'branch_id' => $branchTorino->id,
            'seller_id' => $staff->id,
            'name' => 'Marco Cliente',
            'email' => 'marco@example.com',
            'phone' => '+39 333 3333333',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
            'privacy_accepted' => true,
            'newsletter_opt_in' => false,
        ]);

        UsersDatiVeri::create([
            'branch_id' => $branchTorino->id,
            'name' => 'Marco Cliente',
            'email' => 'marco.vero@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        UserData::create([
            'user_id' => $customer->id,
            'cognome' => 'Rossi',
            'nome' => 'Marco',
            'codice_fiscale' => 'RSSMRC80A01H501Z',
            'citta' => 'Torino',
            'provincia' => 'TO',
            'data_registrazione' => now()->toDateString(),
            'ultimo_accesso_utente' => now()->toDateString(),
            'notes' => 'Profilo importato da CRM.',
        ]);

        UserMedia::create([
            'user_id' => $customer->id,
            'file_path' => 'uploads/documents/id-marco.pdf',
            'file_type' => 'application/pdf',
            'title' => 'Documento identita',
            'uploaded_at' => now(),
        ]);

        $property = ItemProperty::create([
            'name' => 'Abbonamenti',
            'active' => true,
        ]);

        $monthlyItem = Item::create([
            'item_property_id' => $property->id,
            'descrizione' => 'Abbonamento mensile',
            'token' => 10,
            'validity_months' => 1,
            'costo' => 49.99,
            'active' => true,
        ]);

        $course = Course::create([
            'branch_id' => $branchMilano->id,
            'trainer_id' => $trainer->id,
            'title' => 'Yoga Base',
            'description' => 'Corso introduttivo di yoga.',
            'video_url' => null,
        ]);

        $occurrence = CourseOccurrence::create([
            'course_id' => $course->id,
            'date' => now()->addDays(2)->toDateString(),
            'start_time' => '18:00:00',
            'end_time' => '19:00:00',
            'max_participants' => 12,
        ]);

        CourseBooking::create([
            'occurrence_id' => $occurrence->id,
            'user_id' => $customer->id,
            'booked_at' => now(),
            'status' => 'booked',
        ]);

        CourseWaitlist::create([
            'occurrence_id' => $occurrence->id,
            'user_id' => $staff->id,
            'added_at' => now(),
            'status' => 'waiting',
        ]);

        WorkoutPlan::create([
            'trainer_id' => $trainer->id,
            'title' => 'Programma forza 4 settimane',
            'description' => 'Allenamenti progressivi con focus su core e mobilita.',
        ]);

        $payment = Payment::create([
            'user_id' => $customer->id,
            'item_id' => $monthlyItem->id,
            'amount' => 4999,
            'currency' => 'EUR',
            'provider_ref' => Str::upper(Str::random(10)),
            'status' => 'paid',
            'paid_at' => now(),
            'meta' => ['channel' => 'card'],
        ]);

        Membership::create([
            'user_id' => $customer->id,
            'branch_id' => $branchTorino->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'status' => 'active',
            'payment_id' => $payment->id,
        ]);

        Wallet::create([
            'user_id' => $customer->id,
            'model_type' => Payment::class,
            'model_id' => $payment->id,
            'token_delta' => 10,
            'reason' => 'purchase',
            'provider' => 'stripe',
            'token' => 10,
            'meta' => ['note' => 'Accredito iniziale'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Lead::create([
            'branch_id' => $branchMilano->id,
            'source' => 'landing',
            'name' => 'Sara Bianchi',
            'email' => 'sara@example.com',
            'phone' => '+39 333 4444444',
            'message' => 'Vorrei informazioni sui corsi.',
            'received_at' => now()->subDay(),
            'status' => 'new',
            'kanban_order' => 1,
            'notes' => 'Ricontattare entro 24h.',
        ]);

        $conversation = SupportConversation::create([
            'user_id' => $customer->id,
            'branch_id' => $branchTorino->id,
            'title' => 'Problema accesso account',
            'assigned_to' => $staff->name,
            'unread_count' => 1,
            'last_message_at' => now(),
        ]);

        $message = SupportMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $customer->id,
            'branch_id' => $branchTorino->id,
            'sender_type' => 'customer',
            'text' => 'Non riesco ad accedere all app.',
            'has_attachments' => true,
            'sent_at' => now(),
        ]);

        SupportAttachment::create([
            'message_id' => $message->id,
            'disk' => 'public',
            'path' => 'support/screenshots/login.png',
            'filename' => 'login.png',
            'mime' => 'image/png',
            'size' => 24567,
            'uploaded_by_type' => 'customer',
            'uploaded_by_name' => $customer->name,
            'uploaded_at' => now(),
        ]);

        Log::create([
            'user_id' => $staff->id,
            'meta' => ['action' => 'seed', 'entity' => 'database'],
        ]);
    }
}
