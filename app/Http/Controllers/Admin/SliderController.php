<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SliderController extends Controller
{
    use HandlesUploads;

    public function index(): Response
    {
        $this->authorize('sliders.view');

        return Inertia::render('Admin/Sliders/Index', [
            'sliders' => Slider::orderBy('sort_order')->get(),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('sliders.create');

        return Inertia::render('Admin/Sliders/Form', ['slider' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('sliders.create');

        $data = $this->validateData($request);
        $data['image'] = $this->storeUpload($request->file('image'), 'sliders');
        $data['mobile_image'] = $this->storeUpload($request->file('mobile_image'), 'sliders');

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'تمت إضافة السلايدر.');
    }

    public function edit(Slider $slider): Response
    {
        $this->authorize('sliders.update');

        return Inertia::render('Admin/Sliders/Form', ['slider' => $slider]);
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $this->authorize('sliders.update');

        $data = $this->validateData($request, $slider->id);
        $data['image'] = $this->replaceUpload($request->file('image'), 'sliders', $slider->image);
        $data['mobile_image'] = $this->replaceUpload($request->file('mobile_image'), 'sliders', $slider->mobile_image);

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'تم تحديث السلايدر.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $this->authorize('sliders.delete');

        $slider->delete();

        return back()->with('success', 'تم حذف السلايدر.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => [$ignoreId ? 'nullable' : 'required', 'image', 'max:4096'],
            'mobile_image' => ['nullable', 'image', 'max:4096'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);
    }
}
