<?php

namespace App\Modules\Quotes\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Quotes\Requests\CreateQuoteRequest;
use App\Modules\Quotes\Requests\UpdateQuoteRequest;
use App\Modules\Quotes\Services\QuoteService;
use App\Models\Quote;
use App\Modules\Quotes\Services\QuotePdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function __construct(
        protected QuoteService    $quoteService,
        protected QuotePdfService $quotePdfService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->quoteService->index(auth()->user());
    }

    public function store(CreateQuoteRequest $request): JsonResponse
    {
        return $this->quoteService->store(auth()->user(), $request->validated());
    }

    public function show(int $id): JsonResponse
    {
        return $this->quoteService->show(auth()->user(), $id);
    }

    public function update(UpdateQuoteRequest $request, int $id): JsonResponse
    {
        return $this->quoteService->update(auth()->user(), $request->validated(), $id);
        
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->quoteService->destroy(auth()->user(), $id);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:draft,sent,viewed,accepted,declined,expired',
        ]);

        return $this->quoteService->updateStatus(auth()->user(), $id, $request->status);
    }

    public function pdf(int $id): \Illuminate\Http\Response
    {
        $quote = Quote::where('organization_id', auth()->user()->organization_id)
            ->findOrFail($id);

        $pdf = $this->quotePdfService->generate($quote);

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $quote->quote_number . '.pdf"');
    }

    public function pdfDownload(int $id): \Illuminate\Http\Response
    {
        $quote = Quote::where('organization_id', auth()->user()->organization_id)
            ->findOrFail($id);

        $pdf = $this->quotePdfService->generate($quote);

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $quote->quote_number . '.pdf"');
    }
}