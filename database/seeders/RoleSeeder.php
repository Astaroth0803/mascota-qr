<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos si no existen
        Permission::firstOrCreate(['name' => 'ver solicitudes']);
        Permission::firstOrCreate(['name' => 'verificar pagos']);
        Permission::firstOrCreate(['name' => 'rechazar solicitudes']);
        Permission::firstOrCreate(['name' => 'ver perfil']);
        // Añadir más permisos si es necesario

        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);
        $clienteQrRole = Role::firstOrCreate(['name' => 'cliente_qr']);

        // Asignar todos los permisos al rol de administrador
        $permissions = Permission::all(); // Obtener todos los permisos
        $adminRole->syncPermissions($permissions); // Asignar todos los permisos al rol 'administrador'

        // Asignar permisos específicos al rol 'cliente_qr'
        $clienteQrRole->givePermissionTo('ver perfil');
    }
}
