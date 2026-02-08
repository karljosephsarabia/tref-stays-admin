<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SMD\Amazon\Polly\AmazonPollyClient;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsArea;
use SMD\Common\ReservationSystem\Models\RsZipCode;

class AreaController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('ajax.json')->only(['update', 'delete']);
    }

    public function index(Request $request)
    {
        return view('areas')->with([
            'areas' => $this->loadAreas()
        ]);
    }

    public function show(Request $request)
    {
        return response()->json([
            'render' => view('partials.area-accordion')
                ->with([
                    'areas' => $this->loadAreas()
                ])
                ->render()
        ]);
    }

    public function edit(Request $request, $id = null)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
            'menu_order' => 'required|numeric|min:1',
        ]);

        try {
            $changes = 0;
            $area = RsArea::findOrFail($id);

            if (trim($request->input('name')) != $area->name) {
                $area->name = trim($request->input('name'));
                $changes++;
            }

            if (trim($request->input('menu_order')) != $area->menu_order) {
                $area->menu_order = trim($request->input('menu_order'));
                $changes++;
            }

            if ($changes > 0) {
                $area->save();
                GeneralHelper::saveAreaRecording($area, null);
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving area::' . $e->getMessage());
        }
    }

    public function addEdit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
            'menu_order' => 'required|numeric|min:1',
        ]);

        try {
            $area = new RsArea();

            if (!is_null_or_empty($request->input('id'))) {
                $area = RsArea::findOrFail($request->input('id'));
            }

            $area->name = $request->input('name');
            $area->menu_order = $request->input('menu_order');
            $area->parent_area_id = $request->input('parent_id');
            $area->custom_recorded = 0;
            $area->active = $request->has('active');
            $area->save();
            GeneralHelper::saveAreaRecording($area, null);
            return $this->jsonSuccessResponse();

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving area::' . $e->getMessage());
        }
    }

    public function delete(Request $request, $id = null)
    {
        try {
            $area = RsArea::findOrFail($id);

            $this->recursiveDeleteArea($area);

            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving area::' . $e->getMessage());
        }
    }

    public function addEditZipcode(Request $request)
    {
        $this->validate($request, [
            'area_id' => 'required',
            'zipcode' => 'required|max:15',
        ]);

        try {
            //check if area has subareas: only add when not subareas
            $subareas = RsArea::where('parent_area_id', $request->input('area_id'))->get();

            if (count($subareas) > 0) {
                return $this->errorProcessingResponse('Error saving zipcode:: Zip Codes only can be added to Areas without Sub-Areas');
            }

            $current_zipcode = RsZipCode::where('id', $request->input('id'))->first();

            if ($current_zipcode != null) {
                $current_zipcode->area_id = null;
                $current_zipcode->save();
            }

            $zipcode = RsZipCode::where('zipcode', trim($request->input('zipcode')))->first();

            if ($zipcode) {
                $zipcode->area_id = $request->input('area_id');
                $zipcode->save();
                return $this->jsonSuccessResponse();
            } else {
                //return $this->errorProcessingResponse('Error saving zipcode:: Zip Code doesn\'t exists');
                $zipcode = new RsZipCode();
                $zipcode->zipcode = trim($request->input('zipcode'));
                $zipcode->area_id = $request->input('area_id');
                $zipcode->save();
                return $this->jsonSuccessResponse();
            }

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving zipcode::' . $e->getMessage());
        }
    }

    public function deleteZipcode(Request $request, $id = null)
    {
        try {
            $zipcode = RsZipCode::where('id', $id)->first();

            if ($zipcode) {
                $zipcode->area_id = null;
                $zipcode->save();
                return $this->jsonSuccessResponse();
            } else {
                return $this->errorProcessingResponse('Error saving zipcode:: Zip Code doesn\'t exists');
            }

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving zipcode::' . $e->getMessage());
        }
    }

    private function loadAreas($parentId = 0)
    {
        $result = [];
        $areas = RsArea::where('parent_area_id', $parentId)
            ->orderBy('menu_order', 'asc')
            ->get();

        foreach ($areas as $area) {
            $result[] = (object)[
                'id' => $area->id,
                'parent_id' => $area->parent_area_id,
                'name' => $area->name,
                'active' => $area->active,
                'menu_order' => $area->menu_order,
                'zipcodes' => $this->loadZipcodes($area->id),
                'subareas' => $this->loadAreas($area->id),
            ];
        }

        return $result;
    }

    private function loadZipcodes($area_id)
    {
        $result = [];

        $zipcodes = RsZipCode::where('area_id', $area_id)->get();

        foreach ($zipcodes as $zipcode) {
            $result[] = (object)[
                'id' => $zipcode->id,
                'zipcode' => $zipcode->zipcode,
                'area_id' => $zipcode->area_id
            ];
        }

        return $result;
    }

    private function recursiveDeleteArea($area)
    {
        try {
            //detach zipcodes
            $zipcodes = RsZipCode::where('area_id', $area->id);
            $zipcodes->update(['area_id' => null]);

            //delete subareas
            $subareas = RsArea::where('parent_area_id', $area->id)->get();

            foreach ($subareas as $subarea) {
                $this->recursiveDeleteArea($subarea);
            }

            $area->delete();
        } catch (\Exception $e) {
            return;
        }
    }
}
