<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCriterion;
use App\Http\Requests\AddCriterionType;
use App\Http\Requests\EditCriterion;
use App\Http\Requests\EditCriterionType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use SMD\Common\Models\Recording;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsCriterionType;
use SMD\Common\ReservationSystem\Models\RsPropertyCriterion;
use SMD\Common\ReservationSystem\Models\RsSearchCriterion;
use Yajra\Datatables\Facades\Datatables;

class CriterionController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ajax.json')->only(['update', 'delete']);
    }

    public function show(Request $request, $id = null) {

        return view('criteria');
    }

    public function datatable(){
        $list = RsCriterionType::with('searchCriteria');

        return Datatables::of($list)
            ->addIndexColumn()
            ->setRowId('id')
            ->make(true);
    }

    public function getCriteriaList(Request $request, $id = null){
        $list = RsSearchCriterion::get(['name']);

        return Response::json($list);
    }

    public function getCriteriaByType(Request $request, $id = null) {
        $list = RsSearchCriterion::where('criterion_type_id', $id)->get();

        return Response::json($list);
    }

    public function addType(AddCriterionType $request){
        try{
            $group = new RsCriterionType();
            $group->name = $request->input('name');
            $group->menu_order = $request->input('menu_order');
            $group->save();
            GeneralHelper::saveCriteriaTypeRecording($group->id, $group->name, null);
            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('criteria.error.group_saving') . '::' . $e->getMessage());
        }
    }

    public function editType(EditCriterionType $request, $id = null){
        try{
            $changes = 0;
            $group = RsCriterionType::query()->findOrFail($id);

            if($request->input('name') != $group->name) {
                $group->name = $request->input('name');
                $changes++;
            }

            if($request->input('menu_order') != $group->menu_order) {
                $group->menu_order = $request->input('menu_order');
                $changes++;
            }

            //check if a recording exists
            GeneralHelper::saveCriteriaTypeRecording($group->id, $group->name, null);

            if($changes > 0) {
                $group->save();
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }

        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('criteria.error.group_saving') . '::' . $e->getMessage());
        }
    }

    public function deleteType(Request $request, $id = null){
        $type = RsCriterionType::where('id', $id)->first();

        if($type) {
            //delete criterion
            $criteria = RsSearchCriterion::where('criterion_type_id', $id)->get();

            foreach ($criteria as $criterion){
                //==================================
                //delete all rs_property_criteria that use this criterion
                $propertyCriteria = RsPropertyCriterion::where('criterion_id', $criterion->id)->get();

                foreach ($propertyCriteria as $propertyCriterion){
                    $propertyCriterion->delete();
                }
                //==================================
                $criterion->delete();
            }

            $type->delete();
        }

        return $this->jsonSuccessResponse();
    }

    //======================================
    public function add(AddCriterion $request){
        try{
            $criterion = new RsSearchCriterion();
            $criterion->name = $request->input('name');
            $criterion->menu_order = $request->input('menu_order');
            $criterion->criterion_type_id = $request->input('type_id');

            $criterion->has_quantity = $request->has('has_quantity');
            $criterion->has_distance = $request->has('has_distance');

            $criterion->save();
            GeneralHelper::saveCriteriaRecording($criterion->id, $criterion->name, null);
            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('criteria.error.criterion_saving') . '::' . $e->getMessage());
        }
    }

    public function edit(EditCriterion $request, $id = null){
        try{
            $changes = 0;
            $criterion = RsSearchCriterion::query()->findOrFail($id);

            if($request->input('name') != $criterion->name) {
                $criterion->name = $request->input('name');
                $changes++;
            }

            if($request->input('menu_order') != $criterion->menu_order) {
                $criterion->menu_order = $request->input('menu_order');
                $changes++;
            }

            if($request->has('has_quantity') != $criterion->has_quantity) {
                $criterion->has_quantity = $request->has('has_quantity');
                $changes++;
            }

            if($request->has('has_distance') != $criterion->has_distance) {
                $criterion->has_distance = $request->has('has_distance');
                $changes++;
            }

            GeneralHelper::saveCriteriaRecording($criterion->id, $criterion->name, null);

            if($changes > 0) {
                $criterion->save();
                GeneralHelper::saveCriteriaRecording($criterion->id, $criterion->name, null);
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }

        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('criteria.error.criterion_saving') . '::' . $e->getMessage());
        }
    }

    public function delete(Request $request, $id = null){
        $criterion = RsSearchCriterion::where('id', $id)->first();

        if($criterion) {
            //==================================
            //delete all rs_property_criteria that use this criterion
            $propertyCriteria = RsPropertyCriterion::where('criterion_id', $criterion->id)->get();

            foreach ($propertyCriteria as $propertyCriterion){
                $propertyCriterion->delete();
            }
            //==================================
            $criterion->delete();
        }

        return $this->jsonSuccessResponse();
    }
}
