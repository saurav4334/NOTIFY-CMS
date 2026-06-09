<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Coarse capabilities mapped to admin-panel areas. null = all (super admin).
        //   cms   → manage site content (settings, homepage, pricing, faq, seo, backup…)
        //   media → media library
        //   leads → contact leads inbox
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full system access.',
                'permissions' => null,
            ],
            [
                'name' => 'Content Manager',
                'slug' => 'content_manager',
                'description' => 'Manages all site content, media and leads.',
                'permissions' => ['cms', 'media', 'leads'],
            ],
            [
                'name' => 'Support Admin',
                'slug' => 'support_admin',
                'description' => 'Views, exports and responds to contact leads only.',
                'permissions' => ['leads'],
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
