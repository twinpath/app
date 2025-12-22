<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\LegalPage;
use App\Models\LegalPageRevision;
use Illuminate\Database\Seeder;

class LegalPageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Terms and Conditions
        $terms = LegalPage::firstOrCreate(
            ['slug' => 'terms-and-conditions'],
            ['title' => 'Terms and Conditions']
        );

        LegalPageRevision::create([
            'legal_page_id' => $terms->id,
            'version' => '1.0.0',
            'change_log' => 'Initial revision',
            'is_active' => true,
            'content' => "# Terms and Conditions\n\nWelcome to DyDev TrustLab. These terms outline the rules and regulations for the use of our services.\n\n## 1. Acceptable Use\nBy accessing this website, we assume you accept these terms and conditions. Do not continue to use TrustLab if you do not agree to all of the terms and conditions stated on this page.\n\n## 2. Intellectual Property\nUnless otherwise stated, TrustLab and/or its licensors own the intellectual property rights for all material on TrustLab.\n\n## Contact Us\nIf you have any questions about these Terms, please contact us at **info@dydev.com** or via our [Contact Form](/contact)."
        ]);

        // 2. Privacy Policy
        $privacy = LegalPage::firstOrCreate(
            ['slug' => 'privacy-policy'],
            ['title' => 'Privacy Policy']
        );

        LegalPageRevision::create([
            'legal_page_id' => $privacy->id,
            'version' => '1.0.0',
            'change_log' => 'Initial revision',
            'is_active' => true,
            'content' => "# Privacy Policy\n\nYour privacy is important to us. It is TrustLab's policy to respect your privacy regarding any information we may collect from you across our website.\n\n## 1. Information We Collect\nWe only ask for personal information when we truly need it to provide a service to you.\n\n## 2. Data Security\nWe protect your data within commercially acceptable means to prevent loss and theft.\n\n## Contact Us\nIf you have any questions about how we handle user data and personal information, please contact us at **privacy@dydev.com** or via our [Contact Form](/contact)."
        ]);
    }
}
