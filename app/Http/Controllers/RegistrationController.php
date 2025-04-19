<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{ public function create()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fullname' => 'required|string',
            'level' => 'required|string',
            'course_type' => 'required|string',
            'major' => 'required|string',
            'academic_year' => 'required|integer',
            'register_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        if ($request->hasFile('receipt')) {
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        Registration::create($data);

        return redirect()->route('register.index')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว!');
    }

    public function index()
    {
        $registrations = Registration::all();
        return view('registrations', compact('registrations'));
    }

    public function keyData($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->update(['is_keyed' => true]);

        return redirect()->back()->with('success', 'คีย์ข้อมูลเรียบร้อย!');
    }
}
