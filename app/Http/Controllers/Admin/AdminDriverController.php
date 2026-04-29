<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 
class AdminDriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role','driver')->latest()->paginate(20);
        return view('admin.drivers.index', compact('drivers'));
    }
 
    public function create()
    {
        return view('admin.drivers.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required','string','max:100','regex:/^[a-zA-Z\s]+$/'],
            'email'          => 'required|email|unique:users,email',
            'contact_number' => ['required','regex:/^[0-9]+$/','min:7','max:15'],
            'password'       => 'required|string|min:8|confirmed',
            'license_number' => 'nullable|string|max:50',
            'vehicle_info'   => 'nullable|string|max:100',
            'address'        => 'nullable|string|max:200',
        ]);
 
        $driver = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'contact_number'   => $request->contact_number,
            'password'         => Hash::make($request->password),
            'role'             => 'driver',
            'license_number'   => $request->license_number,
            'vehicle_info'     => $request->vehicle_info,
            'address'          => $request->address,
            'email_verified_at'=> now(),
            'is_active'        => true,
        ]);
 
        AuditLogService::log('DRIVER_CREATED','Drivers',"Created: {$driver->email}");
        return redirect()->route('admin.drivers.index')->with('success',"Driver {$driver->name} created.");
    }
 
    public function edit(User $driver)
    {
        abort_unless($driver->isDriver(), 404);
        return view('admin.drivers.edit', compact('driver'));
    }
 
    public function update(Request $request, User $driver)
    {
        abort_unless($driver->isDriver(), 404);
        $data = $request->validate([
            'name'           => ['required','string','max:100'],
            'contact_number' => ['required','regex:/^[0-9]+$/','min:7','max:15'],
            'license_number' => 'nullable|string|max:50',
            'vehicle_info'   => 'nullable|string|max:100',
            'address'        => 'nullable|string|max:200',
            'driver_status'  => 'required|in:available,busy,offline',
        ]);
        $data['is_active'] = $request->has('is_active');
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }
        $driver->update($data);
        AuditLogService::log('DRIVER_UPDATED','Drivers',"Updated: {$driver->email}");
        return redirect()->route('admin.drivers.index')->with('success','Driver updated.');
    }
 
    public function destroy(User $driver)
    {
        abort_unless($driver->isDriver(), 404);
        $email = $driver->email;
        $driver->delete();
        AuditLogService::log('DRIVER_DELETED','Drivers',"Deleted: {$email}");
        return back()->with('success','Driver deleted.');
    }
}