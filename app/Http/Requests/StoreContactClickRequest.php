<?php

namespace App\Http\Requests;

use App\Enums\ContactClickChannel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactClickRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'channel' => ['required', Rule::enum(ContactClickChannel::class)],
            'page_url' => ['required', 'string', 'max:2048', 'url'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'channel.required' => 'กรุณาระบุช่องทาง',
            'channel.enum' => 'ช่องทางไม่ถูกต้อง',
            'page_url.required' => 'กรุณาระบุหน้าเว็บ',
            'page_url.url' => 'หน้าเว็บไม่ถูกต้อง',
            'page_url.max' => 'หน้าเว็บไม่ถูกต้อง',
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('page_url')) {
                    return;
                }

                $pageUrl = $this->string('page_url')->toString();
                $scheme = parse_url($pageUrl, PHP_URL_SCHEME);
                $host = parse_url($pageUrl, PHP_URL_HOST);

                if (! is_string($scheme) || ! in_array(strtolower($scheme), ['http', 'https'], true)) {
                    $validator->errors()->add('page_url', 'หน้าเว็บไม่ถูกต้อง');

                    return;
                }

                if (parse_url($pageUrl, PHP_URL_USER) !== null || parse_url($pageUrl, PHP_URL_PASS) !== null) {
                    $validator->errors()->add('page_url', 'หน้าเว็บไม่ถูกต้อง');

                    return;
                }

                if (! is_string($host) || strcasecmp($host, $this->getHost()) !== 0) {
                    $validator->errors()->add('page_url', 'หน้าเว็บไม่ถูกต้อง');
                }
            },
        ];
    }
}
