<?php

use Illuminate\Database\Seeder;

class tables extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('settings')->insert([
            'key' => 'ads',
            'value' => '{"enabled":"0","header_ads":null,"footer_ads":null}',
        ]);

        DB::table('settings')->insert([
            'key' => 'email',
            'value' => 'admin@prev.me',
        ]);

        DB::table('settings')->insert([
            'key' => 'email_activation',
            'value' => '0',
        ]);

        DB::table('settings')->insert([
            'key' => 'logo',
            'value' => 'logo.png',
        ]);

        DB::table('settings')->insert([
            'key' => 'favicon',
            'value' => 'logo.png',
        ]);

        DB::table('settings')->insert([
            'key' => 'timezone',
            'value' => 'Africa/lagos',
        ]);

        DB::table('settings')->insert([
            'key' => 'registration',
            'value' => '1',
        ]);

        DB::table('settings')->insert([
            'key' => 'custom_home',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'privacy',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'terms',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'buttons',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'package_free',
            'value' => '{"id":"free","name":"Free","status":"1","price":{"month":"FREE","quarter":"FREE","annual":"FREE"},"settings":{"ads":true,"branding":true,"custom_branding":true,"statistics":true,"verified":true,"support":true,"social":true,"custom_background":true,"links_style":true,"links":true,"portfolio":true,"domains":true,"links_limit":"-1","support_limit":"-1","portfolio_limit":"-1"}}',
        ]);
        
        DB::table('settings')->insert([
            'key' => 'captcha',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'social',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'facebook',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'custom_code',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'currency',
            'value' => '',
        ]);

        DB::table('settings')->insert([
            'key' => 'email_notify',
            'value' => '',
        ]);
        
        DB::table('settings')->insert([
            'key' => 'topbar',
            'value' => '{"enabled":"1","location":"1","social":"1"}',
        ]);
        
        DB::table('settings')->insert([
            'key' => 'location',
            'value' => '8560 Magnolia Street Laredo, TX 78043',
        ]);
        
        DB::table('settings')->insert([
            'key' => 'maintenance',
            'value' => '{"enabled":"1","custom_text":null}',
        ]);
        
        DB::table('settings')->insert([
            'key' => 'support_status_change',
            'value' => '',
        ]);
        
        DB::table('settings')->insert([
            'key' => 'contact',
            'value' => '',
        ]);
        
        DB::table('settings')->insert([
            'key' => 'payment_system',
            'value' => '1',
        ]);
    }
}
