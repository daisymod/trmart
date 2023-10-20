<?php

namespace App\Fields;

use App\Forms\BasicForm;
use App\Forms\CatalogAdminForm;
use App\Models\Catalog;
use App\Models\CatalogCharacteristic;
use App\Services\BreadcrumbService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CatalogField extends RelationField
{
    protected static Model|string $model = Catalog::class;
    protected BasicForm|string $form = CatalogAdminForm::class;
    protected array $findFields = [
        "name_ru"
    ];
    public int $parentId = 0; //ИД родителя
    public int $ignoreId = 0; //Пропускать ид
    public bool $level1Only = false; //Показывать только 1-й уровень
    public bool $selectParent = true; //Можно выбирать родителей

    public function __construct(string $field = "", Model|null &$record = null, $config = [])
    {

        parent::__construct($field, $record, $config);
        if (get_class($record) == static::$model AND !empty($record->id)) {
            $this->ignoreId = $record->id;
        }
    }

    public function getModelQuery()
    {
        $query = static::$model::query()
            ->orderBy("name_ru");
        if ($this->level1Only) {
            $query = $query->where("parent_id", 0);
        }
        return $query;
    }

    protected function getValue(array $data)
    {

        return $this->record->{$this->field}()->getQuery()->pluck("catalogs.name_ru", "catalogs.id")->toArray();
    }

    public function actionInit($date): string{
        $find = [];
        parse_str($date["find_data"], $find);
        $config = request("config", []);
        $records = $this->getModelQuery();
        $records = $records->with("child", function ($query) use ($config) {
            $query->where("id", "<>" , $config["ignoreId"]);
        });
        $records = $records->where("parent_id", $config["parentId"]);
        $records = $records->where("id", "<>" , $config["ignoreId"]);
        $records = $this->form->formCreateFind($records, $find);
        $records = $records->paginate(20);
        $breadcrumbs = [];
        if (!empty($config["parentId"])) {
            $breadcrumbs = BreadcrumbService::get(static::$model::query()->with("parent")->findOrFail($config["parentId"]));
        }
        return view("fields." . class_basename($this) . "_table", compact("records", "config", "breadcrumbs"))->render();
    }

    public function actionList($date): string
    {
        $find = [];
        parse_str($date["find_data"], $find);
        $config = request("config", []);
        $records = $this->getModelQuery();
        $records = $records->with("child", function ($query) use ($config) {
            $query->where("id", "<>" , $config["ignoreId"]);
        });
        $records = $records->where("parent_id", $config["parentId"]);
        $records = $records->where("id", "<>" , $config["ignoreId"]);
        $records = $this->form->formCreateFind($records, $find);
        $records = $records->paginate(20);
        $breadcrumbs = [];
        if (!empty($config["parentId"])) {
            $breadcrumbs = BreadcrumbService::get(static::$model::query()->with("parent")->findOrFail($config["parentId"]));
        }
        return view("fields." . class_basename($this) . "_table", compact("records", "config", "breadcrumbs"))->render();
    }

    public function createFind(Builder $db, array $data)
    {
        if (!empty($data[$this->field])) {
            $key = $this->record->{$this->field}()->getRelated()->getKeyName();
            $ids = $data[$this->field];
            $db = $db->whereHas($this->field, function ($query) use ($key, $ids) {
                $query->whereIn("catalogs.id", $ids);
            });
        }
        return $db;
    }

    public static function getShowFields($id)
    {
        $all = CatalogCharacteristic::all()->toArray();
        $select = array_merge(CatalogCharacteristic::query()
            ->where("basic", "Y")
            ->get()
            ->toArray(), static::getShowFieldsSelect($id));
        usort($select, function($a, $b){
            return ($a["position"] - $b["position"]);
        });
        return compact("all", "select");
    }

    protected static function getShowFieldsSelect($id)
    {
        $record = Catalog::query()
            ->with("characteristics")
            ->with("parent")
            ->find($id);
        $select = [];
        if (!empty($record->characteristics)) {
            $select = $record->characteristics->toArray();
        } elseif (!empty($record->parent)) {
            $select = static::getShowFieldsSelect($record->parent->id);
        }
        return $select;
    }

}
