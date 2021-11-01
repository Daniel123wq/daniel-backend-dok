<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Config;
use Illuminate\Support\Str;

class Helper
{

    public static function createWhereByW2uiGrid($search, $logicCond = 'AND', &$where) {
        $cond = $logicCond === 'AND' ? '' : 'or';
        if (isset($search) && is_array($search)) {
            foreach ($search as $key => $ele) {
                if ($ele['type'] == 'date') {
                    $tempDate = [];
                    if (is_array($ele['value'])) {
                        foreach ($ele['value'] as $d) {
                            $tempDate[] = self::dateBrToUs($d);
                        }
                    } else {
                        $tempDate = self::dateBrToUs($ele['value']);
                    }
                    $ele['value'] = $tempDate;
                }
                switch ($ele['operator']) {
                    case 'between':
                        $where->{$cond.'whereBetween'}($ele['field'], $ele['value']);
                        break;
                    case '=':
                    case '>':
                    case '<':
                    case '>=':
                    case '<=':        
                        $where->{$cond.'where'}($ele['field'], $ele['operator'], $ele['value']);
                        break;
                    case 'is':
                        $where->{$cond.'where'}($ele['field'], "=", $ele['value']);
                        break;
                    case 'begins':
                        $where->{$cond.'where'}($ele['field'], 'like', $ele['value']."%");
                        break;
                    case 'contains':
                        $where->{$cond.'where'}($ele['field'], 'like', "%".$ele['value']."%");
                        break;
                    case 'ends':
                        $where->{$cond.'where'}($ele['field'], 'like', "%".$ele['value']);
                        break;
                    case 'less':
                        $where->{$cond.'where'}($ele['field'], '<', $ele['value']);
                        break;
                    case 'more':
                        $where->{$cond.'where'}($ele['field'], '>', $ele['value']);
                        break;
                    default:
                        break;
                }
            }
        }
    }

    public static function dateBrToUs($date) {
        if ($date != '') {
            if (sizeof(explode('-', $date)) >= 3) {
                return substr($date, 0, 10);
            }
            return (substr($date, 6, 4) . '-' . substr($date, 3, 2) . '-' . substr($date, 0, 2));
        } else {
            return '0000-00-00';
        }
    }
}
