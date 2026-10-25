<?php

namespace App\Http\Requests\Admin;

use App\Models\PricingPlan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PricingPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(array_keys(PricingPlan::TYPES))],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique(PricingPlan::class, 'slug')->ignore($this->route('pricing_plan')),
            ],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'price_prefix' => ['nullable', 'string', 'max:255'],
            'price_amount' => ['required', 'string', 'max:255'],
            'price_suffix' => ['nullable', 'string', 'max:255'],
            'features' => ['array', 'max:30'],
            'features.*' => ['required', 'string', 'max:255'],
            'cta_label' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'กรุณาเลือกประเภท',
            'type.in' => 'ประเภทที่เลือกไม่ถูกต้อง',
            'title.required' => 'กรุณาระบุชื่อ',
            'title.max' => 'ชื่อต้องไม่เกิน 255 ตัวอักษร',
            'slug.required' => 'กรุณาระบุ slug',
            'slug.max' => 'slug ต้องไม่เกิน 255 ตัวอักษร',
            'slug.regex' => 'slug ใช้ได้เฉพาะตัวอักษรภาษาอังกฤษตัวพิมพ์เล็ก ตัวเลข และขีดกลาง',
            'slug.unique' => 'slug นี้ถูกใช้แล้ว',
            'subtitle.max' => 'คำบรรยายต้องไม่เกิน 255 ตัวอักษร',
            'price_prefix.max' => 'คำนำหน้าราคาต้องไม่เกิน 255 ตัวอักษร',
            'price_amount.required' => 'กรุณาระบุราคา',
            'price_amount.max' => 'ราคาต้องไม่เกิน 255 ตัวอักษร',
            'price_suffix.max' => 'หน่วยราคาต้องไม่เกิน 255 ตัวอักษร',
            'features.max' => 'รายการคุณสมบัติต้องไม่เกิน 30 บรรทัด',
            'features.*.max' => 'แต่ละคุณสมบัติต้องไม่เกิน 255 ตัวอักษร',
            'cta_label.max' => 'ข้อความปุ่มต้องไม่เกิน 255 ตัวอักษร',
            'sort_order.required' => 'กรุณาระบุลำดับการแสดงผล',
            'sort_order.integer' => 'ลำดับการแสดงผลต้องเป็นจำนวนเต็ม',
            'sort_order.min' => 'ลำดับการแสดงผลต้องไม่ต่ำกว่า 0',
            'sort_order.max' => 'ลำดับการแสดงผลต้องไม่เกิน 9999',
        ];
    }

    /**
     * @return array{
     *     type: string,
     *     title: string,
     *     slug: string,
     *     subtitle: string|null,
     *     price_prefix: string|null,
     *     price_amount: string,
     *     price_suffix: string|null,
     *     features: array<int, string>|null,
     *     cta_label: string|null,
     *     is_featured: bool,
     *     sort_order: int,
     *     is_published: bool
     * }
     */
    public function planAttributes(): array
    {
        $attributes = $this->safe()->only([
            'type',
            'title',
            'slug',
            'subtitle',
            'price_prefix',
            'price_amount',
            'price_suffix',
            'features',
            'cta_label',
            'is_featured',
            'sort_order',
            'is_published',
        ]);

        $attributes['features'] = $attributes['features'] === [] ? null : $attributes['features'];
        $attributes['sort_order'] = (int) $attributes['sort_order'];

        return $attributes;
    }

    protected function prepareForValidation(): void
    {
        $segments = preg_split('/\r\n|\r|\n/', $this->string('features')->toString());

        $lines = collect($segments === false ? [] : $segments)
            ->map(fn (string $line): string => trim($line))
            ->filter(fn (string $line): bool => $line !== '')
            ->values()
            ->all();

        $this->merge([
            'slug' => $this->string('slug')->trim()->lower()->toString(),
            'features' => $lines,
            'is_featured' => $this->boolean('is_featured'),
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
