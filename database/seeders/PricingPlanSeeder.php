<?php

namespace Database\Seeders;

use App\Models\PricingPlan;
use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'slug' => 'monthly-basic',
                'type' => PricingPlan::TYPE_PACKAGE,
                'title' => 'บัญชีและภาษีรายเดือน',
                'subtitle' => 'สำหรับกิจการขนาดเล็ก',
                'price_prefix' => 'เริ่มต้น',
                'price_amount' => '1,500',
                'price_suffix' => 'บาท/เดือน',
                'features' => [
                    'ทำบัญชีรายเดือน',
                    'ยื่นภาษีรายเดือน',
                    'ส่งเอกสารออนไลน์ได้',
                ],
                'cta_label' => 'สอบถามราคา',
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'slug' => 'monthly-complete',
                'type' => PricingPlan::TYPE_PACKAGE,
                'title' => 'แพ็กเกจครบวงจรรายเดือน',
                'subtitle' => 'ครบทุกงาน จบในที่เดียว',
                'price_prefix' => 'เริ่มต้น',
                'price_amount' => '5,000',
                'price_suffix' => 'บาท/เดือน',
                'features' => [
                    'ทำบัญชีรายเดือน',
                    'ยื่นภาษีทุกแบบ',
                    'งบการเงินรายเดือน',
                    'รวมยื่น ภ.ง.ด.50 / 51',
                ],
                'cta_label' => 'เลือกแพ็กเกจนี้',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'annual-closing',
                'type' => PricingPlan::TYPE_PACKAGE,
                'title' => 'ปิดงบการเงินประจำปี',
                'subtitle' => 'สำหรับกิจการที่ทำบัญชีเองระหว่างปี',
                'price_prefix' => 'เริ่มต้น',
                'price_amount' => '12,000',
                'price_suffix' => 'บาท/ปี',
                'features' => [
                    'จัดทำงบการเงินประจำปี',
                    'ประสานงานผู้สอบบัญชี (CPA)',
                    'ไม่รวมค่าผู้สอบบัญชี (CPA)',
                ],
                'cta_label' => 'สอบถามราคา',
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        $aLaCarte = [
            [
                'slug' => 'pnd-50-51-once',
                'type' => PricingPlan::TYPE_A_LA_CARTE,
                'title' => 'ยื่น ภ.ง.ด.50 / 51 แยกครั้ง',
                'subtitle' => null,
                'price_prefix' => null,
                'price_amount' => '1,500–2,000',
                'price_suffix' => 'บาท/ครั้ง',
                'features' => null,
                'cta_label' => null,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'slug' => 'register-llp',
                'type' => PricingPlan::TYPE_A_LA_CARTE,
                'title' => 'จดทะเบียน หจก.',
                'subtitle' => null,
                'price_prefix' => 'เริ่มต้น',
                'price_amount' => '4,599',
                'price_suffix' => 'บาท',
                'features' => null,
                'cta_label' => null,
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'slug' => 'register-company',
                'type' => PricingPlan::TYPE_A_LA_CARTE,
                'title' => 'จดทะเบียน บจก.',
                'subtitle' => null,
                'price_prefix' => 'เริ่มต้น',
                'price_amount' => '7,599',
                'price_suffix' => 'บาท',
                'features' => null,
                'cta_label' => null,
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'slug' => 'consultation-fee',
                'type' => PricingPlan::TYPE_A_LA_CARTE,
                'title' => 'ให้คำปรึกษา',
                'subtitle' => null,
                'price_prefix' => null,
                'price_amount' => 'ตามตกลง',
                'price_suffix' => null,
                'features' => null,
                'cta_label' => null,
                'is_featured' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ([...$packages, ...$aLaCarte] as $plan) {
            PricingPlan::query()->updateOrCreate(
                ['slug' => $plan['slug']],
                [...$plan, 'is_published' => true],
            );
        }
    }
}
