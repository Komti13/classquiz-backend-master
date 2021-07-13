<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Template;

class TemplateController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $templates = Template::query();
            return datatables()->eloquent($templates)->toJson();
        }

        return view('admin.template.index');
    }

    public function create()
    {
        return view('admin.template.create');
    }

    public function store()
    {
        request()->validate([
            'id' => 'required|string|max:255|unique:templates',
            'selected_template' => 'nullable|string|max:255',
            'selected_category' => 'nullable|string|max:255',
            'selected_icon' => 'nullable|string|max:255',
            'with_last_field' => 'nullable|boolean'
        ]);
        $template = new Template;
        $template->id = request('id');
        $template->selected_template = request('selected_template');
        $template->selected_category = request('selected_category');
        $template->selected_icon = request('selected_icon');
        $template->with_last_field = request('with_last_field') ? 1 : 0;
        $template->save();


        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully');
    }

    public function edit($id)
    {

        $template = Template::findOrFail($id);
        return view('admin.template.edit', compact('template'));
    }


    public function update($id)
    {
        $template = Template::findOrFail($id);
        request()->validate([
            'id' => 'required|string|max:255|unique:templates,id,' . $template->id,
            'selected_template' => 'nullable|string|max:255',
            'selected_category' => 'nullable|string|max:255',
            'selected_icon' => 'nullable|string|max:255',
            'with_last_field' => 'nullable|boolean'
        ]);
        $template->id = request('id');
        $template->selected_template = request('selected_template');
        $template->selected_category = request('selected_category');
        $template->selected_icon = request('selected_icon');
        $template->with_last_field = request('with_last_field') ? 1 : 0;

        $template->save();


        return redirect()->route('templates.index')
            ->with('success', 'Template edited successfully');
    }


    public function destroy($id)
    {
        Template::destroy($id);
        session()->flash('success', 'Template deleted successfully');

        return response()->json(['success' => true]);
    }
}
