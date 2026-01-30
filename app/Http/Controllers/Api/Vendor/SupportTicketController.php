<?php

namespace App\Http\Controllers\Api\Vendor;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\SupportTicket;
use App\Models\SupportTicketStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class SupportTicketController extends Controller
{
    // index
    public function index(Request $request)
    {
        $s_status = SupportTicketStatus::first();

        $status = null;
        if ($request->filled('status')) {
            $status = $request['status'];
        }

        $collection = SupportTicket::where([['user_id', Auth::guard('sanctum_vendor')->user()->id], ['user_type', 'vendor']])->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
            ->orderByDesc('id')
            ->get();

        $current_package = \App\Http\Helpers\VendorPermissionHelper::packagePermission(Auth::guard('sanctum_vendor')->user()->id);
        if ($current_package != '[]' && $current_package->support_ticket_status == 1 && $s_status->support_ticket_status == 'active') {

            return response()->json([
                'success' => true,
                'data' => $collection,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('Support Ticket is not active in your package.'),
            ]);
        }
    }

    // store
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required',
            'subject' => 'required',
        ];

        $file = $request->file('attachment');
        $allowedExts = ['zip'];
        $rules['attachment'] = [
            function ($attribute, $value, $fail) use ($file, $allowedExts) {
                $ext = $file->getClientOriginalExtension();
                if (! in_array($ext, $allowedExts)) {
                    return $fail('Only zip file supported');
                }
            },
            'max:20000',
        ];

        $messages = [
            'attachment.max' => __('Attachment may not be greater than 20 MB'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $in = $request->all();
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = uniqid().'.'.$attachment->getClientOriginalExtension();
            $attachment->move(public_path('assets/admin/img/support-ticket/attachment/'), $filename);
            $in['attachment'] = $filename;
        }
        $in['user_id'] = Auth::guard('sanctum_vendor')->user()->id;
        $in['user_type'] = 'vendor';
        $in['description'] = Purifier::clean($request->description, 'youtube');
        SupportTicket::create($in);

        return response()->json([
            'success' => true,
            'message' => __('Support Ticket Created Successfully!'),
        ]);
    }

    // message
    public function message($id): JsonResponse
    {
        $s_status = SupportTicketStatus::first();

        $ticket = SupportTicket::findOrFail($id);
        if ($ticket->user_type == 'vendor' && $ticket->user_id != Auth::guard('sanctum_vendor')->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('You are not authorized to view this ticket.'),
            ], 403);
        }

        $current_package = \App\Http\Helpers\VendorPermissionHelper::packagePermission(Auth::guard('sanctum_vendor')->user()->id);
        if ($current_package != '[]' && $current_package->support_ticket_status == 1 && $s_status->support_ticket_status == 'active') {

            $messages = $ticket->messages()->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $messages,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('Support Ticket is not active in your package.'),
            ]);
        }
    }

    public function zip_file_upload(Request $request)
    {
        $file = $request->file('file');
        $allowedExts = ['zip'];
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($file, $allowedExts) {
                    $ext = $file->getClientOriginalExtension();
                    if (! in_array($ext, $allowedExts)) {
                        return $fail('Only zip file supported');
                    }
                },
                'max:5000',
            ],
        ];

        $messages = [
            'file.max' => ' zip file may not be greater than 5 MB',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('assets/front/temp/'), $filename);
            $input['file'] = $filename;
        }

        return response()->json(['data' => 1]);
    }

    public function ticketreply(Request $request, $id)
    {
        $s_status = SupportTicketStatus::first();

        $current_package = \App\Http\Helpers\VendorPermissionHelper::packagePermission(Auth::guard('sanctum_vendor')->user()->id);
        if ($current_package == '[]' && $current_package->support_ticket_status == 0 && $s_status->support_ticket_status != 'active') {
            return response()->json([
                'success' => false,
                'message' => __('Support Ticket is not active in your package.'),
            ]);
        }

        $file = $request->file('file');
        $allowedExts = ['zip'];
        $rules = [
            'reply' => 'required',
            'file' => [
                function ($attribute, $value, $fail) use ($file, $allowedExts) {

                    $ext = $file->getClientOriginalExtension();
                    if (! in_array($ext, $allowedExts)) {
                        return $fail('Only zip file supported');
                    }
                },
                'max:20000',
            ],
        ];

        $messages = [
            'file.max' => ' Zip file may not be greater than 20 MB',
        ];

        $request->validate($rules, $messages);
        $input = $request->all();

        $reply = str_replace(url('/').'/assets/front/img/', '{base_url}/assets/front/img/', $request->reply);
        $input['reply'] = Purifier::clean($reply, 'youtube');
        $input['user_id'] = Auth::guard('sanctum_vendor')->user()->id;
        $input['type'] = 3;

        $input['support_ticket_id'] = $id;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('assets/admin/img/support-ticket/'), $filename);
            $input['file'] = $filename;
        }

        $data = new Conversation;
        $data->create($input);

        $files = glob('assets/front/temp/*');
        foreach ($files as $file) {
            unlink($file);
        }

        SupportTicket::where('id', $id)->update([
            'last_message' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Reply Sent Successfully!'),
        ]);
    }

    // delete
    public function delete($id)
    {
        // delete all support ticket
        $support_ticket = SupportTicket::find($id);
        if ($support_ticket) {
            // delete conversation
            $messages = $support_ticket->messages()->get();
            foreach ($messages as $message) {
                @unlink(public_path('assets/admin/img/support-ticket/'.$message->file));
                $message->delete();
            }
            @unlink(public_path('assets/admin/img/support-ticket/attachment/').$support_ticket->attachment);
            $support_ticket->delete();
        }
        Session::flash('success', __('Support Ticket Deleted Successfully!'));

        return back();
    }
}
