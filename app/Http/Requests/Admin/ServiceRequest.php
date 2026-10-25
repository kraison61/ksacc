<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique(Service::class, 'slug')->ignore($this->route('service')),
            ],
            'description' => ['required', 'string', 'max:5000'],
            'icon' => ['required', 'string', Rule::in(array_keys(Service::ICONS))],
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
            'title.required' => 'กรุณาระบุชื่อบริการ',
            'title.max' => 'ชื่อบริการต้องไม่เกิน 255 ตัวอักษร',
            'slug.required' => 'กรุณาระบุ slug',
            'slug.max' => 'slug ต้องไม่เกิน 255 ตัวอักษร',
            'slug.regex' => 'slug ใช้ได้เฉพาะตัวอักษรภาษาอังกฤษตัวพิมพ์เล็ก ตัวเลข และขีดกลาง',
            'slug.unique' => 'slug นี้ถูกใช้แล้ว',
            'description.required' => 'กรุณาระบุคำอธิบาย',
            'description.max' => 'คำอธิบายต้องไม่เกิน 5000 ตัวอักษร',
            'icon.required' => 'กรุณาเลือกไอคอน',
            'icon.in' => 'ไอคอนที่เลือกไม่ถูกต้อง',
            'sort_order.required' => 'กรุณาระบุลำดับการแสดงผล',
            'sort_order.integer' => 'ลำดับการแสดงผลต้องเป็นจำนวนเต็ม',
            'sort_order.min' => 'ลำดับการแสดงผลต้องไม่ต่ำกว่า 0',
            'sort_order.max' => 'ลำดับการแสดงผลต้องไม่เกิน 9999',
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     slug: string,
     *     description: string,
     *     icon: string,
     *     sort_order: int,
     *     is_published: bool
     * }
     */
    public function serviceAttributes(): array
    {
        $attributes = $this->safe()->only([
            'title',
            'slug',
            'description',
            'icon',
            'sort_order',
            'is_published',
        ]);

        $attributes['sort_order'] = (int) $attributes['sort_order'];

        return $attributes;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->string('slug')->trim()->lower()->toString(),
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
