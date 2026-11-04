<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Customer;
use App\Models\Education;
use App\Models\Media;
use App\Models\Purpose;
use App\Models\Service;
use App\Models\SubCategorie;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ActivePageRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_and_guest_authentication_pages_return_expected_responses(): void
    {
        foreach (['welcome', 'form-tamu', 'feedback.index', 'login', 'password.request'] as $route) {
            $this->get(route($route))->assertOk();
        }

        $this->get(route('welcome'))
            ->assertOk()
            ->assertDontSee('Masukkan No HP')
            ->assertDontSee('penilaianModal')
            ->assertDontSee('console.log');

        $this->get('/feedback/cek-nohp')->assertNotFound();
        $this->get(route('password.reset', ['token' => 'test-reset-token', 'email' => 'admin@example.test']))->assertOk();
        $this->get(route('register'))->assertNotFound();
        $this->post('/register', [])->assertMethodNotAllowed();
        $this->get('/up')->assertOk();
    }

    public function test_active_admin_superadmin_profile_and_feedback_views_render_with_fixtures(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => null]);
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $category = Categorie::create(['categorie_type' => 'Render Category']);
        $subcategory = SubCategorie::create(['sub_categorie_type' => 'Render Subcategory', 'categorie_id' => $category->id]);
        $education = Education::create(['education_type' => 'Render Education']);
        $work = Work::create(['work_type' => 'Render Work']);
        $media = Media::create(['media_type' => 'Render Media']);
        $service = Service::create(['service_type' => 'Render Service']);
        $purpose = Purpose::create(['purpose_type' => 'Render Purpose']);
        $customer = Customer::create([
            'nama_lengkap' => 'Render Customer',
            'no_hp' => '081234567890',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Render Address',
            'usia' => 30,
            'education_id' => $education->id,
            'work_id' => $work->id,
        ]);
        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'media_id' => $media->id,
            'sub_categorie_id' => $subcategory->id,
            'service_id' => $service->id,
            'purpose_id' => $purpose->id,
            'data_diminta' => 'Render requested data',
            'status_transaksi' => 'Sudah dilayani',
        ]);
        $token = str_repeat('a', 32);
        $transaction->update(['feedback_token_hash' => hash('sha256', $token)]);

        $admin = $admin->fresh();
        $this->actingAs($admin);
        foreach (['admin.dashboard', 'admin.education.index', 'admin.education.create', 'admin.work.index', 'admin.work.create', 'admin.media.index', 'admin.media.create', 'admin.service.index', 'admin.service.create', 'admin.categorie.index', 'admin.categorie.create', 'admin.subcategorie.index', 'admin.subcategorie.create', 'admin.purpose.index', 'admin.purpose.create', 'admin.customer.index', 'admin.transaction.index'] as $route) {
            $this->get(route($route))->assertOk();
        }

        foreach ([
            ['admin.education.edit', $education], ['admin.work.edit', $work], ['admin.media.edit', $media],
            ['admin.service.edit', $service], ['admin.categorie.edit', $category], ['admin.subcategorie.edit', $subcategory],
            ['admin.purpose.edit', $purpose], ['admin.customer.show', $customer], ['admin.transaction.show', $transaction],
        ] as [$route, $model]) {
            $this->get(route($route, $model))->assertOk();
        }

        $this->get(route('profile.edit'))->assertOk();
        $this->get(route('password.confirm'))->assertOk();
        $this->get(route('verification.notice'))->assertOk();
        $signedVerificationUrl = URL::temporarySignedRoute('verification.verify', now()->addMinutes(5), [
            'id' => $admin->id,
            'hash' => sha1($admin->getEmailForVerification()),
        ]);
        $this->get($signedVerificationUrl)->assertRedirect(route('admin.dashboard', absolute: false) . '?verified=1');
        $this->get(route('feedback.create', $token))->assertOk();

        $this->get(route('superadmin.admin.index'))->assertForbidden();

        $this->actingAs($superadmin);
        foreach (['superadmin.admin.index', 'superadmin.admin.create'] as $route) {
            $this->get(route($route))->assertOk();
        }
        $this->get(route('superadmin.admin.edit', $admin))->assertOk();
        $this->get(route('admin.dashboard'))->assertOk();
    }
}
