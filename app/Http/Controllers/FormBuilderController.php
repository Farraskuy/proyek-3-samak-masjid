<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Models\FormResponse;
use App\Models\FormResponseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    // List forms for admin
    public function index()
    {
        $forms = Form::orderBy('created_at','desc')->get();
        return view('admin.forms.index', compact('forms'));
    }

    // Show create UI
    public function create()
    {
        return view('admin.forms.create');
    }

    // Store a new form and its fields
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array|min:1',
        ]);

        $slug = $data['slug'] ?? Str::slug($data['title']);
        if (Form::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $form = Form::create([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'settings' => [],
        ]);

        foreach ($data['fields'] as $i => $f) {
            FormField::create([
                'form_id' => $form->id,
                'label' => $f['label'] ?? 'Field '.$i,
                'name' => $f['name'] ?? ('field_'.$i),
                'type' => $f['type'] ?? 'text',
                'placeholder' => $f['placeholder'] ?? null,
                'options' => $f['options'] ?? null,
                'is_required' => !empty($f['is_required']),
                'validation_rules' => $f['validation_rules'] ?? null,
                'order' => $i,
            ]);
        }

        return redirect()->route('admin.forms.index')->with('success','Form created');
    }

    // Edit form
    public function edit($id)
    {
        $form = Form::with('fields')->findOrFail($id);
        return view('admin.forms.edit', compact('form'));
    }

    public function update(Request $request, $id)
    {
        $form = Form::with('fields')->findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array|min:1',
        ]);

        $form->update([
            'title' => $data['title'],
            'slug' => $data['slug'] ?? $form->slug,
            'description' => $data['description'] ?? null,
        ]);

        // Replace fields: simple approach delete old then insert
        $form->fields()->delete();
        foreach ($data['fields'] as $i => $f) {
            FormField::create([
                'form_id' => $form->id,
                'label' => $f['label'] ?? 'Field '.$i,
                'name' => $f['name'] ?? ('field_'.$i),
                'type' => $f['type'] ?? 'text',
                'placeholder' => $f['placeholder'] ?? null,
                'options' => $f['options'] ?? null,
                'is_required' => !empty($f['is_required']),
                'validation_rules' => $f['validation_rules'] ?? null,
                'order' => $i,
            ]);
        }

        return redirect()->route('admin.forms.index')->with('success','Form updated');
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);
        $form->delete();
        return back()->with('success','Form deleted');
    }

    // Show list of responses
    public function responses($id)
    {
        $form = Form::findOrFail($id);
        $responses = $form->responses()->orderBy('created_at','desc')->get();
        return view('admin.forms.responses', compact('form','responses'));
    }

    public function responseShow($formId, $responseId)
    {
        $form = Form::findOrFail($formId);
        $response = FormResponse::with('items')->findOrFail($responseId);
        return view('admin.forms.response_show', compact('form','response'));
    }

    public function responseDelete($formId, $responseId)
    {
        $response = FormResponse::findOrFail($responseId);
        $response->delete();
        return back()->with('success','Response deleted');
    }

    // Public form show
    public function show($slug)
    {
        $form = Form::with('fields')->where('slug', $slug)->firstOrFail();
        return view('client.forms.fill', compact('form'));
    }

    // Submit response
    public function submit(Request $request, $slug)
    {
        $form = Form::with('fields')->where('slug', $slug)->firstOrFail();

        $fields = $form->fields->toArray();
        $expectedNames = array_column($fields, 'name');

        $payload = $request->except(['_token']);

        // Validate that submitted keys match exactly expected field names
        $submittedNames = array_keys($payload);

        sort($expectedNames);
        sort($submittedNames);

        if ($expectedNames !== $submittedNames) {
            return response()->json(['message' => 'Submitted fields do not match form fields'], 422);
        }

        // Build validation rules from fields
        $rules = [];
        foreach ($fields as $f) {
            $r = [];
            if (!empty($f['is_required'])) $r[] = 'required';
            // accept custom validation_rules array
            if (!empty($f['validation_rules']) && is_array($f['validation_rules'])) {
                $r = array_merge($r, $f['validation_rules']);
            }
            if (!empty($r)) {
                $rules[$f['name']] = implode('|', $r);
            }
        }

        $request->validate($rules);

        $response = FormResponse::create([
            'form_id' => $form->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        foreach ($fields as $f) {
            FormResponseItem::create([
                'response_id' => $response->id,
                'field_name' => $f['name'],
                'field_label' => $f['label'],
                'value' => is_array($request->input($f['name'])) ? json_encode($request->input($f['name'])) : $request->input($f['name']),
            ]);
        }

        return redirect()->back()->with('success','Response submitted');
    }
}
