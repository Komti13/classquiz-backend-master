<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Template;
use App\Http\Resources\Template as TemplateResource;

/**
 * @resource Templates
 *
 */
class TemplateController extends Controller
{
    /**
     * Display a listing of the template.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TemplateResource::collection(Template::all());
    }

    /**
     * Store a newly created template in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $template->with_last_field = request('with_last_field');

        $template->save();


        return new TemplateResource($template);
    }

    /**
     * Display the specified template.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new TemplateResource(Template::findOrFail($id));
    }

    /**
     * Update the specified template in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        $template->with_last_field = request('with_last_field');

        $template->save();

        return new TemplateResource($template);

    }

    /**
     * Remove the specified template from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = Template::destroy($id);
        return response()->json([
            'message' => 'Template deleted successfully'
        ], 200);
    }
}
