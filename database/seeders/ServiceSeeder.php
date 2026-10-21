<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'slug' => 'company-registration',
                'title' => 'จดทะเบียนบริษัท / หจก.',
                'description' => 'ดำเนินการจดทะเบียนให้ครบทุกขั้นตอน พร้อมวางระบบบัญชีตั้งแต่วันแรกที่เปิดกิจการ',
                'icon' => 'building',
                'sort_order' => 1,
            ],
            [
                'slug' => 'monthly-bookkeeping',
                'title' => 'ทำบัญชีรายเดือน',
                'description' => 'บันทึกบัญชี จัดเก็บเอกสาร และสรุปรายงานให้เจ้าของกิจการเห็นภาพทุกเดือน',
                'icon' => 'book',
                'sort_order' => 2,
            ],
            [
                'slug' => 'monthly-tax-filing',
                'title' => 'ยื่นภาษีรายเดือน',
                'description' => 'ภ.พ.30, ภ.ง.ด.1, 3, 53 และประกันสังคม ยื่นตรงเวลาทุกเดือน ไม่ต้องจำกำหนดการเอง',
                'icon' => 'calendar',
                'sort_order' => 3,
            ],
            [
                'slug' => 'annual-financial-statements',
                'title' => 'ปิดงบการเงินประจำปี',
                'description' => 'จัดทำงบการเงิน ประสานงานผู้สอบบัญชีรับอนุญาต (CPA) และนำส่งงบให้ครบจบ',
                'icon' => 'chart',
                'sort_order' => 4,
            ],
            [
                'slug' => 'corporate-income-tax',
                'title' => 'ยื่น ภ.ง.ด.50 / 51',
                'description' => 'ภาษีเงินได้นิติบุคคลประจำปีและครึ่งปี คำนวณถูกต้อง ยื่นทันกำหนด',
                'icon' => 'file',
                'sort_order' => 5,
            ],
            [
                'slug' => 'consultation',
                'title' => 'ให้คำปรึกษา',
                'description' => 'ตอบคำถามเรื่องบัญชี ภาษี และเอกสาร ช่วยวางแผนให้กิจการเดินต่อได้อย่างมั่นใจ',
                'icon' => 'message',
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['slug' => $service['slug']],
                [...$service, 'is_published' => true],
            );
        }
    }
}
