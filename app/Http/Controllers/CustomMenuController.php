<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use SMD\Common\Models\Recording;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsAction;
use SMD\Common\ReservationSystem\Models\RsMenu;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class CustomMenuController extends AppBaseController
{
    public function __construct(){
        $this->middleware('ajax.json')->only(['update','delete']);
    }

    public function show(Request $request, $id = null)
    {
        $with = [];
        $mainMenu = RsMenu::where('id',1)->first();
        $menus = $this->loadCustomMenu(1);

        $unusedMenus = RsMenu::where('id', '>', 1)
            ->where('is_menu', '=', 1)
            ->where('active', '=', 0)
            ->whereNull('rs_parent_menu_id')->get();

        $unusedOptions = RsMenu::where('id', '>', 1)
            ->where('is_menu', '=', 0)
            ->where('active', '=', 0)
            ->whereNull('rs_parent_menu_id')->get();

        $optionActions = RsAction::where('active', '=', 1)->get();

        $with['mainMenu'] = $mainMenu;
        $with['menus'] = $menus;
        $with['unusedMenus'] = $unusedMenus;
        $with['unusedOptions'] = $unusedOptions;
        $with['optionActions'] = $optionActions;

        //dd($menus);
        //die;

        return view('custom-menu')->with($with);
    }

    private function loadCustomMenu($parentId)
    {
        if($parentId == null) {
            $menus = RsMenu::whereNull('rs_parent_menu_id')->orderBy('menu_order','asc')->get();
        } else {
            $menus = RsMenu::where('rs_parent_menu_id', $parentId)->orderBy('menu_order','asc')->get();
        }

        foreach($menus as $menu) {
            if($menu->is_menu == 1) {
                $menu->options = $this->loadCustomMenu($menu->id);
            }
        }

        return $menus;
    }

    //===============================================================
    //===============================================================
    public function menuAdd(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:100',
            'menu_order' => 'required|numeric|min:0',
            'tts_text' => 'required'
        ]);

        try{
            //check if there is another menu with same menu_order for same parent_id
            $menus = RsMenu::where('rs_parent_menu_id', $request->input('parent_id'))
                            ->where('menu_order', $request->input('menu_order'))->get();

            if(count($menus) > 0){
                return $this->errorProcessingResponse('Error saving Menu:: The dial number is in use by another menu or option');
            }

            $createRecording = false;
            $menu = RsMenu::find($request->input('id'));

            if(!$menu) {
                $menu = new RsMenu();
                $createRecording = true;
            } else {
                if($menu->tts_text != trim($request->input('tts_text'))){
                    $createRecording = true;
                }
            }

            $menu->name = trim($request->input('name'));
            $menu->menu_order = trim($request->input('menu_order'));
            $menu->rs_parent_menu_id = $request->input('parent_id');
            $menu->tts_text = trim($request->input('tts_text'));
            $menu->is_menu = 1;
            $menu->active = 1;
            $menu->save();

            if($createRecording == true) {
                $recordingId = GeneralHelper::createMenuRecording($menu->id, $menu->tts_text);
                $menu->recording_id = $recordingId;
                $menu->save();
            }

            return $this->jsonSuccessResponse();

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving Menu::' . $e->getMessage());
        }
    }

    public function menuEdit(Request $request, $id = null) {
        if(!is_null_or_empty($id) && $id == 1) {
            $this->validate($request, [
                'tts_text' => 'required'
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|max:100',
                'menu_order' => 'required|numeric|min:0',
                'tts_text' => 'required'
            ]);
        }

        try{
            //check if there is another menu with same menu_order for same parent_id
            $menus = RsMenu::where('id','<>',$id)
                ->where('rs_parent_menu_id', $request->input('parent_id'))
                ->where('menu_order', $request->input('menu_order'))->get();

            if(count($menus) > 0){
                return $this->errorProcessingResponse('Error saving Menu:: The dial number is in use by another menu or option');
            }

            $changes = 0;
            $menu = RsMenu::findOrFail($id);

            if(trim($request->input('name')) != $menu->name){
                $menu->name = trim($request->input('name'));
                $changes++;
            }

            if(trim($request->input('menu_order')) != $menu->menu_order){
                $menu->menu_order = trim($request->input('menu_order'));
                $changes++;
            }

            if(trim($request->input('tts_text')) != $menu->tts_text){
                $menu->tts_text = trim($request->input('tts_text'));

                $recordingId = GeneralHelper::createMenuRecording($menu->id, $menu->tts_text);
                $menu->recording_id = $recordingId;

                $changes++;
            }

            if($changes > 0) {
                $menu->save();
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving menu::' . $e->getMessage());
        }
    }

    public function menuDelete(Request $request, $id = null) {
        try {
            $menu = RsMenu::findOrFail($id);
            $menu->rs_parent_menu_id = null;
            $menu->active = 0;
            $menu->save();
            return $this->jsonSuccessResponse();

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error disabling menu::' . $e->getMessage());
        }
    }

    //===============================================================
    //===============================================================
    public function optionAdd(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:100',
            'menu_order' => 'required|numeric|min:0',
            'action' => 'required'
        ]);

        try{
            //check if there is another menu with same menu_order for same parent_id
            $menus = RsMenu::where('rs_parent_menu_id', $request->input('parent_id'))
                ->where('menu_order', $request->input('menu_order'))->get();

            if(count($menus) > 0){
                return $this->errorProcessingResponse('Error saving Option:: The dial number is in use by another menu or option');
            }

            $menu = RsMenu::find($request->input('id'));

            if(is_null_or_empty($menu)) {
                $menu = new RsMenu();
            }

            $menu->name = trim($request->input('name'));
            $menu->menu_order = trim($request->input('menu_order'));
            $menu->rs_parent_menu_id = $request->input('parent_id');
            $menu->rs_action_id = trim($request->input('action'));
            $menu->is_menu = 0;
            $menu->active = 1;
            $menu->save();

            return $this->jsonSuccessResponse();

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving Option::' . $e->getMessage());
        }
    }

    public function optionEdit(Request $request, $id = null) {
        if(!is_null_or_empty($id) && $id == 1) {
            return $this->errorProcessingResponse('Error saving Option:: invalid option');
        } else {
            $this->validate($request, [
                'name' => 'required|max:100',
                'menu_order' => 'required|numeric|min:0',
                'action' => 'required'
            ]);
        }

        try{
            //check if there is another menu with same menu_order for same parent_id
            $menus = RsMenu::where('id','<>',$id)
                ->where('rs_parent_menu_id', $request->input('parent_id'))
                ->where('menu_order', $request->input('menu_order'))->get();

            if(count($menus) > 0){
                return $this->errorProcessingResponse('Error saving Option:: The dial number is in use by another menu or option');
            }

            $changes = 0;
            $menu = RsMenu::findOrFail($id);

            if(trim($request->input('name')) != $menu->name){
                $menu->name = trim($request->input('name'));
                $changes++;
            }

            if(trim($request->input('menu_order')) != $menu->menu_order){
                $menu->menu_order = trim($request->input('menu_order'));
                $changes++;
            }

            if(trim($request->input('action')) != $menu->rs_action_id){
                $menu->rs_action_id = trim($request->input('action'));
                $changes++;
            }

            if($changes > 0) {
                $menu->save();
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error saving option::' . $e->getMessage());
        }
    }

    public function optionDelete(Request $request, $id = null) {
        try {
            $menu = RsMenu::findOrFail($id);
            $menu->rs_parent_menu_id = null;
            $menu->active = 0;
            $menu->save();
            return $this->jsonSuccessResponse();

        } catch (\Exception $e) {
            return $this->errorProcessingResponse('Error disabling menu::' . $e->getMessage());
        }
    }

    //===============================================================
    public function play($id = null) {
        try {
            $menu = RsMenu::findOrFail($id);

            if(!is_null_or_empty($menu->recording_id)){
                $recording = Recording::findOrFail($menu->recording_id);
                return response($recording->contents)->withHeaders([
                    'Content-Type' => $recording->mime_type,
                    'Content-Transfer-Encoding' => 'Binary',
                    'Content-Length' => $recording->size,
                    'Content-disposition' => 'attachment; filename="'. $recording->id .'.'.$recording->extension.'"',
                ]);
            }
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('recording.file_download_error').': '.$e->getMessage());
        }
    }
}
