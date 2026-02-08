<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsPointInterest;

class PointInterestController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('ajax.json')->only(['update', 'delete']);
    }

    public function index(Request $request)
    {
        return view('point-interests')->with([
            'points' => $this->loadPointInterests()
        ]);
    }

    public function show(Request $request)
    {
        return response()->json([
            'render' => view('partials.point-accordion')
                ->with([
                    'points' => $this->loadPointInterests()
                ])
                ->render()
        ]);
    }

    private function loadPointInterests($parentId = 0, $category = 'menu')
    {
        $points = [];

        $point_interests = RsPointInterest::where('parent_id', $parentId)
            ->where('category', $category)
            ->orderBy('menu_order', 'asc')->get();

        foreach ($point_interests as $point) {
            $points[] = (object)[
                'points' => $this->loadPointInterests($point->id, 'point'),
                'categories' => $this->loadPointInterests($point->id, 'category'),
                'id' => $point->id,
                'name' => $point->name,
                'category' => $point->category,
                'menu_order' => $point->menu_order,
                'active' => $point->active ? true : false,
                'parent_id' => $point->parent_id,
                'map_place_name' => $point->map_place_name,
                'map_address' => $point->map_address,
                'map_lat' => $point->map_lat,
                'map_lng' => $point->map_lng
            ];
        }

        return $points;
    }

    public function addEdit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'menu_order' => 'required|numeric|min:1',
            'category' => 'required|in:' . join(",", ['menu', 'point', 'category']),
            'map_lat' => 'required_if:category,==,point|nullable|string',
            'map_lng' => 'required_if:category,==,point|nullable|string',
        ]);

        try {
            $pointInterest = new RsPointInterest();

            if (!is_null_or_empty($request->input('id'))) {
                $pointInterest = RsPointInterest::findOrFail($request->input('id'));
            }

            $pointInterest->name = $request->input('name');
            $pointInterest->category = $request->input('category');
            $pointInterest->menu_order = $request->input('menu_order');
            $pointInterest->parent_id = $request->input('parent_id');
            $pointInterest->map_lng = $request->input('map_lng');
            $pointInterest->map_lat = $request->input('map_lat');
            $pointInterest->map_address = $request->input('map_address');
            $pointInterest->map_place_name = $request->input('map_place_name');
            $pointInterest->custom_recorded = 0;
            $pointInterest->active = $request->has('active');
            $pointInterest->save();
            GeneralHelper::savePointInterestRecording($pointInterest, null);
            return $this->jsonSuccessResponse();

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving item :: ' . $e->getMessage());
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $point_interest = RsPointInterest::findOrFail($id);

            $this->recursiveDeletePointInterest($point_interest);

            return $this->jsonSuccessResponse();

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving item :: ' . $e->getMessage());
        }
    }

    private function recursiveDeletePointInterest($point_interest)
    {
        try {
            $children = RsPointInterest::where('parent_id', $point_interest->id)->get();

            foreach ($children as $child) {
                $this->recursiveDeletePointInterest($child);
            }

            $point_interest->delete();
        } catch (\Exception $e) {
            return;
        }
    }
}
