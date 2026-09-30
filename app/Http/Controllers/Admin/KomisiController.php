<?php

namespace App\Http\Controllers\Admin;

use App\Models\Komisi;

class KomisiController extends SimpleMasterDataController
{
    protected string $model = Komisi::class;

    protected string $slug = 'komisi';

    protected string $label = 'Komisi';

    protected string $viewIndex = 'admin.komisi.index';
}
