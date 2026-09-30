<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kategori;

class KategoriController extends SimpleMasterDataController
{
    protected string $model = Kategori::class;

    protected string $slug = 'kategori';

    protected string $label = 'Kategori';

    protected string $viewIndex = 'admin.kategori.index';
}
