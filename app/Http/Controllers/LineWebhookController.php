<?php

namespace App\Http\Controllers;

use App\Services\LineOfficialAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JsonException;

class LineWebhookController extends Controller
{
    public function __invoke(Request $request, LineOfficialAccount $line): Response
    {
        if (! $line->signatureIsValid($request)) {
            abort(403);
        }

        try {
            $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            abort(400);
        }

        if (! is_array($payload)) {
            abort(400);
        }

        $line->ingest($payload);

        return response('OK');
    }
}
