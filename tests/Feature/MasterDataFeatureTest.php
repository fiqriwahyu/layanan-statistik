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
use Tests\TestCase;

class MasterDataFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_master_index_filters_records_on_the_server(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $parent = Categorie::create(['categorie_type' => 'Parent master']);
        $cases = [
            [Education::class, 'education_type', 'educations', 'admin.education.index', null],
            [Work::class, 'work_type', 'works', 'admin.work.index', null],
            [Media::class, 'media_type', 'medias', 'admin.media.index', null],
            [Service::class, 'service_type', 'services', 'admin.service.index', null],
            [Categorie::class, 'categorie_type', 'categories', 'admin.categorie.index', null],
            [SubCategorie::class, 'sub_categorie_type', 'subcategories', 'admin.subcategorie.index', $parent->id],
            [Purpose::class, 'purpose_type', 'purposes', 'admin.purpose.index', null],
        ];

        foreach ($cases as [$model, $field, $viewKey, $route, $parentId]) {
            $matching = [$field => 'Needle record'];
            if ($model === SubCategorie::class) $matching['categorie_id'] = $parentId;
            $other = [$field => 'Other record'];
            if ($model === SubCategorie::class) $other['categorie_id'] = $parentId;
            $model::create($matching);
            $model::create($other);

            $this->actingAs($admin)->get(route($route, ['search' => 'Needle']))
                ->assertOk()
                ->assertViewHas($viewKey, fn ($rows) => $rows->total() === 1 && $rows->first()->{$field} === 'Needle record');

            $this->actingAs($admin)->get(route($route, ['search' => 'no-match']))
                ->assertOk()
                ->assertViewHas($viewKey, fn ($rows) => $rows->total() === 0);
        }
    }

    public function test_master_create_edit_delete_and_pagination_keep_search_query(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $parent = Categorie::create(['categorie_type' => 'Parent CRUD']);
        $cases = [
            [Education::class, 'education_type', 'admin.education.store', 'admin.education.update', 'admin.education.destroy', 'admin.education.create', 'admin.education.edit', 'admin.education.index', []],
            [Work::class, 'work_type', 'admin.work.store', 'admin.work.update', 'admin.work.destroy', 'admin.work.create', 'admin.work.edit', 'admin.work.index', []],
            [Media::class, 'media_type', 'admin.media.store', 'admin.media.update', 'admin.media.destroy', 'admin.media.create', 'admin.media.edit', 'admin.media.index', []],
            [Service::class, 'service_type', 'admin.service.store', 'admin.service.update', 'admin.service.destroy', 'admin.service.create', 'admin.service.edit', 'admin.service.index', []],
            [Categorie::class, 'categorie_type', 'admin.categorie.store', 'admin.categorie.update', 'admin.categorie.destroy', 'admin.categorie.create', 'admin.categorie.edit', 'admin.categorie.index', []],
            [SubCategorie::class, 'sub_categorie_type', 'admin.subcategorie.store', 'admin.subcategorie.update', 'admin.subcategorie.destroy', 'admin.subcategorie.create', 'admin.subcategorie.edit', 'admin.subcategorie.index', ['categorie_id' => $parent->id]],
            [Purpose::class, 'purpose_type', 'admin.purpose.store', 'admin.purpose.update', 'admin.purpose.destroy', 'admin.purpose.create', 'admin.purpose.edit', 'admin.purpose.index', []],
        ];

        foreach ($cases as [$model, $field, $storeRoute, $updateRoute, $deleteRoute, $createRoute, $editRoute, $indexRoute, $extra]) {
            $this->actingAs($admin)->get(route($createRoute))->assertOk();
            $this->actingAs($admin)->post(route($storeRoute), array_merge([$field => 'Created master'], $extra))
                ->assertRedirect(route($indexRoute))->assertSessionHas('success');

            $row = $model::where($field, 'Created master')->firstOrFail();
            $this->actingAs($admin)->get(route($editRoute, $row))->assertOk();
            $this->actingAs($admin)->put(route($updateRoute, $row), array_merge([$field => 'Updated master'], $extra))
                ->assertRedirect(route($indexRoute))->assertSessionHas('success');
            $this->assertSame('Updated master', $row->fresh()->{$field});
            $this->actingAs($admin)->delete(route($deleteRoute, $row))
                ->assertRedirect(route($indexRoute))->assertSessionHas('success');
            $this->assertNull($row->fresh());
        }

        for ($number = 1; $number <= 16; $number++) {
            Education::create(['education_type' => "Needle education {$number}"]);
        }
        Education::create(['education_type' => 'Other education']);

        $this->actingAs($admin)->get(route('admin.education.index', ['search' => 'Needle']))
            ->assertViewHas('educations', fn ($rows) => $rows->total() === 16 && $rows->count() === 15)
            ->assertSee('search=Needle&amp;page=2', false);
    }

    public function test_foreign_key_restrictions_show_errors_and_keep_master_rows(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Categorie::create(['categorie_type' => 'Referenced category']);
        $subCategory = SubCategorie::create(['sub_categorie_type' => 'Referenced subcategory', 'categorie_id' => $category->id]);
        $education = Education::create(['education_type' => 'Referenced education']);
        $work = Work::create(['work_type' => 'Referenced work']);
        $media = Media::create(['media_type' => 'Referenced media']);
        $service = Service::create(['service_type' => 'Referenced service']);
        $purpose = Purpose::create(['purpose_type' => 'Referenced purpose']);
        $customer = Customer::create([
            'nama_lengkap' => 'Referensi', 'no_hp' => '081234567891', 'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Alamat', 'usia' => 30, 'education_id' => $education->id, 'work_id' => $work->id,
        ]);
        Transaction::create([
            'customer_id' => $customer->id, 'media_id' => $media->id, 'sub_categorie_id' => $subCategory->id,
            'service_id' => $service->id, 'purpose_id' => $purpose->id, 'data_diminta' => 'Permintaan',
        ]);

        $cases = [
            [$education, 'admin.education.destroy', 'Pendidikan masih digunakan'],
            [$work, 'admin.work.destroy', 'Pekerjaan masih digunakan'],
            [$media, 'admin.media.destroy', 'Media masih digunakan'],
            [$service, 'admin.service.destroy', 'Layanan masih digunakan'],
            [$purpose, 'admin.purpose.destroy', 'Tujuan masih digunakan'],
            [$category, 'admin.categorie.destroy', 'Kategori masih digunakan'],
            [$subCategory, 'admin.subcategorie.destroy', 'Subkategori masih digunakan'],
        ];

        foreach ($cases as [$row, $route, $message]) {
            $this->actingAs($admin)->from('/admin')->delete(route($route, $row))
                ->assertRedirect('/admin')->assertSessionHas('error', $message . ' dan tidak dapat dihapus.');
            $this->assertNotNull($row->fresh());
        }
    }
}
