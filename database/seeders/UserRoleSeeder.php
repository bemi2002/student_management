<!-- <?php

// namespace Database\Seeders;

//use App\Models\User;
//use Illuminate\Database\Seeder;
//use Spatie\Permission\Models\Role;
//use Illuminate\Support\Facades\Hash;

//class UserRoleSeeder extends Seeder
//{
  //  public function run(): void
    //{
        // Create roles
    //    $adminRole = Role::firstOrCreate(['name' => 'Admin']);
      //  $teacherRole = Role::firstOrCreate(['name' => 'Teacher']);
        //$studentRole = Role::firstOrCreate(['name' => 'Student']);

        // Create Admin user - name same as password
       // $admin = User::firstOrCreate(
         //   ['email' => 'admin@school.com'],
           // [
             //   'name' => 'admin123',
               // 'password' => Hash::make('admin123'),
                //'email_verified_at' => now(),
            //]
        //);
        //$admin->assignRole($adminRole);

        // Create Teacher user - name same as password
        //$teacher = User::firstOrCreate(
          //  ['email' => 'teacher@school.com'],
           // [
             //   'name' => 'teacher123',
               // 'password' => Hash::make('teacher123'),
                //'email_verified_at' => now(),
            //]
        ///);
        //$teacher->assignRole($teacherRole);

        // Create Student user - name same as password
        