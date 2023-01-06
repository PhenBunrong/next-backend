<?php

namespace App\Transformers;

use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    /**
     * prepare human-readable time with users timezone.
     *
     * @param    $entity
     * @param $responseData
     * @param  array  $columns
     * @param  bool  $isIncludeDefault
     * @return array
     */
    public function addTimesHumanReadable($entity, $responseData, array $columns = [], bool $isIncludeDefault = true): array
    {
        $auth = app('auth');
        if (! $auth->check()) {
            $responseData['created_at'] = isset($entity->created_at) ? $entity->created_at->toDateTimeString() : null;

            return $responseData;
        }

        $timeZone = $auth->user()->timezone ?? config('app.timezone');
        $readable = function ($column) use ($entity, $timeZone) {
            $at = Carbon::parse($entity->{$column});

            return [
                $column => $at->format(config('settings.formats.datetime_24')),
                $column.'_readable' => $at->diffForHumans(),
                $column.'_tz' => $at->timezone($timeZone)->format(config('settings.formats.datetime_24')),
                $column.'_readable_tz' => $at->timezone($timeZone)->diffForHumans(),
            ];
        };
        $isHasCustom = count($columns) > 0;
        $defaults = ['created_at', 'updated_at', 'deleted_at'];
        // only custom
        if ($isHasCustom && ! $isIncludeDefault) {
            $toBeConvert = $columns;
        }  // custom and defaults
        elseif ($isHasCustom && $isIncludeDefault) {
            $toBeConvert = array_merge($columns, $defaults);
        } // only defaults
        else {
            $toBeConvert = $defaults;
        }
        $return = [];
        foreach ($toBeConvert as $column) {
            $date = $entity->{$column} ?? null;
            $return = array_merge($return, (! is_null($date)) ? array_merge($return, $readable($column)) : []);
        }

        return array_merge($responseData, $return);
    }
}
