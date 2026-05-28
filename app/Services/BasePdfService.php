<?php

namespace App\Services;

use Mpdf\Mpdf;

abstract class BasePdfService
{
    protected function createMpdf(): Mpdf
    {
        return new Mpdf([
            'format'        => 'A4',
            'margin_top'    => 15,
            'margin_bottom' => 15,
            'margin_left'   => 15,
            'margin_right'  => 15,
            'default_font'  => 'dejavusans',
        ]);
    }
}