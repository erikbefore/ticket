<?php

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->deleteAllPermissionDB();
        $this->createAllPermission();
        $this->createPermissionForRoleClient();
        $this->createPermissionForRoleSuporte();
        $this->createPermissionForRoleCoordenacao();
        $this->createPermissionForRoleAdmin();
        $this->createPermissionForRoleSuperAdmin();
    }

    public function deleteAllPermissionDB(){

        Model::unguard();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
    }

    public function createAllPermission(){


        $permissoes = (new User())->getPermissionsExisting();

        foreach ($permissoes as $nomePermissao){
            Permission::create(['name' => $nomePermissao]);
        }
    }

    public function createPermissionForRoleClient(){

        Role::create(['name' => 'Cliente'])
            ->givePermissionTo(
                [
                    \App\Helpers\Permission::TICKET_CREATE,
                    \App\Helpers\Permission::TICKET_EDIT
                ]);
    }

    public function createPermissionForRoleSuporte(){

        $role = Role::create(['name' => \App\Helpers\Role::ROLE_SUPORTE]);
        $role->givePermissionTo(Permission::all());
    }

    public function createPermissionForRoleCoordenacao(){

        $role = Role::create(['name' => \App\Helpers\Role::ROLE_COORDENACAO]);
        $role->givePermissionTo(Permission::all());
    }

    public function createPermissionForRoleSuperAdmin(){

        $role = Role::create(['name' => \App\Helpers\Role::ROLE_SUPER_ADMIN]);
        $role->givePermissionTo(Permission::all());
    }

    public function createPermissionForRoleAdmin(){

        $role = Role::create(['name' => \App\Helpers\Role::ROLE_ADMIN]);
        $role->givePermissionTo(Permission::all());
    }
}
