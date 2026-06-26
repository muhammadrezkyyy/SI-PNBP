<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use Illuminate\Http\Request;

class MenuOrderController extends Controller
{
    public function update(Request $request)
    {
        $orderedIds = $request->input('orderedIds');

        if (is_array($orderedIds)) {
            foreach ($orderedIds as $index => $id) {
                AdminMenu::where('id', $id)->update(['order' => $index]);
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }
}
