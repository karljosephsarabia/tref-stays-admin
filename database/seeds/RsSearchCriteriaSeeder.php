<?php

use Illuminate\Database\Seeder;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsCriterionType;
use SMD\Common\ReservationSystem\Models\RsSearchCriterion;

class RsSearchCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Schema::disableForeignKeyConstraints();
        DB::statement('truncate table `rs_search_criteria`;');
        DB::statement('truncate table `rs_criterion_types`;');
        Schema::enableForeignKeyConstraints();

        $typeId = $this->saveCriterionType('Appliance', 1);
        $this->saveCriterion('Fridge',$typeId, 1);
        $this->saveCriterion('Washer',$typeId, 2);

        $typeId = $this->saveCriterionType('Environment', 2);
        $this->saveCriterion('Steam Central',$typeId, 1);
        $this->saveCriterion('Steam Portable',$typeId, 2);

        $typeId = $this->saveCriterionType('Furniture', 3);
        $this->saveCriterion('Dresser',$typeId, 1);
        $this->saveCriterion('Closet',$typeId, 2);
    }

    private function saveCriterionType($name, $menuOrder){
        $type = new RsCriterionType();
        $type->name = $name;
        $type->menu_order = $menuOrder;
        $type->save();
        GeneralHelper::saveCriteriaTypeRecording($type->id, $type->name, null);
        return $type->id;
    }

    private function saveCriterion($name, $typeId, $menuOrder){
        $criterion = new RsSearchCriterion();
        $criterion->name = $name;
        $criterion->criterion_type_id = $typeId;
        $criterion->menu_order = $menuOrder;
        $criterion->save();
        GeneralHelper::saveCriteriaRecording($criterion->id, $criterion->name, null);
    }
}
