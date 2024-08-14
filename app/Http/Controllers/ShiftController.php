<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftPatternCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class ShiftController extends Controller
{
    public function shift()
    {
        $shift = DB::table('shift')->get();
        return view("jam.shift", compact('shift'));
    }

    public function shiftstore(Request $request)
    {
        $shift_name = $request->shift_name;
        $early_time = $request->early_time;
        $start_time = $request->start_time;
        $latest_time = $request->latest_time;
        $end_time = $request->end_time;
        $type = $request->type;
        $description = $request->description;
        $status = $request->status;

        try {
            $data = [
                'shift_name' => $shift_name,
                'early_time' => $early_time,
                'start_time' => $start_time,
                'latest_time' => $latest_time,
                'end_time' => $end_time,
                'type' => $type,
                'description' => $description,
                'status' => $status
            ];
            DB::table('shift')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
        } catch (\Throwable $th) {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Simpan']);
        }
    }
    public function shiftedit(Request $request)
    {
        $id = $request->id;
        $shift = DB::table('shift')->where('id', $id)->first();
        return view('jam.shiftedit', compact('shift'));
    }

    public function shiftupdate(Request $request)
    {
        $id = $request->id; // Retrieve the id from the request
        $shift_name = $request->shift_name;
        $early_time = $request->early_time;
        $start_time = $request->start_time;
        $latest_time = $request->latest_time;
        $end_time = $request->end_time;
        $type = $request->type;
        $description = $request->description;
        $status = $request->status;

        try {
            $data = [
                'shift_name' => $shift_name,
                'early_time' => $early_time,
                'start_time' => $start_time,
                'latest_time' => $latest_time,
                'end_time' => $end_time,
                'type' => $type,
                'description' => $description,
                'status' => $status
            ];
            DB::table('shift')
                ->where('id', $id)
                ->update($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } catch (\Throwable $th) {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function shiftdelete($id)
    {
        $delete = DB::table('shift')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }


    // SHIFT PATTERN ----------------------------------------------------------------------------------------------



    public function shiftpatt()
    {
        $patterns = DB::table('shift_pattern')->get();
        $shifts = Shift::all(); // Assuming you have a Shift model and you want all shifts
        return view("jam.shiftpatt", compact('shifts', 'patterns'));
    }

    public function shiftpattstore(Request $request)
    {
        $pattern_name = $request->pattern_name;
        $description = $request->description;

        try {
            $data = [
                'pattern_name' => $pattern_name,
                'description' => $description
            ];
            DB::table('shift_pattern')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
        } catch (\Throwable $th) {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Simpan']);
        }
    }
    public function shiftpattedit(Request $request)
    {
        $id = $request->id;
        $shift = DB::table('shift_pattern')->where('id', $id)->first();
        return view('jam.shiftpattedit', compact('shift'));
    }

    public function shiftpattupdate(Request $request)
    {
        $id = $request->id; // Retrieve the id from the request
        $pattern_name = $request->pattern_name;
        $description = $request->description;

        try {
            $data = [
                'pattern_name' => $pattern_name,
                'description' => $description
            ];
            DB::table('shift_pattern')
                ->where('id', $id)
                ->update($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } catch (\Throwable $th) {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function shiftpattdelete($id)
    {
        $delete = DB::table('shift_pattern')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

    public function getCycles($id)
    {
        $cycles = ShiftPatternCycle::where('pattern_id', $id)->get();
        $shifts = Shift::all(); // Fetch all shifts for the dropdown
        return response()->json(['cycles' => $cycles, 'shifts' => $shifts]);
    }

    public function saveCycles(Request $request, $id)
    {
        $cycles = $request->input('cycle_id', []);
        $cycleDays = $request->input('cycle_day', []);
        $shiftIds = $request->input('shift_id', []);
        $dayNames = $request->input('day_name', []);

        // Collect IDs of cycles that should remain after the operation
        $cyclesToKeep = [];

        foreach ($cycleDays as $index => $cycleDay) {
            $cycleId = $cycles[$index] ?? null;
            $shiftId = $shiftIds[$index];
            $dayName = $dayNames[$index];

            if ($cycleId) {
                // Update existing cycle
                ShiftPatternCycle::where('id', $cycleId)->update([
                    'cycle_day' => $cycleDay,
                    'shift_id' => $shiftId,
                    'day_name' => $dayName,
                ]);

                // Mark this cycle to be kept
                $cyclesToKeep[] = $cycleId;
            } else {
                // Add new cycle
                $newCycle = ShiftPatternCycle::create([
                    'pattern_id' => $id,
                    'cycle_day' => $cycleDay,
                    'shift_id' => $shiftId,
                    'day_name' => $dayName,
                ]);

                // Add the new cycle ID to the list of cycles to keep
                $cyclesToKeep[] = $newCycle->id;
            }
        }

        // Remove any cycles that were not updated or created
        ShiftPatternCycle::where('pattern_id', $id)->whereNotIn('id', $cyclesToKeep)->delete();

        return response()->json(['success' => true]);
    }



    public function deleteCycle($id)
    {
        $delete = DB::table('shift_pattern_cycle')->where('id', $id)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }
}
