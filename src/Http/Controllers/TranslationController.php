<?php

namespace Keasy9\Localize\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Keasy9\Localize\Models\Translation;

class TranslationController extends Controller
{
    public function index(Request $request, string $locale, string $model): View
    {
        $models = $model::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', '=', $locale);
        }]);

        $perPage = $request->get('perPage', 25);
        if ($perPage === 'all') {
            $models = $models->get();
            $perPage = $models->count();
            $models = new LengthAwarePaginator(
                $models->forPage(1, $perPage),
                $models->count(),
                $perPage,
                1,
                ['path' => Paginator::resolveCurrentPath()],
            );
        } else {
            $models = $models->paginate($perPage);
        }

        $model = [
            'name' => $model,
            'list' => $models,
        ];

        return view('localize::model', [
            'model' => $model,
            'locale' => $locale,
        ]);
    }

    public function save(Request $request, string $locale, string $model, int $id): RedirectResponse
    {
        $result = true;

        DB::beginTransaction();
        foreach ($model::$translated as $attribute) {
            if ($request->filled($attribute)) {
                $result = (bool) Translation::updateOrCreate(
                    ['model_type' => $model, 'model_id' => $id, 'model_field' => $attribute, 'locale' => $locale],
                    ['translation' => $request->input($attribute)],
                );
                if (! $result) {
                    DB::rollBack();

                    return redirect()->back()->with('saved', $result);
                }
            }
        }

        DB::commit();

        return redirect()->back()->with('saved', $result);
    }
}
