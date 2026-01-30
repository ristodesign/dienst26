<?php

namespace App\Http\Controllers;

/**
 * Compatibility shim for legacy route/controller references.
 *
 * Some older route definitions (or cached routes) may still reference
 * `App\Http\Controllers\MyFatoorahController`. The actual implementation
 * lives in `App\Http\Controllers\Payment\MyFatoorahController`.
 */
class MyFatoorahController extends \App\Http\Controllers\Payment\MyFatoorahController
{
}

