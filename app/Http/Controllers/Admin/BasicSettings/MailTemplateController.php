<?php

namespace App\Http\Controllers\Admin\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\MailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;
use Validator;

class MailTemplateController extends Controller
{
    public function index(): View
    {
        $templates = MailTemplate::all();

        return view('admin.basic-settings.email.templates', compact('templates'));
    }

    public function edit($id): View
    {
        $templateInfo = MailTemplate::findOrFail($id);

        return view('admin.basic-settings.email.edit-template', compact('templateInfo'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $rules = [
            'mail_subject' => 'required',
            'mail_body' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        MailTemplate::findOrFail($id)->update($request->except('mail_type', 'mail_body') + [
            'mail_body' => Purifier::clean($request->mail_body, 'youtube'),
        ]);

        session()->flash('success', __('Mail template updated successfully!'));

        return redirect()->back();
    }
}
