<?php

use App\Helpers\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public $email_domain = '@bticket.com'; // the email domain name for demo accounts. Ex. user1@example.com
    public $agents_qty = 3;
    public $users_qty = 3;
    public $admin_qty = 3;
    public $default_agent_password = 'bticket';
    public $default_user_password = 'bticket';
 

    public function run()
    {
        Model::unguard();

        $faker = \Faker\Factory::create();

        for ($a = 1; $a <= $this->agents_qty; $a++) {
            $email = 'agent'.$a.$this->email_domain;
			
			$agent_info = \PanicHDMember::firstOrNew(['email' => $email]);
			$agent_info->name = $faker->name;
			$agent_info->panichd_agent = 1;
			$agent_info->password = Hash::make($this->default_agent_password);
			$agent_info->save();

            $agent_info->assignRole([Role::ROLE_SUPORTE]);
        }

        for ($u = 1; $u <= $this->users_qty; $u++) {
            
			// Create users
			$email = 'user'.$u.$this->email_domain;
			
			$user_info = \PanicHDMember::firstOrNew(['email' => $email]);
            $user_info->name = $faker->name;
            $user_info->panichd_agent = 0;
            $user_info->password = Hash::make($this->default_user_password);
            $user_info->save();

            $user_info->assignRole([Role::ROLE_CLIENTE]);
        }

        for ($u = 1; $u <= $this->admin_qty; $u++) {

			// Create users
			$email = 'admin'.$u.$this->email_domain;

			$user_info = \PanicHDMember::firstOrNew(['email' => $email]);
            $user_info->name = $faker->name;
            $user_info->panichd_agent = 0;
            $user_info->panichd_admin = 1;
            $user_info->password = Hash::make($this->default_user_password);
            $user_info->save();

            $user_info->assignRole([Role::ROLE_ADMIN]);
        }
    }
}
